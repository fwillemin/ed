<?php

/**
 * Classe de gestion des Maquettes
 * Manager : Model_maquettes
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*
  ALTER TABLE `maquettes` ADD `maquetteFicheAtelierEditee` BOOLEAN NOT NULL AFTER `maquetteCommandeCertifiee`;
 */
class Maquette {

    protected $maquetteId;
    protected $maquetteDateCreation;
    protected $maquetteDateLimite;
    protected $maquetteClientText;
    protected $maquetteClientId;
    protected $maquetteClient;
    protected $maquetteDescription;
    protected $maquetteDifficulte;
    protected $maquetteAvancement;
    protected $maquetteAvancementText;
    protected $maquettePathFiles;

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

        switch ($this->maquetteAvancement):
            case 1:
                $this->maquetteAvancementText = '<span style="color: steelblue;"><i class="fas fa-pause"></i> Attente</span>';
                break;
            case 2:
                $this->maquetteAvancementText = '<span style="color: goldenrod;"><i class="fas fa-play"></i> En cours</span>';
                break;
            case 3:
                $this->maquetteAvancementText = '<span style="color: green;"><i class="fas fa-check"></i> Prêt</span>';
                break;
        endswitch;
    }

    public function hydrateClient() {
        $CI = & get_instance();
        if ($this->maquetteClientId):
            $this->maquetteClient = $CI->managerClients->getClientById($this->maquetteClientId);
        endif;
    }

    public function avancement() {
        $this->maquetteAvancement++;
        if ($this->maquetteAvancement > 3):
            $this->maquetteAvancement = 1;
        endif;
    }

    function getMaquetteId() {
        return $this->maquetteId;
    }

    function getMaquetteDateCreation() {
        return $this->maquetteDateCreation;
    }

    function getMaquetteDateLimite() {
        return $this->maquetteDateLimite;
    }

    function getMaquetteClientText() {
        return $this->maquetteClientText;
    }

    function getMaquetteClientId() {
        return $this->maquetteClientId;
    }

    function getMaquetteClient() {
        return $this->maquetteClient;
    }

    function getMaquetteDescription() {
        return $this->maquetteDescription;
    }

    function getMaquetteDifficulte() {
        return $this->maquetteDifficulte;
    }

    function getMaquetteAvancement() {
        return $this->maquetteAvancement;
    }

    function getMaquetteAvancementText() {
        return $this->maquetteAvancementText;
    }

    function getMaquettePathFiles() {
        return $this->maquettePathFiles;
    }

    function setMaquetteId($maquetteId) {
        $this->maquetteId = $maquetteId;
    }

    function setMaquetteDateCreation($maquetteDateCreation) {
        $this->maquetteDateCreation = $maquetteDateCreation;
    }

    function setMaquetteDateLimite($maquetteDateLimite) {
        $this->maquetteDateLimite = $maquetteDateLimite;
    }

    function setMaquetteClientText($maquetteClientText) {
        $this->maquetteClientText = $maquetteClientText;
    }

    function setMaquetteClientId($maquetteClientId) {
        $this->maquetteClientId = $maquetteClientId;
    }

    function setMaquetteClient($maquetteClient) {
        $this->maquetteClient = $maquetteClient;
    }

    function setMaquetteDescription($maquetteDescription) {
        $this->maquetteDescription = $maquetteDescription;
    }

    function setMaquetteDifficulte($maquetteDifficulte) {
        $this->maquetteDifficulte = $maquetteDifficulte;
    }

    function setMaquetteAvancement($maquetteAvancement) {
        $this->maquetteAvancement = $maquetteAvancement;
    }

    function setMaquetteAvancementText($maquetteAvancementText) {
        $this->maquetteAvancementText = $maquetteAvancementText;
    }

    function setMaquettePathFiles($maquettePathFiles) {
        $this->maquettePathFiles = $maquettePathFiles;
    }

}
