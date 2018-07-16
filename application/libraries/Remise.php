<?php

/**
 * Classe de gestion des Remises.
 * Manager : Model_remises
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Remise {

    protected $remiseClientId;
    protected $remiseClient;
    protected $remiseFamilleId;
    protected $remiseFamille;
    protected $remiseTaux;

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
    }

    public function hydrateClient() {
        $CI = & get_instance();
        $this->remiseClient = $CI->managerClients->getClientById($this->remiseClientId);
    }

    public function hydrateFamille() {
        $CI = & get_instance();
        $this->remiseFamille = $CI->managerFamilles->getFamilleById($this->remiseFamilleId);
    }

    function getRemiseClientId() {
        return $this->remiseClientId;
    }

    function getRemiseFamilleId() {
        return $this->remiseFamilleId;
    }

    function getRemiseTaux() {
        return $this->remiseTaux;
    }

    function setRemiseClientId($remiseClientId) {
        $this->remiseClientId = $remiseClientId;
    }

    function setRemiseFamilleId($remiseFamilleId) {
        $this->remiseFamilleId = $remiseFamilleId;
    }

    function setRemiseTaux($remiseTaux) {
        $this->remiseTaux = $remiseTaux;
    }

    function getRemiseClient() {
        return $this->remiseClient;
    }

    function getRemiseFamille() {
        return $this->remiseFamille;
    }

    function setRemiseClient($remiseClient) {
        $this->remiseClient = $remiseClient;
    }

    function setRemiseFamille($remiseFamille) {
        $this->remiseFamille = $remiseFamille;
    }

}
