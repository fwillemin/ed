<?php

/**
 * Classe de gestion des Affaires
 * Manager : Model_affaires
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Affaire {

    protected $affaireId;
    protected $affaireClientsIds;
    protected $affaireClients;
    protected $affaireType; /* 1 = Prestation, 2 = Vente */
    protected $affaireObjet;
    protected $affaireDate;
    protected $affaireTauxTVA;
    protected $affaireTotalHT;
    protected $affaireTotalTTC;
    protected $affaireTotalTVA;
    protected $affaireDevisId;
    protected $affaireDevisDate;
    protected $affaireCommandeId;
    protected $affaireCommandeDate;
    protected $affaireReglements;
    protected $affaireFactures;
    protected $affairePAO;
    protected $affaireFabrication;
    protected $affairePose;
    protected $affaireCloture;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;
    }

    public function hydrateClients($format = 'object') {
        $CI = & get_instance();
        $clients = $CI->managerAffaireClients->getClientsByAffaireId($this->affaireId, $format);
        $this->affaireClients = $clients;

        foreach ((array) $clients as $c):
            if ($format == 'object'):
                $ids[] = $c->getClientId();
            else:
                $ids[] = $c->clientId;
            endif;
        endforeach;
        $this->affaireClientsIds = $ids;
    }

    public function hydrateReglements() {
        $CI = & get_instance();
        $this->affaireReglements = $CI->managerReglements->liste(array('reglementAffaireId' => $this->affaireId, 'reglementUtile' => 1));
    }

    public function hydrateFactures() {
        $CI = & get_instance();
        $this->affaireFactures = $CI->managerFactures->liste(array('factureAffaireId' => $this->affaireId));
    }

    function getAffaireId() {
        return $this->affaireId;
    }

    function getAffaireClientsIds() {
        return $this->affaireClientsIds;
    }

    function getAffaireClients() {
        return $this->affaireClients;
    }

    function getAffaireType() {
        return $this->affaireType;
    }

    function getAffaireDate() {
        return $this->affaireDate;
    }

    function getAffaireTauxTVA() {
        return $this->affaireTauxTVA;
    }

    function getAffaireTotalHT() {
        return $this->affaireTotalHT;
    }

    function getAffaireTotalTTC() {
        return $this->affaireTotalTTC;
    }

    function getAffaireTotalTVA() {
        return $this->affaireTotalTVA;
    }

    function getAffaireDevisId() {
        return $this->affaireDevisId;
    }

    function getAffaireDevisDate() {
        return $this->affaireDevisDate;
    }

    function getAffaireCommandeId() {
        return $this->affaireCommandeId;
    }

    function getAffaireCommandeDate() {
        return $this->affaireCommandeDate;
    }

    function setAffaireId($affaireId) {
        $this->affaireId = $affaireId;
    }

    function setAffaireClientsIds($affaireClientsIds) {
        $this->affaireClientsIds = $affaireClientsIds;
    }

    function setAffaireClients($affaireClients) {
        $this->affaireClients = $affaireClients;
    }

    function setAffaireType($affaireType) {
        $this->affaireType = $affaireType;
    }

    function setAffaireDate($affaireDate) {
        $this->affaireDate = $affaireDate;
    }

    function setAffaireTauxTVA($affaireTauxTVA) {
        $this->affaireTauxTVA = $affaireTauxTVA;
    }

    function setAffaireTotalHT($affaireTotalHT) {
        $this->affaireTotalHT = $affaireTotalHT;
    }

    function setAffaireTotalTTC($affaireTotalTTC) {
        $this->affaireTotalTTC = $affaireTotalTTC;
    }

    function setAffaireTotalTVA($affaireTotalTVA) {
        $this->affaireTotalTVA = $affaireTotalTVA;
    }

    function setAffaireDevisId($affaireDevisId) {
        $this->affaireDevisId = $affaireDevisId;
    }

    function setAffaireDevisDate($affaireDevisDate) {
        $this->affaireDevisDate = $affaireDevisDate;
    }

    function setAffaireCommandeId($affaireCommandeId) {
        $this->affaireCommandeId = $affaireCommandeId;
    }

    function setAffaireCommandeDate($affaireCommandeDate) {
        $this->affaireCommandeDate = $affaireCommandeDate;
    }

    function getAffaireReglements() {
        return $this->affaireReglements;
    }

    function setAffaireReglements($affaireReglements) {
        $this->affaireReglements = $affaireReglements;
    }

    function getAffaireFactures() {
        return $this->affaireFactures;
    }

    function setAffaireFactures($affaireFactures) {
        $this->affaireFactures = $affaireFactures;
    }

    function getAffaireObjet() {
        return $this->affaireObjet;
    }

    function setAffaireObjet($affaireObjet) {
        $this->affaireObjet = $affaireObjet;
    }

    function getAffairePAO() {
        return $this->affairePAO;
    }

    function getAffaireFabrication() {
        return $this->affaireFabrication;
    }

    function getAffairePose() {
        return $this->affairePose;
    }

    function setAffairePAO($affairePAO) {
        $this->affairePAO = $affairePAO;
    }

    function setAffaireFabrication($affaireFabrication) {
        $this->affaireFabrication = $affaireFabrication;
    }

    function setAffairePose($affairePose) {
        $this->affairePose = $affairePose;
    }

    function getAffaireCloture() {
        return $this->affaireCloture;
    }

    function setAffaireCloture($affaireCloture) {
        $this->affaireCloture = $affaireCloture;
    }

}
