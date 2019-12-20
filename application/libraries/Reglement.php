<?php

/**
 * Classe de gestion des Reglements
 * Manager : Model_reglements
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Reglement {

    protected $reglementId;
    protected $reglementDate;
    protected $reglementMontant;
    protected $reglementToken;
    protected $reglementAffaireId;
    protected $reglementClientId;
    protected $reglementClient;
    protected $reglementType; // 1 = Acompte; 2 = Réglement
    protected $reglementFactureId;
    protected $reglementFactureNum;
    protected $reglementMode;
    protected $reglementModeText;
    protected $reglementGroupeId;
    protected $reglementSourceId;
    protected $reglementUtile;
    protected $reglementSecure; /* Verifie la validité du token */
    protected $reglementHistorique;

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

        $CI = & get_instance();
        /* Vérification de l'intégrité du token */
//        $security = new Token();
//        $chaine = $this->reglementAffaireId . $this->reglementClientId . $this->reglementMontant . $this->reglementDate . $this->reglementSourceId . $this->reglementGroupeId;
//        $this->reglementSecure = $security->verifyToken($chaine, $this->reglementToken);
        $this->reglementSecure = true;

        $this->reglementModeText = $CI->xth->affModeReglement($this->reglementMode);

        if ($this->reglementFactureId):
            $this->reglementFactureNum = $CI->managerFactures->getFactureById($this->reglementFactureId, 'array')->factureNum;
        endif;
    }

    public function hydrateHistorique() {
        /* Recherche de l'historique de ce réglement */
        $CI = & get_instance();
        if ($this->reglementUtile == 1):
            $this->reglementHistorique = $CI->managerReglements->historique($this->reglementSourceId);
        endif;
    }

    public function hydrateClient() {
        $CI = & get_instance();
        $this->reglementClient = $CI->managerClients->getClientById($this->reglementClientId);
    }

    function getReglementGroupeId() {
        return $this->reglementGroupeId;
    }

    function setReglementGroupeId($reglementGroupeId) {
        $this->reglementGroupeId = $reglementGroupeId;
    }

    function getReglementId() {
        return $this->reglementId;
    }

    function getReglementDate() {
        return $this->reglementDate;
    }

    function getReglementMontant() {
        return $this->reglementMontant;
    }

    function getReglementToken() {
        return $this->reglementToken;
    }

    function getReglementAffaireId() {
        return $this->reglementAffaireId;
    }

    function getReglementClientId() {
        return $this->reglementClientId;
    }

    function getReglementFactureId() {
        return $this->reglementFactureId;
    }

    function getReglementMode() {
        return $this->reglementMode;
    }

    function getReglementSourceId() {
        return $this->reglementSourceId;
    }

    function getReglementUtile() {
        return $this->reglementUtile;
    }

    function setReglementId($reglementId) {
        $this->reglementId = $reglementId;
    }

    function setReglementDate($reglementDate) {
        $this->reglementDate = $reglementDate;
    }

    function setReglementMontant($reglementMontant) {
        $this->reglementMontant = $reglementMontant;
    }

    function setReglementToken($reglementToken) {
        $this->reglementToken = $reglementToken;
    }

    function setReglementAffaireId($reglementAffaireId) {
        $this->reglementAffaireId = $reglementAffaireId;
    }

    function setReglementClientId($reglementClientId) {
        $this->reglementClientId = $reglementClientId;
    }

    function setReglementFactureId($reglementFactureId) {
        $this->reglementFactureId = $reglementFactureId;
    }

    function setReglementMode($reglementMode) {
        $this->reglementMode = $reglementMode;
    }

    function setReglementSourceId($reglementSourceId) {
        $this->reglementSourceId = $reglementSourceId;
    }

    function setReglementUtile($reglementUtile) {
        $this->reglementUtile = $reglementUtile;
    }

    function getReglementClient() {
        return $this->reglementClient;
    }

    function setReglementClient($reglementClient) {
        $this->reglementClient = $reglementClient;
    }

    function getReglementSecure() {
        return $this->reglementSecure;
    }

    function setReglementSecure($reglementSecure) {
        $this->reglementSecure = $reglementSecure;
    }

    function getReglementHistorique() {
        return $this->reglementHistorique;
    }

    function setReglementHistorique($reglementHistorique) {
        $this->reglementHistorique = $reglementHistorique;
    }

    function getReglementModeText() {
        return $this->reglementModeText;
    }

    function setReglementModeText($reglementModeText) {
        $this->reglementModeText = $reglementModeText;
    }

    function getReglementType() {
        return $this->reglementType;
    }

    function setReglementType($reglementType) {
        $this->reglementType = $reglementType;
    }

    function getReglementFactureNum() {
        return $this->reglementFactureNum;
    }

    function setReglementFactureNum($reglementFactureNum) {
        $this->reglementFactureNum = $reglementFactureNum;
    }

}
