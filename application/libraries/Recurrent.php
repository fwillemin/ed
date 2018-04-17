<?php

/**
 * Classe de gestion des Recurrents
 * Manager : Model_recurrents
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 *
  ALTER TABLE recurrents DROP FOREIGN KEY recurrents_ibfk_1
  ALTER TABLE `recurrents` CHANGE `recurrentEquipeId` `recurrentType` INT(11) NOT NULL;
 */
class Recurrent {

    protected $recurrentId;
    protected $recurrentCritere;
    protected $recurrentType; /* Type de poste auquel est associé le recurrent (pose, atelier, depannage, ... ) */
    protected $recurrentTypeNom;
    protected $recurrentCommentaire;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;
        switch ($this->recurrentType):
            case 1:
                $this->recurrentTypeNom = 'Fabrication';
                break;
            case 2:
                $this->recurrentTypeNom = 'Pose';
                break;
            case 3:
                $this->recurrentTypeNom = 'PAO';
                break;
            case 4:
                $this->recurrentTypeNom = 'Depannage';
                break;
        endswitch;
    }

    function getRecurrentId() {
        return $this->recurrentId;
    }

    function getRecurrentCritere() {
        return $this->recurrentCritere;
    }

    function getRecurrentType() {
        return $this->recurrentType;
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

    function setRecurrentType($recurrentType) {
        $this->recurrentType = $recurrentType;
    }

    function setRecurrentCommentaire($recurrentCommentaire) {
        $this->recurrentCommentaire = $recurrentCommentaire;
    }

    function getRecurrentTypeNom() {
        return $this->recurrentTypeNom;
    }

    function setRecurrentTypeNom($recurrentTypeNom) {
        $this->recurrentTypeNom = $recurrentTypeNom;
    }

}
