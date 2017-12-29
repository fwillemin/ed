<?php

/**
 * Classe de gestion des Articles
 * Manager : Model_articles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Article {

    protected $articleId;
    protected $articleFamilleId;
    protected $articleFamille;
    protected $articleDesignation;
    protected $articleDescription;
    protected $articleCompositions;
    protected $articleHT;
    protected $articleAchatHT;
    protected $articleDelete;

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
        $this->articleCompositions = $CI->managerCompositions->getCompositionsByArticleId( $this->articleId );
        $this->articleFamille = $CI->managerFamilles->getFamilleById( $this->articleFamilleId );
    }
    
    function updatePrice() {
        
        if( empty( $this->articleCompositions )):            
            $this->articleHT = 0;
            $this->articleAchatHT = 0;
        else:            
            $total = 0;
            $totalAchat = 0;
            foreach( $this->articleCompositions as $c):
                $total += round( $c->getCompositionQte() * $c->getCompositionOption()->getOptionHT(), 2 );
                $totalAchat += round( $c->getCompositionQte() * $c->getCompositionOption()->getOptionPrixAchat(), 2 );
            endforeach;
            $this->articleHT = $total;
            $this->articleAchatHT = $totalAchat;
        endif;      
    }
    
    function getArticleId() {
        return $this->articleId;
    }

    function getArticleDesignation() {
        return $this->articleDesignation;
    }

    function getArticleDescription() {
        return $this->articleDescription;
    }

    function getArticleCompositions() {
        return $this->articleCompositions;
    }

    function getArticleDelete() {
        return $this->articleDelete;
    }

    function setArticleId($articleId) {
        $this->articleId = $articleId;
    }

    function setArticleDesignation($articleDesignation) {
        $this->articleDesignation = $articleDesignation;
    }

    function setArticleDescription($articleDescription) {
        $this->articleDescription = $articleDescription;
    }

    function setArticleCompositions($articleCompositions) {
        $this->articleCompositions = $articleCompositions;
    }

    function setArticleDelete($articleDelete) {
        $this->articleDelete = $articleDelete;
    }
    function getArticleHT() {
        return $this->articleHT;
    }

    function setArticleHT($articleHT) {
        $this->articleHT = $articleHT;
    }
    function getArticleAchatHT() {
        return $this->articleAchatHT;
    }

    function setArticleAchatHT($articleAchatHT) {
        $this->articleAchatHT = $articleAchatHT;
    }
    function getArticleFamilleId() {
        return $this->articleFamilleId;
    }

    function setArticleFamilleId($articleFamilleId) {
        $this->articleFamilleId = $articleFamilleId;
    }
    function getArticleFamille() {
        return $this->articleFamille;
    }

    function setArticleFamille($articleFamille) {
        $this->articleFamille = $articleFamille;
    }


}
