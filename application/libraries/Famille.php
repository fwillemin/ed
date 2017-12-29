<?php

/**
 * Classe de gestion des Catégories d'articles
 * Manager : Model_familles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Famille {

    protected $familleId;
    protected $familleNom;
    protected $familleNbArticles;

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
        $this->setFamilleNbArticles( $CI->managerComposants->count( array('composantFamilleId' => $this->familleId ) ));
    }

    function getFamilleId() {
        return $this->familleId;
    }

    function getFamilleNom() {
        return $this->familleNom;
    }

    function setFamilleId($familleId) {
        $this->familleId = intval($familleId);
    }

    function setFamilleNom($familleNom) {
        $this->familleNom = $familleNom;
    }
    function getFamilleNbArticles() {
        return $this->familleNbArticles;
    }

    function setFamilleNbArticles($familleNbArticles) {
        $this->familleNbArticles = $familleNbArticles;
    }


}
