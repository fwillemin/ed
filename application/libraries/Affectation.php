<?php

/**
 * Classe de gestion des Affectations
 * Manager : Model_affectations
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Affectation {

    protected $affectationId;
    protected $affectationDossierId;
    protected $affectationType;
    protected $affectationDate;
    protected $affectationEquipeId;   
    protected $affectationEquipe; 
    protected $affectationIntervenant; 
    protected $affectationPosition;    
    protected $affectationCommentaire;    
    protected $affectationEtat; /* 1=Attente, 2=Encours, 3=Terminé */
    protected $affectationClient;
    protected $affectationDescriptif;
    protected $affectationCouleur;
    protected $affectationFontColor;
    protected $affectationDossierClos;

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
        
        $this->colorier();
    }
    
    private function colorier(){
        /* Couleur de l'affectation */
        switch( $this->getAffectationEtat() ):
            case 1:
                $this->setAffectationCouleur( '#dbe5f8' );
                $this->setAffectationFontColor( '#000000' );
                break;
            case 2:
                $this->setAffectationCouleur( '#F8B038' );
                $this->setAffectationFontColor( '#000000' );
                break;
            case 3:
                $this->setAffectationCouleur( '#0F6837' );
                $this->setAffectationFontColor( '#FFFFFF' );
                break;            
        endswitch;
    }
    
    function nextStep() {
        switch( $this->getAffectationEtat() ):
            case 1:
                $this->setAffectationEtat(2);
                break;
            case 2:
                $this->setAffectationEtat(3);
                break;
            case 3:
                $this->setAffectationEtat(1);
                break;
        endswitch;
        $this->colorier();
    }
 
    function getAffectationId() {
        return $this->affectationId;
    }

    function getAffectationDossierId() {
        return $this->affectationDossierId;
    }

    function getAffectationType() {
        return $this->affectationType;
    }

    function getAffectationDate() {
        return $this->affectationDate;
    }

    function getAffectationEquipeId() {
        return $this->affectationEquipeId;
    }

    function getAffectationPosition() {
        return $this->affectationPosition;
    }

    function getAffectationEtat() {
        return $this->affectationEtat;
    }

    function setAffectationId($affectationId) {
        $this->affectationId = $affectationId;
    }

    function setAffectationDossierId($affectationDossierId) {
        $this->affectationDossierId = $affectationDossierId;
    }

    function setAffectationType($affectationType) {
        $this->affectationType = $affectationType;
    }

    function setAffectationDate($affectationDate) {
        $this->affectationDate = $affectationDate;
    }

    function setAffectationEquipeId($affectationEquipeId) {
        $this->affectationEquipeId = $affectationEquipeId;
    }

    function setAffectationPosition($affectationPosition) {
        $this->affectationPosition = $affectationPosition;
    }

    function setAffectationEtat($affectationEtat) {
        $this->affectationEtat = $affectationEtat;
    }
    function getAffectationEquipe() {
        return $this->affectationEquipe;
    }

    function setAffectationEquipe($affectationEquipe) {
        $this->affectationEquipe = $affectationEquipe;
    }
    function getAffectationCouleur() {
        return $this->affectationCouleur;
    }

    function setAffectationCouleur($affectationCouleur) {
        $this->affectationCouleur = $affectationCouleur;
    }
    function getAffectationClient() {
        return $this->affectationClient;
    }

    function getAffectationDescriptif() {
        return $this->affectationDescriptif;
    }

    function setAffectationClient($affectationClient) {
        $this->affectationClient = $affectationClient;
    }

    function setAffectationDescriptif($affectationDescriptif) {
        $this->affectationDescriptif = $affectationDescriptif;
    }
    function getAffectationCommentaire() {
        return $this->affectationCommentaire;
    }

    function setAffectationCommentaire($affectationCommentaire) {
        $this->affectationCommentaire = $affectationCommentaire;
    }    
    function getAffectationDossierClos() {
        return $this->affectationDossierClos;
    }

    function setAffectationDossierClos($affectationDossierClos) {
        $this->affectationDossierClos = $affectationDossierClos;
    }
    function getAffectationIntervenant() {
        return $this->affectationIntervenant;
    }

    function setAffectationIntervenant($affectationIntervenant) {
        $this->affectationIntervenant = $affectationIntervenant;
    }
    function getAffectationFontColor() {
        return $this->affectationFontColor;
    }

    function setAffectationFontColor($affectationFontColor) {
        $this->affectationFontColor = $affectationFontColor;
    }


}
