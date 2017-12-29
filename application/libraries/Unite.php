<?php

/**
 * Classe de gestion des Unites
 * Manager : Model_unites
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Unite {

    protected $uniteId;
    protected $uniteNom;
    protected $uniteSymbole;

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

    function getUniteId() {
        return $this->uniteId;
    }

    function getUniteNom() {
        return $this->uniteNom;
    }

    function getUniteSymbole() {
        return $this->uniteSymbole;
    }

    function setUniteId($uniteId) {
        $this->uniteId = $uniteId;
    }

    function setUniteNom($uniteNom) {
        $this->uniteNom = $uniteNom;
    }

    function setUniteSymbole($uniteSymbole) {
        $this->uniteSymbole = $uniteSymbole;
    }
}
