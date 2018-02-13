<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
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
     * Fonction pour from_validation qui vérifie l'existance du composant dans la bdd
     *
     * @param int $id ID du composant
     * @return boolean TRUE si le composant exist
     */
    public function existComposant($composantId) {
        $this->form_validation->set_message('existComposant', 'Ce composant est introuvable.');
        if ($this->managerComposants->count(array('composantId' => $composantId)) == 1 || !$composantId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance de l'article dans la bdd
     *
     * @param int $id ID de l'article
     * @return boolean
     */
    public function existArticle($articleId) {
        $this->form_validation->set_message('existArticle', 'Cet article est introuvable.');
        if ($this->managerArticles->count(array('articleId' => $articleId)) == 1 || !$articleId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance de l'option dans la bdd
     *
     * @param int $id ID de l'option
     * @return boolean
     */
    public function existOption($optionId) {
        $this->form_validation->set_message('existOption', 'Cette option est introuvable.');
        if ($this->managerOptions->count(array('optionId' => $optionId)) == 1 || !$optionId) :
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
     * Fonction pour from_validation qui vérifie l'existance du contact dans la bdd
     *
     * @param int $contactId ID du contact
     * @return boolean TRUE si le contact existe
     */
    public function existContact($contactId) {
        $this->form_validation->set_message('existContact', 'Ce contact est introuvable.');
        if ($this->managerContacts->count(array('contactId' => $contactId)) == 1 || !$contactId) :
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

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un avoir dans la bdd
     *
     * @param int $avoirId ID de l'avoir
     * @return boolean TRUE si l'avoir existe
     */
    public function existAvoir($avoirId) {
        $this->form_validation->set_message('existAvoir', 'Cet avoir est introuvable.');
        if ($this->managerAvoirs->count(array('avoirId' => $avoirId)) == 1 || !$avoirId) :
            return true;
        else :
            return false;
        endif;
    }

    public function venteInit() {

        $dataSession = array('affaireId', 'affaireClientId', 'affaireExonerationTVA', 'affaireDate');
        $this->session->unset_userdata($dataSession);
        $this->cart->destroy();
        $this->session->set_userdata(
                array(
                    'pleaseSave' => 0,
                    'affaireType' => 1,
                    'affaireClients' => array(),
                    'affaireObjet' => '',
                    'affairePAO' => 0,
                    'affaireFabrication' => 0,
                    'affairePose' => 0
                )
        );
    }

    /**
     * Recalcule le solde d'une facture et la met à jour dans le BDD
     * @param Facture $facture Facture à traiter
     */
    public function setFactureSolde(Facture $facture) {
        $facture->solde();
        $this->managerFactures->editer($facture);
    }

}
