<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_contacts extends MY_model {

    protected $table = 'contacts';

    const clase = 'Contact';

    /**
     * Ajout d'un objet de la classe Contact à la BDD
     * @param Contact $contact Objet de la classe Contact
     */
    public function ajouter(Contact $contact) {
        $this->db
                ->set('contactClientId', $contact->getContactClientId())
                ->set('contactCivilite', $contact->getContactCivilite())
                ->set('contactNom', $contact->getContactNom())
                ->set('contactPrenom', $contact->getContactPrenom())
                ->set('contactFonction', $contact->getContactFonction())
                ->set('contactTelephone', $contact->getContactTelephone())
                ->set('contactPortable', $contact->getContactPortable())
                ->set('contactEmail', $contact->getContactEmail())
                ->insert($this->table);
        $contact->setContactId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Contact
     * @param Contact $contact Objet de la classe Contact
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Contact $contact) {
        $this->db
                ->set('contactCivilite', $contact->getContactCivilite())
                ->set('contactNom', $contact->getContactNom())
                ->set('contactPrenom', $contact->getContactPrenom())
                ->set('contactFonction', $contact->getContactFonction())
                ->set('contactTelephone', $contact->getContactTelephone())
                ->set('contactPortable', $contact->getContactPortable())
                ->set('contactEmail', $contact->getContactEmail())
                ->where('contactId', $contact->getContactId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe contact
     *
     * @param Contact Objet de la classe Contact
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Contact $contact) {
        $this->db->where('contactId', $contact->getContactId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Contacts correspondant aux critères su paralètre $where
     * @param array $where Critères de selection des contacts
     * @param string $type Type de retour des données (array, Object)
     * @return array Liste d'objets de la classe Contact
     */
    public function liste($where = array(), $tri = 'c.contactNom ASC', $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Contact correspondant à l'id
     * @param type $ref
     * @return \Contact|boolean
     */
    public function getContactById($contactId, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->where(array('contactId' => intval($contactId)))
                ->group_by('c.contactId')
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
