<?php

/**
 * Classe de gestion des Articles.
 * Manager : Model_articles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class AffaireOption {

    protected $affaireOptionId;
    protected $affaireOptionAffaireId;
    protected $affaireOptionArticleId; /* Article de classe AffaireArticle pas Article */
    protected $affaireOptionOptionId;
    protected $affaireOptionComposantId;
    protected $affaireOptionQte;
    protected $affaireOptionPU;
    protected $affaireOptionOriginel;
        
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
    }   

    function getAffaireOptionId() {
        return $this->affaireOptionId;
    }

    function getAffaireOptionAffaireId() {
        return $this->affaireOptionAffaireId;
    }

    function getAffaireOptionArticleId() {
        return $this->affaireOptionArticleId;
    }

    function getAffaireOptionOptionId() {
        return $this->affaireOptionOptionId;
    }

    function getAffaireOptionComposantId() {
        return $this->affaireOptionComposantId;
    }

    function getAffaireOptionQte() {
        return $this->affaireOptionQte;
    }

    function getAffaireOptionPU() {
        return $this->affaireOptionPU;
    }

    function setAffaireOptionId($affaireOptionId) {
        $this->affaireOptionId = $affaireOptionId;
    }

    function setAffaireOptionAffaireId($affaireOptionAffaireId) {
        $this->affaireOptionAffaireId = $affaireOptionAffaireId;
    }

    function setAffaireOptionArticleId($affaireOptionArticleId) {
        $this->affaireOptionArticleId = $affaireOptionArticleId;
    }

    function setAffaireOptionOptionId($affaireOptionOptionId) {
        $this->affaireOptionOptionId = $affaireOptionOptionId;
    }

    function setAffaireOptionComposantId($affaireOptionComposantId) {
        $this->affaireOptionComposantId = $affaireOptionComposantId;
    }

    function setAffaireOptionQte($affaireOptionQte) {
        $this->affaireOptionQte = $affaireOptionQte;
    }

    function setAffaireOptionPU($affaireOptionPU) {
        $this->affaireOptionPU = $affaireOptionPU;
    }
    function getAffaireOptionOriginel() {
        return $this->affaireOptionOriginel;
    }

    function setAffaireOptionOriginel($affaireOptionOriginel) {
        $this->affaireOptionOriginel = $affaireOptionOriginel;
    }

}
