<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Facturation extends My_Controller {

    const tauxTVA = 20;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) :
            redirect('secure/login');
        endif;
    }

    private function getEcheance($emission, $typeEcheance) {
        switch ($typeEcheance):
            case 1:
            case 4:
                return $emission;
                break;
            case 2:
                return ($emission + (30 * 86400));
                break;
            case 3:
                return ($emission + (45 * 86400));
                break;
        endswitch;
    }

    private function ajouterReglement($affaireId, $clientId, $factureId = null, $date, $montant, $mode, $type, $reglementId = null) {

        $reglementSource = $this->managerReglements->getReglementById($reglementId);

        $dataReglement = array(
            'reglementDate' => $date,
            'reglementMontant' => $montant,
            'reglementToken' => null,
            'reglementAffaireId' => $affaireId,
            'reglementClientId' => $clientId,
            'reglementFactureId' => $factureId,
            'reglementMode' => $mode,
            'reglementType' => $type,
            'reglementSourceId' => $reglementSource ? $reglementSource->getReglementId() : null,
            'reglementGroupeId' => $reglementSource ? $reglementSource->getReglementGroupeId() : null,
            'reglementUtile' => 1
        );

        $this->db->trans_start();

        $reglement = new Reglement($dataReglement);
        $this->managerReglements->ajouter($reglement);

        /* Si il y a un réglement d'origine, on le desactive */
        if ($reglementSource):
            $reglementSource->setReglementUtile(0);
            $this->managerReglements->editer($reglementSource);
        else:
            $reglement->setReglementSourceId($reglement->getReglementId());
            $reglement->setReglementGroupeId($reglement->getReglementId());
        endif;

        /* Génération du TOKEN */
        $security = new Token();
        $chaine = $affaireId . $clientId . number_format($montant, 2, '.', '') . $date . $reglement->getReglementSourceId() . $reglement->getReglementGroupeId();
        $token = $security->getToken($chaine);
        if (!$token):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . 'Erreur dans la création du token');
            return false;
        endif;
        $reglement->setReglementToken($token);
        $this->managerReglements->editer($reglement);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' Echec dans l\'enregistrement du réglement.');
            return false;
        endif;

        return $reglement;
    }

    public function addReglement() {

        if (!$this->form_validation->run('addReglement')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $reglement = $this->ajouterReglement($this->input->post('addReglementAffaireId'), $this->input->post('addReglementClientId'), $this->input->post('addReglementFactureId'), $this->xth->mktimeFromInputDate($this->input->post('addReglementDate')), floatval($this->input->post('addReglementMontant')), $this->input->post('addReglementMode'), $this->input->post('addReglementType'), $this->input->post('addReglementId'));

        if ($reglement):
            if ($this->input->post('addReglementFactureId')):
                $this->setFactureSolde($this->managerFactures->getFactureById($this->input->post('addReglementFactureId')));
            endif;
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur dans la création du réglement.'));
        endif;
        exit;
    }

    public function recalculeSolde($factureId) {
        if (!$this->existFacture($factureId)):
            redirect('ventes/bdcListe');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $this->setFactureSolde($facture);
        redirect('facturation/ficheFacture/' . $facture->getFactureId());
        exit;
    }

    public function getReglement() {
        if (!$this->form_validation->run('getReglement')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else:

            $reglement = $this->managerReglements->getReglementById($this->input->post('reglementId'), 'array');
            echo json_encode(array('type' => 'success', 'reglement' => $reglement));
            exit;

        endif;
    }

    public function modReglement() {

        if (!$this->form_validation->run('modReglement')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else:
            $reglement = $this->managerReglements->getReglementById($this->input->post('modReglementId'));
            $this->modifierReglement($reglement, $type, $factureId);
            echo json_encode(array('type' => 'success'));
            exit;

        endif;
    }

    /**
     * Modification d'un réglement dans la limite des éléments ne rompant pas l'intégrité du token
     * @param tinyint $type Type de réglement : 1Acompte 2Reglement
     * @param int $factureId ID de la facture à laquelle lier le réglement
     */
    private function modifierReglement(Reglement $reglement, $type, $factureId = null) {

        $reglement->setReglementType($type);
        $reglement->setReglementFactureId($factureId);
        $this->managerRegements->editer($reglement);
    }

    private function ajouterFacture(Affaire $affaire, Client $client, $modeReglement, $objet = '', $echeance = 1) {

        $dataFacture = array(
            'factureDate' => time(),
            'factureType' => $affaire->getAffaireType(), /* Vente Marchandises ou service */
            'factureAffaireId' => $affaire->getAffaireId(),
            'factureClientId' => $client->getClientId(),
            'factureTotalHT' => 0,
            'factureTotalTVA' => 0,
            'factureTotalTTC' => 0,
            'factureTauxTVA' => $client->getClientExoneration() ? 0 : self::tauxTVA,
            'factureModeReglement' => $modeReglement,
            'factureObjet' => $objet,
            'factureEcheanceId' => $echeance,
            'factureEcheanceDate' => $this->getEcheance(time(), $echeance),
            'factureEnvoyee' => 0
        );
        $facture = new Facture($dataFacture);
        $this->managerFactures->ajouter($facture);

        return $facture;
    }

    private function addLigne(Facture $facture, $item, $quota, $tauxTVA = self::tauxTVA) {

        if ($quota > 0):

            $totalHT = round($item['price'] * $item['qty'] * ($quota / 100), 2);
            $totalTVA = round($totalHT * $tauxTVA / 100, 2);

            $dataLigne = array(
                'factureLigneFactureId' => $facture->getFactureId(),
                'factureLigneAffaireId' => $facture->getFactureAffaireId(),
                'factureLigneAffaireArticleId' => $item['affaireArticleId'],
                'factureLigneDesignation' => $item['name'],
                'factureLigneDescription' => $item['description'],
                'factureLigneQte' => $item['qty'],
                'factureLigneTarif' => $item['price'],
                'factureLigneRemise' => $item['remise'],
                'factureLigneTotalHT' => $totalHT,
                'factureLigneTotalTVA' => $totalTVA,
                'factureLigneTotalTTC' => $totalHT + $totalTVA,
                'factureLigneQuota' => $quota
            );
            $ligne = new FactureLigne($dataLigne);
            $this->managerFactureLignes->ajouter($ligne);

            return $ligne;

        endif;
    }

    public function addFacture() {

        if ($this->form_validation->run('addFacture')):

            $this->db->trans_start();

            $client = $this->managerClients->getClientById($this->input->post('addFactureClientId'));
            $affaire = $this->managerAffaires->getAffaireById($this->input->post('addFactureAffaireId'));
            $facture = $this->ajouterFacture($affaire, $client, $this->input->post('addFactureMode'), $this->input->post('addFactureObjet'), $this->input->post('addFactureEcheancePaiement'));

            $totalFactureHT = $facture->getFactureTotalHT();
            $totalFactureTTC = $facture->getFactureTotalTTC();
            $totalFactureTVA = $facture->getFactureTotalTVA();

            if (!empty($this->input->post('addFactureLignes'))):
                foreach ($this->input->post('addFactureLignes') as $ligne):
                    $item = $this->cart->get_item($ligne[0]);
                    if ($item['qty'] > 0):
                        $ligne = $this->addLigne($facture, $item, $ligne[1], $facture->getFactureTauxTVA());
                        if ($ligne):

                            $totalFactureHT += $ligne->getFactureLigneTotalHT();
                            $totalFactureTVA += $ligne->getFactureLigneTotalTVA();
                            $totalFactureTTC += $ligne->getFactureLigneTotalTTC();

                        endif;
                    endif;
                endforeach;

                $facture->setFactureTotalHT($totalFactureHT);
                $facture->setFactureTotalTVA($totalFactureTVA);
                $facture->setFactureTotalTTC($totalFactureTTC);
                $facture->setFactureSolde($totalFactureTTC);
                $this->managerFactures->editer($facture);

            endif;

            $this->db->trans_complete();

            echo json_encode(array('type' => 'success'));
            exit;

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

    private function resetCriteresFactures() {
        $criteres = array(
            'rechFactureStart' => mktime(0, 0, 0, date('m'), 1, date('Y')),
            'rechFactureEnd' => mktime(23, 59, 59, date('m'), date('t'), date('Y')),
            'rechFactureEtat' => 'ALL'
        );
        $this->session->set_userdata($criteres);
    }

    public function criteresListeFactures($etat = 'ALL', $start = null, $end = null, $factureId = null) {
        if ($factureId && $this->existFacture($factureId)):
            $this->session->set_flashdata('rechFactureId', $factureId);
        endif;
        $this->resetCriteresFactures();
        if ($start):
            $this->session->set_userdata('rechFactureStart', $this->xth->mktimeFromInputDate($start));
        endif;
        if ($end):
            $this->session->set_userdata('rechFactureEnd', $this->xth->mktimeFromInputDate($end) + 86300);
        endif;
        $this->session->set_userdata('rechFactureEtat', $etat);
        redirect('facturation/listeFactures');
    }

    public function listeFactures() {

        if ($this->session->flashdata('rechFactureId')):
            $whereFactures = array('factureId' => $this->session->flashdata('rechFactureId'));
            $whereAvoirs = array('avoirId' => $this->session->flashdata('rechFactureId'));
        else:
            if (!$this->session->userdata('rechFactureStart')):
                $this->criteresListeFactures();
            endif;
            $whereFactures = array('factureDate >=' => $this->session->userdata('rechFactureStart'), 'factureDate <=' => $this->session->userdata('rechFactureEnd'));
            $whereAvoirs = array('avoirDate >=' => $this->session->userdata('rechFactureStart'), 'avoirDate <=' => $this->session->userdata('rechFactureEnd'));

            switch ($this->session->userdata('rechFactureEtat')):
                case 'NS':
                    $whereFactures['factureSolde >'] = 0;
                    break;
                case 'P':
                    $whereFactures['factureSolde'] = 0;
                    break;
            endswitch;
        endif;

        $factures = $this->managerFactures->liste($whereFactures);
        if ($this->session->userdata('rechFactureEtat') != 'NS'):
            $avoirs = $this->managerAvoirs->liste($whereAvoirs);
        else:
            $avoirs = array();
        endif;

        if ($factures):
            foreach ($factures as $f):
                $f->hydrateClient();
            endforeach;
        endif;
        if ($avoirs):
            foreach ($avoirs as $a):
                $a->hydrateClient();
            endforeach;
        endif;

        $data = array(
            'avoirs' => $avoirs,
            'factures' => $factures,
            'title' => 'Factures',
            'description' => 'Liste des factures',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function listeFacturesNonEnvoyees() {

        if (!$this->session->userdata('rechFactureStart')):
            $this->resetCriteresFactures();
        endif;
        $whereFactures = array('factureDate >=' => $this->session->userdata('rechFactureStart'), 'factureDate <=' => $this->session->userdata('rechFactureEnd'), 'factureEnvoyee' => 0);
        $factures = $this->managerFactures->liste($whereFactures);

        if ($factures):
            foreach ($factures as $f):
                $f->hydrateClient();
            endforeach;
        endif;

        $data = array(
            'factures' => $factures,
            'title' => 'Factures',
            'description' => 'Liste des factures',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function listeFacturesRelances() {

        if (!$this->session->userdata('rechFactureStart')):
            $this->criteresListeFactures();
        endif;
        $whereFactures = array('factureDate >=' => $this->session->userdata('rechFactureStart'), 'factureDate <=' => $this->session->userdata('rechFactureEnd'), 'factureSolde >' => 0, 'factureEcheanceDate <=' => (time() - 432000));
        $factures = $this->managerFactures->liste($whereFactures, 'factureEcheanceDate ASC');

        if ($factures):
            foreach ($factures as $f):
                $f->hydrateClient();
            endforeach;
        endif;

        $data = array(
            'factures' => $factures,
            'title' => 'Factures',
            'description' => 'Liste des factures',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function criteresListeReglements($start = null, $end = null) {
        if ($start):
            $this->session->set_userdata('rechReglementStart', $this->xth->mktimeFromInputDate($start));
        else:
            $this->session->set_userdata('rechReglementStart', mktime(0, 0, 0, date('m'), 1, date('Y')));
        endif;
        if ($end):
            $this->session->set_userdata('rechReglementEnd', $this->xth->mktimeFromInputDate($end));
        else:
            $this->session->set_userdata('rechReglementEnd', mktime(23, 59, 59, date('m'), date('t'), date('Y')));
        endif;
        redirect('facturation/listeReglements');
    }

    public function listeReglements() {

        if ($this->session->userdata('rechReglementStart')):
            $whereReglements = array('reglementUtile' => 1, 'reglementDate >=' => $this->session->userdata('rechReglementStart'), 'reglementDate <=' => $this->session->userdata('rechReglementEnd'));
        else:
            $whereReglements = array('reglementUtile' => 1);
        endif;

        $reglements = $this->managerReglements->liste($whereReglements);
        if ($reglements):
            foreach ($reglements as $r):
                $r->hydrateClient();
            endforeach;
        endif;

        $data = array(
            'reglements' => $reglements,
            'title' => 'Réglements',
            'description' => 'Liste des réglements',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function forceSolde($factureId, $solde) {
        if (!$this->existFacture($factureId)):
            redirect('ventes/bdcListe');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->setFactureSolde($solde);
        $this->managerFactures->editer($facture);
        redirect('facturation/ficheFacture/' . $facture->getFactureId());
        exit;
    }

    public function ficheFacture($factureId) {

        if (!$this->existFacture($factureId)):
            redirect('facturation/listeFactures');
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->hydrateClient();
        $facture->hydrateReglements();
        if ($facture->getFactureReglements()):
            foreach ($facture->getFactureReglements() as $r):
                $r->hydrateHistorique();
            endforeach;
        endif;
        $facture->hydrateAvoirs();

        $this->session->set_userdata(array('venteFactureId' => $facture->getFactureId(), 'venteClientId' => $facture->getFactureClientId(), 'venteId' => null));

        $data = array(
            'facture' => $facture,
            'title' => 'Détail facture ' . $facture->getFactureId(),
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    /**
     * NON FONCTIONNEL - MANQUE LA SELECTION DU CONTACT
     * Envoi la facture sur le mail d'un contact du client
     * @param type $contactId
     */
    public function sendFactureByEmail($contactId) {

        if (!$this->existContact($contactId)):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if (!$this->existFacture($this->input->post('factureId'))):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($this->input->post('factureId'));
        $facture->hydrateClient();
        if (!$facture->getFactureClient()->getClientEmail() || !valid_email($facture->getFactureClient()->getClientEmail())):
            echo json_encode(array('type' => 'error', 'message' => 'Le client n\'a pas d\'email renseigné ou cet email est invalide.'));
            exit;
        endif;

        if ($this->xth->emailFacture($facture)):
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email'));
        endif;
        exit;
    }

    public function setFactureEnvoyee() {
        if (!$this->form_validation->run('getFacture')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($this->input->post('factureId'));
        $facture->setFactureEnvoyee($this->input->post('etat'));
        $this->managerFactures->editer($facture);
        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function modEcheanceFacture() {

        if (!$this->form_validation->run('modEcheanceFacture')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($this->input->post('factureId'));
        $facture->setFactureEcheanceId($this->input->post('echeanceId'));
        $facture->setFactureEcheanceDate($this->getEcheance($facture->getFactureDate(), $this->input->post('echeanceId')));
        $this->managerFactures->editer($facture);
        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function migrationFactures() {
        $factures = $this->managerFactures->liste();
        foreach ($factures as $f):
            $f->setFactureEcheanceDate($this->getEcheance($f->getFactureDate(), $f->getFactureEcheanceId()));
            $this->managerFactures->editer($f);
        endforeach;
    }

}
