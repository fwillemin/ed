<?php

/**
 * Classe de gestion des Recurrents
 * Manager : Model_recurrents
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Recurrent {

    protected $recurrentId;
    protected $recurrentCritere;
    protected $recurrentEquipeId;
    protected $recurrentEquipe;
    protected $recurrentCommentaire;

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
    function getRecurrentId() {
        return $this->recurrentId;
    }

    function getRecurrentCritere() {
        return $this->recurrentCritere;
    }

    function getRecurrentEquipeId() {
        return $this->recurrentEquipeId;
    }

    function getRecurrentCommentaire() {
        return $this->recurrentCommentaire;
    }

    function setRecurrentId($recurrentId) {
        $this->recurrentId = $recurrentId;
    }

    function setRecurrentCritere($recurrentCritere) {
        $this->recurrentCritere = $recurrentCritere;
    }

    function setRecurrentEquipeId($recurrentEquipeId) {
        $this->recurrentEquipeId = $recurrentEquipeId;
    }

    function setRecurrentCommentaire($recurrentCommentaire) {
        $this->recurrentCommentaire = $recurrentCommentaire;
    }
    function getRecurrentEquipe() {
        return $this->recurrentEquipe;
    }

    function setRecurrentEquipe($recurrentEquipe) {
        $this->recurrentEquipe = $recurrentEquipe;
    }



}
