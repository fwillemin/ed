<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Avoirs extends My_Controller {

    const tauxTVA = 20.00;

    public function __construct() {
        parent::__construct();

        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;
    }

    private function getAvoirsTotaux($tauxTva) {

        return array(
            'avoirTotalHT' => $this->cart->total(),
            'avoirTotalTVA' => round($this->cart->total() * $tauxTva / 100, 2),
            'avoirTotalTTC' => $this->cart->total() + round($this->cart->total() * $tauxTva / 100, 2)
        );
    }

    public function genererAvoir($factureId = null) {
        if (!$this->existFacture($factureId) || !$factureId):
            redirect('facturation/');
            exit;
        endif;

        $this->venteInit();

        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->hydrateLignes();
        $facture->hydrateClient();

        /* on change la session */
        $session = array(
            'affaireType' => 3,
            'affaireFactureId' => $facture->getFactureId()
        );
        $this->session->set_userdata($session);

        foreach ($facture->getFactureLignes() as $l) :

            $this->cart->insert(
                    array(
                        'id' => $l->getFactureLigneId(),
                        'name' => $l->getFactureLigneDesignation(),
                        'description' => $l->getFactureLigneDescription(),
                        'qty' => 0,
                        'price' => round($l->getFactureLigneTarif() * (100 - $l->getFactureLigneRemise()) / 100, 2),
                        'options' => array(
                            'qteVendue' => $l->getFactureLigneQte(),
                            'prixUnitaire' => $l->getFactureLigneTarif(),
                            'remise' => $l->getFactureLigneRemise(),
                            'tauxTVA' => $facture->getFactureTauxTVA()
                        )
                    )
            );

        endforeach;

        $this->cart->insert(
                array(
                    'id' => 'Libre',
                    'name' => 'Libre',
                    'description' => '',
                    'qty' => 0,
                    'price' => 0,
                    'options' => array(
                        'qteVendue' => 1000,
                        'prixUnitaire' => $l->getFactureLigneTarif(),
                        'remise' => 0,
                        'tauxTVA' => $facture->getFactureTauxTVA()
                    )
                )
        );

        redirect('avoirs/avoir');
        exit;
    }

    public function avoir() {

        if (!$this->session->userdata('venteFactureId')):
            redirect('ventes/bdcListe');
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($this->session->userdata('venteFactureId'));
        $facture->hydrateClient();
        $facture->hydrateAvoirs();

        $data = array(
            'facture' => $facture,
            'title' => 'Générer un avoir',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function modAvoirQte() {
        if (!$this->form_validation->run('modAvoirQte')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $this->cart->update(array('rowid' => $this->input->post('rowId'), 'qty' => $this->input->post('qte')));
            echo json_encode(array('type' => 'success'));
        endif;
        exit;
    }

    public function modAvoirPrix() {

        if (!$this->form_validation->run('modAvoirPrix')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $this->cart->update(array('rowid' => $this->input->post('rowId'), 'price' => $this->input->post('prix')));
            echo json_encode(array('type' => 'success'));
        endif;
        exit;
    }

    public function modAvoirName() {

        if (!$this->form_validation->run('modAvoirName')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $this->cart->update(array('rowid' => $this->input->post('rowId'), 'name' => $this->input->post('name')));
            echo json_encode(array('type' => 'success'));
        endif;
        exit;
    }

    public function addAvoir() {

        if (!$this->existFacture($this->session->userdata('venteFactureId')) || $this->cart->total() == 0):
            echo json_encode(array('type' => 'error', 'message' => 'Toutes les quantité sont nulles'));
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($this->session->userdata('venteFactureId'));
        $facture->hydrateAvoirs();
        $facture->hydrateClient();
        $client = $facture->getFactureClient();

        if ($this->cart->total() > ($facture->getFactureTotalHT() - $facture->getFactureTotalAvoirs())):
            echo json_encode(array('type' => 'error', 'message' => 'Le total de l\'avoir est supérieur à la valeur de la facture.'));
            exit;
        endif;

        /* Calcul de la TVA totale */
        if ($facture->getFactureTauxTVA() > 0):
            $tva = round($this->cart->total() * $facture->getFactureTauxTVA() / 100, 2);
        else:
            $tva = 0;
        endif;

        $this->db->trans_start();

        /* création d'un avoir */
        $arrayAvoir = array(
            'avoirFactureId' => $facture->getFactureId(),
            'avoirDate' => time(),
            'avoirClientId' => $client->getClientId(),
            'avoirTotalTVA' => $tva,
            'factureTotalHT' => 0,
            'factureTotalTTC' => 0
        );
        $avoir = new Avoir($arrayAvoir);
        $this->managerAvoirs->ajouter($avoir);

        /* on ajoute les lignes de l'avoir */
        $avoirTotalHT = 0;

        foreach ($this->cart->contents() as $item) :
            if ($item['qty'] > 0 && $item['price'] > 0):
                $ligne = $this->saveNewLigneAvoir($item, $avoir->getAvoirId());
                $avoirTotalHT += $ligne->getAvoirLigneTotalHT();
            endif;
        endforeach;

        /* mise à jour du total de la facture */
        $avoir->setAvoirTotalHT($avoirTotalHT);
        $avoir->setAvoirTotalTTC($avoirTotalHT + $tva);

        $this->managerAvoirs->editer($avoir);

        $facture->hydrateAvoirs();
        $facture->solde();
        $this->managerFactures->editer($facture);

        $this->db->trans_complete();

        echo json_encode(array('type' => 'success', 'avoirId' => $avoir->getAvoirId()));
        exit;
    }

    private function saveNewLigneAvoir($item, $avoirId) {

        $ligneTotalHT = round($item['price'] * $item['qty'], 2);

        if ($item['id'] == 'Libre'):
            $ligneReference = null;
        else:
            $ligneReference = $item['id'];
        endif;

        $dataLigne = array(
            'avoirLigneAvoirId' => $avoirId,
            'avoirLigneFactureLigneId' => $ligneReference,
            'avoirLigneDesignation' => $item['name'],
            'avoirLigneDescription' => $item['description'],
            'avoirLigneQte' => $item['qty'],
            'avoirLignePrixUnitaire' => $item['price'],
            'avoirLigneTotalHT' => $ligneTotalHT,
            'avoirLigneTauxTVA' => $item['options']['tauxTVA']
        );

        $newLigne = new Avoirligne($dataLigne);
        $this->managerAvoirlignes->ajouter($newLigne);
        return $newLigne;
    }

    public function sendAvoirByEmail() {

        if (!$this->existAvoir($this->input->post('avoirId'))):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $avoir = $this->managerAvoirs->getAvoirById($this->input->post('avoirId'));
        $avoir->hydrateClient();
        if (!$avoir->getAvoirClient()->getClientEmail() || !valid_email($avoir->getAvoirClient()->getClientEmail())):
            echo json_encode(array('type' => 'error', 'message' => 'Le client n\'a pas d\'email renseigné ou cet email est invalide.'));
            exit;
        endif;

        if ($this->xth->emailAvoir($avoir)):
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email'));
        endif;
        exit;
    }

}
