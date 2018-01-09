<?php

/**
 * Classe de gestion des Articles.
 * Manager : Model_articles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class AffaireClient {

    protected $affaireClientAffaireId;    
    protected $affaireClientClientId;
    protected $affaireClientPrincipal;
        
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
    
    function getAffaireClientAffaireId() {
        return $this->affaireClientAffaireId;
    }

    function getAffaireClientClientId() {
        return $this->affaireClientClientId;
    }

    function getAffaireClientPrincipal() {
        return $this->affaireClientPrincipal;
    }

    function setAffaireClientAffaireId($affaireClientAffaireId) {
        $this->affaireClientAffaireId = $affaireClientAffaireId;
    }

    function setAffaireClientClientId($affaireClientClientId) {
        $this->affaireClientClientId = $affaireClientClientId;
    }

    function setAffaireClientPrincipal($affaireClientPrincipal) {
        if( $affaireClientPrincipal == 1 ):
            $this->affaireClientPrincipal = 1;
        else:
            $this->affaireClientPrincipal = 0;
        endif;
    }



}
