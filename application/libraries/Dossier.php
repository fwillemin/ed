<?php

/**
 * Classe de gestion des Dossiers
 * Manager : Model_dossiers
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Dossier {

    protected $dossierId;
    protected $dossierClient;
    protected $dossierDescriptif;
    protected $dossierDateSortie;
    protected $dossierSortieEtat; /* 1 = Attente, 2 = Fait */
    protected $dossierPao;   
    protected $dossierFab;   
    protected $dossierPose;    
    protected $dossierClos;
    
    protected $dossierAffectations;

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
        $this->setDossierAffectations( $CI->managerAffectations->liste( array('affectationDossierId' => $this->getDossierId())) );
    }
    
    function nextStepSortie() {
        switch( $this->getDossierSortieEtat() ):
            case 1:
                $this->setDossierSortieEtat(2);
                break;
            case 2:
                $this->setDossierSortieEtat(1);
                break;            
        endswitch;
    }    
    function cloture() {
        $this->setDossierClos(1);        
    }
    function ouverture() {
        $this->setDossierClos(0);        
    }    
    function getDossierId() {
        return $this->dossierId;
    }

    function getDossierClient() {
        return $this->dossierClient;
    }

    function getDossierDescriptif() {
        return $this->dossierDescriptif;
    }

    function getDossierDateSortie() {
        return $this->dossierDateSortie;
    }

    function getDossierFab() {
        return $this->dossierFab;
    }

    function getDossierPose() {
        return $this->dossierPose;
    }

    function getDossierClos() {
        return $this->dossierClos;
    }

    function setDossierId($dossierId) {
        $this->dossierId = $dossierId;
    }

    function setDossierClient($dossierClient) {
        $this->dossierClient = $dossierClient;
    }

    function setDossierDescriptif($dossierDescriptif) {
        $this->dossierDescriptif = $dossierDescriptif;
    }

    function setDossierDateSortie($dossierDateSortie) {
        $this->dossierDateSortie = $dossierDateSortie;
    }

    function setDossierFab($dossierFab) {
        $this->dossierFab = $dossierFab;
    }

    function setDossierPose($dossierPose) {
        $this->dossierPose = $dossierPose;
    }

    function setDossierClos($dossierClos) {
        $this->dossierClos = $dossierClos;
    }    
    function getDossierAffectations() {
        return $this->dossierAffectation;
    }

    function setDossierAffectations($dossierAffectation) {
        $this->dossierAffectation = $dossierAffectation;
    }
    function getDossierSortieEtat() {
        return $this->dossierSortieEtat;
    }

    function setDossierSortieEtat($dossierSortieEtat) {
        $this->dossierSortieEtat = $dossierSortieEtat;        
    }
    function getDossierPao() {
        return $this->dossierPao;
    }

    function setDossierPao($dossierPao) {
        $this->dossierPao = $dossierPao;
    }


}
