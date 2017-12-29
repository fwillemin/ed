<?php

/**
 * Classe de gestion des Contacts.
 * Manager : Model_contacts
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Contact {

    protected $contactId;
    protected $contactClientId;
    protected $contactCivilite;
    protected $contactNom;
    protected $contactPrenom;
    protected $contactFonction;
    protected $contactTelephone;
    protected $contactPortable;
    protected $contactEmail;

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

    function getContactId() {
        return $this->contactId;
    }

    function getContactClientId() {
        return $this->contactClientId;
    }

    function getContactCivilite() {
        return $this->contactCivilite;
    }

    function getContactNom() {
        return $this->contactNom;
    }

    function getContactPrenom() {
        return $this->contactPrenom;
    }

    function getContactFonction() {
        return $this->contactFonction;
    }

    function getContactTelephone() {
        return $this->contactTelephone;
    }

    function getContactPortable() {
        return $this->contactPortable;
    }

    function getContactEmail() {
        return $this->contactEmail;
    }

    function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    function setContactClientId($contactClientId) {
        $this->contactClientId = $contactClientId;
    }

    function setContactCivilite($contactCivilite) {
        $this->contactCivilite = $contactCivilite;
    }

    function setContactNom($contactNom) {
        $this->contactNom = $contactNom;
    }

    function setContactPrenom($contactPrenom) {
        $this->contactPrenom = $contactPrenom;
    }

    function setContactFonction($contactFonction) {
        $this->contactFonction = $contactFonction;
    }

    function setContactTelephone($contactTelephone) {
        $this->contactTelephone = $contactTelephone;
    }

    function setContactPortable($contactPortable) {
        $this->contactPortable = $contactPortable;
    }

    function setContactEmail($contactEmail) {
        $this->contactEmail = $contactEmail;
    }

}
