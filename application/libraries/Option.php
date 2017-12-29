<?php

/**
 * Classe de gestion des Options des composants
 * Manager : Model_options
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Option {
    
    const tva = 0.2;

    protected $optionId;
    protected $optionReference;    
    protected $optionComposantId;
    protected $optionComposant;
    protected $optionNom;
    protected $optionPrixCatalogue;
    protected $optionRemise;
    protected $optionPrixAchat;
    protected $optionCoefficient;
    protected $optionHT;
    protected $optionTVA;
    protected $optionTTC;
    protected $optionActive;
    protected $optionNbUse;
    /* Articles ayant cette option en composition */
    protected $optionArticles;
    
    /**
     * 
     * @param array $valeurs
     * @param type $option
     * Chargement des Objets liés (Composant/Articles) 
     */
    
    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if(!empty($valeurs)) $this->hydrate ($valeurs);
    }

    public function hydrate(array $donnees) {

       foreach ($donnees as $key => $value):
            $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
                $this->$method($value);
        endforeach;
        $CI =& get_instance();
        $this->optionNbUse = $CI->managerCompositions->count( array('compositionOptionId' => $this->optionId) );
    }
    
    function hydrateArticles() {
        $CI =& get_instance();
        $this->optionArticles = $CI->managerArticles->getArticlesByOptionId( $this->optionId );

    }
    
    function hydrateComposant() {
        $CI =& get_instance();
        $this->optionComposant = $CI->managerComposants->getComposantById( $this->optionComposantId );
    }
    
    function majPrix() {
        
        /* Mise à jour des données financières de l'option */
        //$this->optionPrixAchat = round( $this->optionPrixCatalogue * ( 100 - $this->optionRemise )/100, 2);
        $this->optionHT = round( $this->optionPrixAchat * $this->optionCoefficient, 2 );
        $this->optionTVA = round( $this->optionHT * self::tva, 2);
        $this->optionTTC = $this->optionHT + $this->optionTVA;
        
    }
    
    function getOptionId() {
        return $this->optionId;
    }

    function getOptionReference() {
        return $this->optionReference;
    }

    function getOptionComposantId() {
        return $this->optionComposantId;
    }

    function getOptionComposant() {
        return $this->optionComposant;
    }

    function getOptionNom() {
        return $this->optionNom;
    }

    function getOptionPrixCatalogue() {
        return $this->optionPrixCatalogue;
    }

    function getOptionRemise() {
        return $this->optionRemise;
    }

    function getOptionCoefficient() {
        return $this->optionCoefficient;
    }

    function getOptionHT() {
        return $this->optionHT;
    }

    function getOptionTVA() {
        return $this->optionTVA;
    }

    function getOptionTTC() {
        return $this->optionTTC;
    }

    function getOptionActive() {
        return $this->optionActive;
    }

    function getOptionNbUse() {
        return $this->optionNbUse;
    }

    function getOptionArticles() {
        return $this->optionArticles;
    }

    function setOptionId($optionId) {
        $this->optionId = $optionId;
    }

    function setOptionReference($optionReference) {
        $this->optionReference = $optionReference;
    }

    function setOptionComposantId($optionComposantId) {
        $this->optionComposantId = $optionComposantId;
    }

    function setOptionComposant($optionComposant) {
        $this->optionComposant = $optionComposant;
    }

    function setOptionNom($optionNom) {
        $this->optionNom = $optionNom;
    }

    function setOptionPrixCatalogue($optionPrixCatalogue) {
        $this->optionPrixCatalogue = $optionPrixCatalogue;
        $this->majPrix();
    }

    function setOptionRemise($optionRemise) {
        $this->optionRemise = $optionRemise;
        $this->majPrix();
    }

    function setOptionCoefficient($optionCoefficient) {
        $this->optionCoefficient = $optionCoefficient;
        $this->majPrix();
    }

    function setOptionHT($optionHT) {
        $this->optionHT = $optionHT;
    }

    function setOptionTVA($optionTVA) {
        $this->optionTVA = $optionTVA;
    }

    function setOptionTTC($optionTTC) {
        $this->optionTTC = $optionTTC;
    }

    function setOptionActive($optionActive) {
        $this->optionActive = $optionActive;
    }

    function setOptionNbUse($optionNbUse) {
        $this->optionNbUse = $optionNbUse;
    }

    function setOptionArticles($optionArticles) {
        $this->optionArticles = $optionArticles;
    }
    function getOptionPrixAchat() {
        return $this->optionPrixAchat;
    }

    function setOptionPrixAchat($optionPrixAchat) {
        $this->optionPrixAchat = $optionPrixAchat;
    }


}
