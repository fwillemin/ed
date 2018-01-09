<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Facturation extends CI_Controller {

    const tauxTVA = 20;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) :
            redirect('secure/login');
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'une affaire dans la bdd
     *
     * @param int $affaireId ID de l'affaire
     * @return boolean TRUE si l'affaire existe
     */
    public function existAffaire($affaireId) {
        $this->form_validation->set_message('existAffaire', 'Cette affaire est introuvable.');
        if ($this->managerAffaires->count(array('affaireId' => $affaireId)) == 1 || !$affaireId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du client dans la bdd
     *
     * @param int $clientId ID du client
     * @return boolean TRUE si le client existe
     */
    public function existClient($clientId) {
        $this->form_validation->set_message('existClient', 'Ce client est introuvable.');
        if ($this->managerClients->count(array('clientId' => $clientId)) == 1 || !$clientId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du client dans la bdd
     *
     * @param int $factureId ID de la facture
     * @return boolean TRUE si la facture existe
     */
    public function existFacture($factureId) {
        $this->form_validation->set_message('existFacture', 'Cette facture est introuvable.');
        if ($this->managerFactures->count(array('factureId' => $factureId)) == 1 || !$factureId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du reglement dans la bdd
     *
     * @param int $reglementId ID du réglement
     * @return boolean TRUE si le réglement existe
     */
    public function existReglement($reglementId) {
        $this->form_validation->set_message('existReglement', 'Ce réglement est introuvable.');
        if ($this->managerReglements->count(array('reglementId' => $reglementId)) == 1 || !$reglementId) :
            return true;
        else :
            return false;
        endif;
    }

    private function ajouterReglement($affaireId, $clientId, $factureId = null, $date, $montant, $mode, $type, $reglementId = null) {

        $reglementFacture = $factureId ? $factureId : null;

        $security = new Token();
        $chaine = $affaireId . $clientId . number_format($montant, 2, '.', '') . $date;
        $token = $security->getToken($chaine);
        if (!$token):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . 'Erreur dans la création du token');
            return false;
        endif;

        $dataReglement = array(
            'reglementDate' => $date,
            'reglementMontant' => $montant,
            'reglementToken' => $token,
            'reglementAffaireId' => $affaireId,
            'reglementClientId' => $clientId,
            'reglementFactureId' => $factureId,
            'reglementMode' => $mode,
            'reglementType' => $type,
            'reglementSourceId' => $reglementId ? $reglementId : 0,
            'reglementUtile' => 1
        );

        $this->db->trans_start();


        $reglement = new Reglement($dataReglement);
        $this->managerReglements->ajouter($reglement);

        /* Si il y a un/des réglements d'origine, on le(s) desactive */
        if ($reglementId):
            $this->majReglementsPrecedents($reglement, $reglementId);
            $reglement->setReglementSourceId($reglementId);
        else:
            $reglement->setReglementSourceId($reglement->getReglementId());
        endif;

        $this->managerReglements->editer($reglement);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' Echec dans l\'enregistrement du réglement.');
            return false;
        endif;

        return $reglement;
    }

    /**
     * Pass tous les réglements ayant comme source le $reglementSourceId en Inactif sauf le réglement Utile
     * @param Reglement $reglementUtile Reglement Utile
     * @param type $reglementSourceId ID du réglement source
     */
    private function majReglementsPrecedents(Reglement $reglementUtile, $reglementSourceId) {

        $reglements = $this->managerReglements->liste(array('reglementSourceId' => $reglementSourceId, 'reglementId <>' => $reglementUtile->getReglementId()));
        if (!empty($reglements)):
            foreach ($reglements as $r):
                $r->setReglementUtile(0);
                $this->managerReglements->editer($r);
            endforeach;
        endif;
    }

    /**
     * Gestion de la demande d'ajout d'un réglement
     */
    public function addReglement() {

        if (!$this->form_validation->run('addReglement')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $reglement = $this->ajouterReglement($this->input->post('addReglementAffaireId'), $this->input->post('addReglementClientId'), $this->input->post('addReglementFactureId'), $this->xth->mktimeFromInputDate($this->input->post('addReglementDate')), floatval($this->input->post('addReglementMontant')), $this->input->post('addReglementMode'), $this->input->post('addReglementType'), $this->input->post('addReglementSourceId'));

        if ($reglement):
            if ($this->input->post('addReglementFactureId')):
                $this->recalculeSolde($this->managerFactures->getFactureById($this->input->post('addReglementFactureId')));
            endif;
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur dans la création du réglement.'));
        endif;
        exit;
    }

    private function recalculeSolde(Facture $facture) {

        $facture->hydrateReglements();
        $totalReglements = 0;
        if ($facture->getFactureReglements()):
            foreach ($facture->getFactureReglements() as $r):
                $totalReglements += $r->getReglementMontant();
            endforeach;
        endif;

        $facture->setFactureSolde($facture->getFactureTotalTTC() - $totalReglements);
        $this->managerFactures->editer($facture);
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

    private function ajouterFacture($affaireId, Client $client, $modeReglement, $objet = '') {

        $dataFacture = array(
            'factureDate' => time(),
            'factureAffaireId' => $affaireId,
            'factureClientId' => $client->getClientId(),
            'factureTotalHT' => 0,
            'factureTotalTVA' => 0,
            'factureTotalTTC' => 0,
            'factureTauxTVA' => $client->getClientExoneration() ? 0 : self::tauxTVA,
            'factureModeReglement' => $modeReglement,
            'factureObjet' => $objet
        );
        $facture = new Facture($dataFacture);
        $this->managerFactures->ajouter($facture);

        return $facture;
    }

    private function getSolde(Facture $facture) {

    }

    private function addLigne(Facture $facture, $item, $qteQuota, $tauxTVA = self::tauxTVA) {

        if ($qteQuota > 0):

            $qte = round($qteQuota * $item['qty'] / 100, 2);
            $totalHT = round($item['price'] * $qte, 2);
            $totalTVA = round($totalHT * $tauxTVA / 100, 2);


            $dataLigne = array(
                'factureLigneFactureId' => $facture->getFactureId(),
                'factureLigneAffaireId' => $facture->getFactureAffaireId(),
                'factureLigneAffaireArticleId' => $item['affaireArticleId'],
                'factureLigneDesignation' => $item['name'],
                'factureLigneDescription' => $item['description'],
                'factureLigneQte' => $qte,
                'factureLigneTarif' => $item['price'],
                'factureLigneRemise' => $item['remise'],
                'factureLigneTotalHT' => $totalHT,
                'factureLigneTotalTVA' => $totalTVA,
                'factureLigneTotalTTC' => $totalHT + $totalTVA,
                'factureLigneQuota' => $qteQuota
            );
            $ligne = new FactureLigne($dataLigne);
            $this->managerFactureLignes->ajouter($ligne);

            return $ligne;

        endif;
    }

    public function addFacture() {

        if ($this->form_validation->run('addFacture')):

            $client = $this->managerClients->getClientById($this->input->post('addFactureClientId'));
            $facture = $this->ajouterFacture($this->input->post('addFactureAffaireId'), $client, $this->input->post('addFactureMode'), $this->input->post('addFactureObjet'));

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

            echo json_encode(array('type' => 'success'));
            exit;

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

    public function listeFactures($etat = 'ALL', $start = null, $end = null) {

        if (!$start):
            $start = mktime(0, 0, 0, date('m'), 1, date('Y'));
        endif;
        if (!$end):
            $end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        endif;
        $where = array('factureNum > ' => 0, 'factureDate >=' => $start, 'factureDate <=' => $end);

        switch ($etat):
            case 'NS':
                $where['factureSolde >'] = 0;
                break;
            case 'S':
                $where['factureSolde'] = 0;
                break;
        endswitch;

        $factures = $this->managerFactures->listeAll($where);

        $data = array(
            'factures' => $factures,
            'title' => 'Factures',
            'description' => 'Liste des factures',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheFacture($factureId) {

        if (!$this->existFacture($factureId)):
            redirect('facturation/listeFactures');
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($factureId);
        if (!$facture->getFactureNum()):
            redirect('facturation/listeFactures');
            exit;
        endif;

        $facture->hydrateClient();
        $facture->hydrateReglements();

        $data = array(
            'facture' => $facture,
            'title' => 'Facture FA' . $facture->getFactureNum(),
            'description' => 'Fiche détaillée de la facture',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

}
