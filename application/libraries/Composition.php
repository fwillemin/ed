<?php

/**
 * Classe de gestion des Compositions
 * Manager : Model_compositions
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Composition {

    protected $compositionId;
    protected $compositionArticleId;
    protected $compositionArticle;
    protected $compositionComposantId;
    protected $compositionComposant;
    protected $compositionOptionId;
    protected $compositionOption;
    protected $compositionQte;
    
    /**
     * 
     * @param array $valeurs Valeurs à Hydrater dans les attributs
     * @param str $option Permet de choisir les Objets à charger
     * L'option permet de charger les objets fils de l'objet Composition avec dans l'ordre : Article, Composant, Option et en valeur 0 ou 1
     * 101 => charge l'article et l'option
     * 000 => Aucun chargement
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
        $this->hydrateOption();
    }
    
    function hydrateArticle() {
        $CI =& get_instance();
        $this->compositionArticle = $CI->managerArticles->getArticleById( $this->compositionArticleId );
    }
    function hydrateComposant() {
        $CI =& get_instance();
        $this->compositionComposant = $CI->managerComposants->getComposantById( $this->compositionComposantId );
    }
    function hydrateOption() {
        $CI =& get_instance();
        $this->compositionOption = $CI->managerOptions->getOptionById( $this->compositionOptionId );
    }
    
    function getCompositionId() {
        return $this->compositionId;
    }

    function getCompositionArticleId() {
        return $this->compositionArticleId;
    }

    function getCompositionArticle() {
        return $this->compositionArticle;
    }

    function getCompositionComposantId() {
        return $this->compositionComposantId;
    }

    function getCompositionComposant() {
        return $this->compositionComposant;
    }

    function getCompositionOptionId() {
        return $this->compositionOptionId;
    }

    function getCompositionOption() {
        return $this->compositionOption;
    }

    function getCompositionQte() {
        return $this->compositionQte;
    }

    function setCompositionId($compositionId) {
        $this->compositionId = $compositionId;
    }

    function setCompositionArticleId($compositionArticleId) {
        $this->compositionArticleId = $compositionArticleId;
    }

    function setCompositionArticle($compositionArticle) {
        $this->compositionArticle = $compositionArticle;
    }

    function setCompositionComposantId($compositionComposantId) {
        $this->compositionComposantId = $compositionComposantId;
    }

    function setCompositionComposant($compositionComposant) {
        $this->compositionComposant = $compositionComposant;
    }

    function setCompositionOptionId($compositionOptionId) {
        $this->compositionOptionId = $compositionOptionId;
    }

    function setCompositionOption($compositionOption) {
        $this->compositionOption = $compositionOption;
    }

    function setCompositionQte($compositionQte) {
        $this->compositionQte = $compositionQte;
    }


}
