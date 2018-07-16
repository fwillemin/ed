<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_remises extends MY_model {

    protected $table = 'remises';

    const classe = 'Remise';

    /**
     * Ajout d'un objet de la classe Remise à la BDD
     * @param Remise $remise Objet de la classe Remise
     */
    public function ajouter(Remise $remise) {
        $this->db
                ->set('remiseClientId', $remise->getRemiseClientId())
                ->set('remiseFamilleId', $remise->getRemiseFamilleId())
                ->set('remiseTaux', $remise->getRemiseTaux())
                ->insert($this->table);
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Remise
     * @param Remise $remise Objet de la classe Remise
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Remise $remise) {
        $this->db
                ->set('remiseTaux', $remise->getRemiseTaux())
                ->where(array('remiseClientId' => $remise->getRemiseClientId(), 'remiseFamilleId' => $remise->getRemiseFamilleId()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe remise
     *
     * @param Remise Objet de la classe Remise
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Remise $remise) {
        $this->db
                ->where(array('remiseClientId' => $remise->getRemiseClientId(), 'remiseFamilleId' => $remise->getRemiseFamilleId()))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Remises correspondant aux critères su paralètre $where
     * @param array $where Critères de selection des remises
     * @param string $type Type de retour des données (array, Object)
     * @return array Liste d'objets de la classe Remise
     */
    public function liste($where = array(), $tri = 'remiseClientId ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Remise correspondant à l'id
     * @param type $ref
     * @return \Remise|boolean
     */
    public function getRemisesByClientId($clientId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('remiseClientId' => $clientId))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getRemisesByFamilleId($familleId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('remiseFamilleId' => $familleId))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getRemise($clientId, $familleId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('remiseClientId' => $clientId, 'remiseFamilleId' => $familleId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
