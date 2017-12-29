<?php

/**
 * Classe de gestion des Composants
 * Manager : Model_composants
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Composant {

    protected $composantId;
    protected $composantFamilleId;
    protected $composantFamille;
    protected $composantDesignation;
    protected $composantUniteId;
    protected $composantUnite;    
    protected $composantTauxTVA;
    protected $composantOptions;
    protected $composantCompositions;
    protected $composantDelete;

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
        $this->composantFamille = $CI->managerFamilles->getFamilleById( $this->composantFamilleId );
        $this->composantUnite = $CI->managerUnites->getUniteById( $this->composantUniteId );
    }

    function hydrateOptions() {
        $CI =& get_instance();
        $this->composantOptions = $CI->managerOptions->getOptionsByComposantId( $this->composantId );
    }   
    function hydrateCompositions() {
        $CI =& get_instance();
        $this->composantCompositions = $CI->managerCompositions->getCompositionsByComposantId( $this->composantId );
    }   

    function getComposantId() {
        return $this->composantId;
    }

    function getComposantDesignation() {
        return $this->composantDesignation;
    }

    function getComposantUniteId() {
        return $this->composantUniteId;
    }

    function getComposantUnite() {
        return $this->composantUnite;
    }

    function getComposantType() {
        return $this->composantType;
    }

    function getComposantTauxTVA() {
        return $this->composantTauxTVA;
    }

    function getComposantOptions() {
        return $this->composantOptions;
    }

    function getComposantDelete() {
        return $this->composantDelete;
    }

    function setComposantId($composantId) {
        $this->composantId = $composantId;
    }

    function setComposantDesignation($composantDesignation) {
        $this->composantDesignation = $composantDesignation;
    }

    function setComposantUniteId($composantUniteId) {
        $this->composantUniteId = $composantUniteId;
    }

    function setComposantUnite($composantUnite) {
        $this->composantUnite = $composantUnite;
    }

    function setComposantType($composantType) {
        $this->composantType = $composantType;
    }

    function setComposantTauxTVA($composantTauxTVA) {
        $this->composantTauxTVA = $composantTauxTVA;
    }

    function setComposantOptions($composantOptions) {
        $this->composantOptions = $composantOptions;
    }

    function setComposantDelete($composantDelete) {
        $this->composantDelete = $composantDelete;
    }
    function getComposantFamilleId() {
        return $this->composantFamilleId;
    }

    function getComposantFamille() {
        return $this->composantFamille;
    }

    function setComposantFamilleId($composantFamilleId) {
        $this->composantFamilleId = $composantFamilleId;
    }

    function setComposantFamille($composantFamille) {
        $this->composantFamille = $composantFamille;
    }
    function getComposantCompositions() {
        return $this->composantCompositions;
    }

    function setComposantCompositions($composantCompositions) {
        $this->composantCompositions = $composantCompositions;
    }
}
