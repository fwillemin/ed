<?php

/**
 * Classe de gestion des Articles.
 * Manager : Model_articles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class AffaireArticle {

    protected $affaireArticleId;
    protected $affaireArticleAffaireId;
    protected $affaireArticleArticleId;
    protected $affaireArticleDesignation;
    protected $affaireArticleDescription;
    protected $affaireArticleQte;
    protected $affaireArticleTarif; /* tarif de vente force ou non */
    protected $affaireArticleRemise;
    protected $affaireArticlePU; /* tarif remisé */
    protected $affaireArticleTotalHT;
    protected $affaireArticlePrixForce; /* BOOL - Indique si le PU est forcé ou calculé à partir des options/composants */
    protected $affaireArticleOptions;

    //protected $affaireArticlePrixDeRevient;

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

    function hydrateAffaireOptions() {

        $CI = & get_instance();
        $this->affaireArticleOptions = $CI->managerAffaireOptions->liste(array('affaireOptionArticleId' => $this->affaireArticleId), 'affaireOptionId ASC');
    }

//    function hydratePrixDeRevient() {
//        $CI = & get_instance();
//        if (!$this->affaireArticleOptions):
//            $this->hydrateAffaireOptions();
//        endif;
//        $prixDeRevient = 0;
//        foreach ($this->affaireArticleOptions as $affaireOption):
//            $prixDeRevient += round($CI->managerOptions->getOptionById($affaireOption->getAffaireOptionOptionId())->getOptionPrixAchat() * $affaireOption->getAffaireOptionQte(), 2);
//        endforeach;
//        $this->affaireArticlePrixDeRevient = $prixDeRevient;
//    }

    function getAffaireArticleId() {
        return $this->affaireArticleId;
    }

    function getAffaireArticleArticleId() {
        return $this->affaireArticleArticleId;
    }

    function getAffaireArticleDesignation() {
        return $this->affaireArticleDesignation;
    }

    function getAffaireArticleDescription() {
        return $this->affaireArticleDescription;
    }

    function getAffaireArticleQte() {
        return $this->affaireArticleQte;
    }

    function getAffaireArticleTarif() {
        return $this->affaireArticleTarif;
    }

    function getAffaireArticleRemise() {
        return $this->affaireArticleRemise;
    }

    function getAffaireArticlePU() {
        return $this->affaireArticlePU;
    }

    function getAffaireArticleTotalHT() {
        return $this->affaireArticleTotalHT;
    }

    function getAffaireArticlePrixForce() {
        return $this->affaireArticlePrixForce;
    }

    function setAffaireArticleId($affaireArticleId) {
        $this->affaireArticleId = $affaireArticleId;
    }

    function setAffaireArticleArticleId($affaireArticleArticleId) {
        $this->affaireArticleArticleId = $affaireArticleArticleId;
    }

    function setAffaireArticleDesignation($affaireArticleDesignation) {
        $this->affaireArticleDesignation = $affaireArticleDesignation;
    }

    function setAffaireArticleDescription($affaireArticleDescription) {
        $this->affaireArticleDescription = $affaireArticleDescription;
    }

    function setAffaireArticleQte($affaireArticleQte) {
        $this->affaireArticleQte = $affaireArticleQte;
    }

    function setAffaireArticleTarif($affaireArticleTarif) {
        $this->affaireArticleTarif = $affaireArticleTarif;
    }

    function setAffaireArticleRemise($affaireArticleRemise) {
        $this->affaireArticleRemise = $affaireArticleRemise;
    }

    function setAffaireArticlePU($affaireArticlePU) {
        $this->affaireArticlePU = $affaireArticlePU;
    }

    function setAffaireArticleTotalHT($affaireArticleTotalHT) {
        $this->affaireArticleTotalHT = $affaireArticleTotalHT;
    }

    function setAffaireArticlePrixForce($affaireArticlePrixForce) {
        $this->affaireArticlePrixForce = $affaireArticlePrixForce;
    }

    function getAffaireArticleAffaireId() {
        return $this->affaireArticleAffaireId;
    }

    function setAffaireArticleAffaireId($affaireArticleAffaireId) {
        $this->affaireArticleAffaireId = $affaireArticleAffaireId;
    }

    function getAffaireArticleOptions() {
        return $this->affaireArticleOptions;
    }

    function setAffaireArticleOptions($affaireArticleOptions) {
        $this->affaireArticleOptions = $affaireArticleOptions;
    }

    function getAffaireArticlePrixDeRevient() {
        return $this->affaireArticlePrixDeRevient;
    }

    function setAffaireArticlePrixDeRevient($affaireArticlePrixDeRevient) {
        $this->affaireArticlePrixDeRevient = $affaireArticlePrixDeRevient;
    }

}
