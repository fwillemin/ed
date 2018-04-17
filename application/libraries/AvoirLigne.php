<?php

/**
 * Classe de gestion des Bls.
 * Manager : Model_lignes
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class AvoirLigne {

    protected $avoirLigneId;
    protected $avoirLigneAvoirId;
    protected $avoirLigneFactureLigneId;
    protected $avoirLigneQte;
    protected $avoirLigneDesignation;
    protected $avoirLigneDescription;
    protected $avoirLignePrixUnitaire;
    protected $avoirLigneTauxTVA;
    protected $avoirLigneTotalHT;
    protected $avoirLigneMarge;

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

    function getAvoirLigneId() {
        return $this->avoirLigneId;
    }

    function getAvoirLigneAvoirId() {
        return $this->avoirLigneAvoirId;
    }

    function getAvoirLigneFactureLigneId() {
        return $this->avoirLigneFactureLigneId;
    }

    function getAvoirLigneQte() {
        return $this->avoirLigneQte;
    }

    function getAvoirLigneDesignation() {
        return $this->avoirLigneDesignation;
    }

    function getAvoirLigneDescription() {
        return $this->avoirLigneDescription;
    }

    function getAvoirLignePrixUnitaire() {
        return $this->avoirLignePrixUnitaire;
    }

    function getAvoirLigneTauxTVA() {
        return $this->avoirLigneTauxTVA;
    }

    function getAvoirLigneTotalHT() {
        return $this->avoirLigneTotalHT;
    }

    function setAvoirLigneId($avoirLigneId) {
        $this->avoirLigneId = $avoirLigneId;
    }

    function setAvoirLigneAvoirId($avoirLigneAvoirId) {
        $this->avoirLigneAvoirId = $avoirLigneAvoirId;
    }

    function setAvoirLigneFactureLigneId($avoirLigneFactureLigneId) {
        $this->avoirLigneFactureLigneId = $avoirLigneFactureLigneId;
    }

    function setAvoirLigneQte($avoirLigneQte) {
        $this->avoirLigneQte = $avoirLigneQte;
    }

    function setAvoirLigneDesignation($avoirLigneDesignation) {
        $this->avoirLigneDesignation = $avoirLigneDesignation;
    }

    function setAvoirLigneDescription($avoirLigneDescription) {
        $this->avoirLigneDescription = $avoirLigneDescription;
    }

    function setAvoirLignePrixUnitaire($avoirLignePrixUnitaire) {
        $this->avoirLignePrixUnitaire = $avoirLignePrixUnitaire;
    }

    function setAvoirLigneTauxTVA($avoirLigneTauxTVA) {
        $this->avoirLigneTauxTVA = $avoirLigneTauxTVA;
    }

    function setAvoirLigneTotalHT($avoirLigneTotalHT) {
        $this->avoirLigneTotalHT = $avoirLigneTotalHT;
    }

    function getAvoirLigneMarge() {
        return $this->avoirLigneMarge;
    }

    function setAvoirLigneMarge($avoirLigneMarge) {
        $this->avoirLigneMarge = $avoirLigneMarge;
    }

}
