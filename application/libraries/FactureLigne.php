<?php

/**
 * Classe de gestion des Articles.
 * Manager : Model_articles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class FactureLigne {

    protected $factureLigneId;
    protected $factureLigneFactureId;
    protected $factureLigneAffaireId;
    protected $factureLigneAffaireArticleId;
    protected $factureLigneDesignation;
    protected $factureLigneDescription;
    protected $factureLigneQte;
    protected $factureLigneTarif;
    protected $factureLigneRemise;
    protected $factureLigneTotalHT;
    protected $factureLigneTotalTTC;
    protected $factureLigneTotalTVA;
    protected $factureLigneMarge;
    protected $factureLigneQuota; /* INFO : Quota en % du client qui a été appliqué sur la ligne d'origine de l'affaire. */

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

    function getFactureLigneId() {
        return $this->factureLigneId;
    }

    function getFactureLigneFactureId() {
        return $this->factureLigneFactureId;
    }

    function getFactureLigneAffaireId() {
        return $this->factureLigneAffaireId;
    }

    function getFactureLigneAffaireArticleId() {
        return $this->factureLigneAffaireArticleId;
    }

    function getFactureLigneQte() {
        return $this->factureLigneQte;
    }

    function getFactureLigneTarif() {
        return $this->factureLigneTarif;
    }

    function getFactureLigneRemise() {
        return $this->factureLigneRemise;
    }

    function getFactureLigneTotalHT() {
        return $this->factureLigneTotalHT;
    }

    function getFactureLigneTotalTTC() {
        return $this->factureLigneTotalTTC;
    }

    function getFactureLigneTotalTVA() {
        return $this->factureLigneTotalTVA;
    }

    function setFactureLigneId($factureLigneId) {
        $this->factureLigneId = $factureLigneId;
    }

    function setFactureLigneFactureId($factureLigneFactureId) {
        $this->factureLigneFactureId = $factureLigneFactureId;
    }

    function setFactureLigneAffaireId($factureLigneAffaireId) {
        $this->factureLigneAffaireId = $factureLigneAffaireId;
    }

    function setFactureLigneAffaireArticleId($factureLigneAffaireArticleId) {
        $this->factureLigneAffaireArticleId = $factureLigneAffaireArticleId;
    }

    function setFactureLigneQte($factureLigneQte) {
        $this->factureLigneQte = $factureLigneQte;
    }

    function setFactureLigneTarif($factureLigneTarif) {
        $this->factureLigneTarif = $factureLigneTarif;
    }

    function setFactureLigneRemise($factureLigneRemise) {
        $this->factureLigneRemise = $factureLigneRemise;
    }

    function setFactureLigneTotalHT($factureLigneTotalHT) {
        $this->factureLigneTotalHT = $factureLigneTotalHT;
    }

    function setFactureLigneTotalTTC($factureLigneTotalTTC) {
        $this->factureLigneTotalTTC = $factureLigneTotalTTC;
    }

    function setFactureLigneTotalTVA($factureLigneTotalTVA) {
        $this->factureLigneTotalTVA = $factureLigneTotalTVA;
    }

    function getFactureLigneDesignation() {
        return $this->factureLigneDesignation;
    }

    function getFactureLigneDescription() {
        return $this->factureLigneDescription;
    }

    function setFactureLigneDesignation($factureLigneDesignation) {
        $this->factureLigneDesignation = $factureLigneDesignation;
    }

    function setFactureLigneDescription($factureLigneDescription) {
        $this->factureLigneDescription = $factureLigneDescription;
    }

    function getFactureLigneQuota() {
        return $this->factureQuota;
    }

    function setFactureLigneQuota($factureQuota) {
        $this->factureQuota = $factureQuota;
    }

    function getFactureLigneMarge() {
        return $this->factureLigneMarge;
    }

    function setFactureLigneMarge($factureLigneMarge) {
        $this->factureLigneMarge = $factureLigneMarge;
    }

}
