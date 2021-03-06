<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_equipes extends MY_model {

    protected $table = 'equipes';

    const classe = 'Equipe';

    /**
     * Ajout d'un objet de la classe Equipe à la BDD
     * @param Equipe $equipe Objet de la classe Equipe
     */
    public function ajouter(Equipe $equipe) {
        $this->db
                ->set('equipeNom', $equipe->getEquipeNom())
                ->insert($this->table);
        $equipe->setEquipeId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Equipe
     * @param Equipe $equipe Objet de la classe Equipe
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Equipe $equipe) {
        $this->db
                ->set('equipeNom', $equipe->getEquipeNom())
                ->where('equipeId', $equipe->getEquipeId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe equipe
     *
     * @param Equipe Objet de la classe Equipe
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Equipe $equipe) {
        $this->db->where('equipeId', $equipe->getEquipeId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Equipes correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des equipes
     * @param array $tri Critères de tri des equipes
     * @return array Liste d'objets de la classe Equipe
     */
    public function liste($where = array(), $tri = 'equipeNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Equipe correspondant à l'id
     * @param type $ref
     * @return \Equipe|boolean
     */
    public function getEquipeById($equipeId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('equipeId' => intval($equipeId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
