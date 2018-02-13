<?php

/**
 * Classe de gestion des Articles.
 * Manager : Model_articles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Client {

    protected $clientId;
    protected $clientRaisonSociale;
    protected $clientNumTva;
    protected $clientExoneration;
    protected $clientTelephone;
    protected $clientAdresse1;
    protected $clientAdresse2;
    protected $clientCp;
    protected $clientVille;
    protected $clientPays;
    protected $clientAffaires;
    protected $clientFactures;
    protected $clientAvoirs;
    protected $clientContacts;
    protected $clientPrincipal; /* 1 si client principal dans l'affaire chargée */

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

    function hydrateAffaires() {
        $CI = & get_instance();
        $this->clientAffaires = $CI->managerAffaireClients->getAffairesByClientId($this->clientId);
    }

    function hydrateContacts() {
        $CI = & get_instance();
        $this->clientContacts = $CI->managerContacts->liste(array('contactClientId' => $this->clientId));
    }

    function hydrateFactures() {
        $CI = & get_instance();
        $this->clientFactures = $CI->managerFactures->liste(array('factureClientId' => $this->clientId));
    }

    function hydrateAvoirs() {
        $CI = & get_instance();
        $this->clientAvoirs = $CI->managerAvoirs->liste(array('avoirClientId' => $this->clientId));
    }

    function getClientId() {
        return $this->clientId;
    }

    function getClientRaisonSociale() {
        return $this->clientRaisonSociale;
    }

    function getClientNumTva() {
        return $this->clientNumTva;
    }

    function getClientExoneration() {
        return $this->clientExoneration;
    }

    function getClientTelephone() {
        return $this->clientTelephone;
    }

    function getClientAdresse1() {
        return $this->clientAdresse1;
    }

    function getClientAdresse2() {
        return $this->clientAdresse2;
    }

    function getClientCp() {
        return $this->clientCp;
    }

    function getClientVille() {
        return $this->clientVille;
    }

    function getClientPays() {
        return $this->clientPays;
    }

    function getClientAffaires() {
        return $this->clientAffaires;
    }

    function getClientFactures() {
        return $this->clientFactures;
    }

    function getClientAvoirs() {
        return $this->clientAvoirs;
    }

    function getClientContacts() {
        return $this->clientContacts;
    }

    function getClientPrincipal() {
        return $this->clientPrincipal;
    }

    function setClientId($clientId) {
        $this->clientId = $clientId;
    }

    function setClientRaisonSociale($clientRaisonSociale) {
        $this->clientRaisonSociale = $clientRaisonSociale;
    }

    function setClientNumTva($clientNumTva) {
        $this->clientNumTva = $clientNumTva;
    }

    function setClientExoneration($clientExoneration) {
        $this->clientExoneration = $clientExoneration;
    }

    function setClientTelephone($clientTelephone) {
        $this->clientTelephone = $clientTelephone;
    }

    function setClientAdresse1($clientAdresse1) {
        $this->clientAdresse1 = $clientAdresse1;
    }

    function setClientAdresse2($clientAdresse2) {
        $this->clientAdresse2 = $clientAdresse2;
    }

    function setClientCp($clientCp) {
        $this->clientCp = $clientCp;
    }

    function setClientVille($clientVille) {
        $this->clientVille = $clientVille;
    }

    function setClientPays($clientPays) {
        $this->clientPays = $clientPays;
    }

    function setClientAffaires($clientAffaires) {
        $this->clientAffaires = $clientAffaires;
    }

    function setClientFactures($clientFactures) {
        $this->clientFactures = $clientFactures;
    }

    function setClientAvoirs($clientAvoirs) {
        $this->clientAvoirs = $clientAvoirs;
    }

    function setClientContacts($clientContacts) {
        $this->clientContacts = $clientContacts;
    }

    function setClientPrincipal($clientPrincipal) {
        $this->clientPrincipal = $clientPrincipal;
    }

}
