<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_familles extends MY_model {

    protected $table = 'familles';

    const classe = 'Famille';

    /**
     * Ajout d'un objet de la classe Famille à la BDD
     * @param Famille $famille Objet de la classe Famille
     */
    public function ajouter(Famille $famille) {
        $this->db
                ->set('familleNom', $famille->getFamilleNom())
                ->insert($this->table);
        $famille->setFamilleId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Famille
     * @param Famille $famille Objet de la classe Famille
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Famille $famille) {
        $this->db
                ->set('familleNom', $famille->getFamilleNom())
                ->where('familleId', $famille->getFamilleId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe famille
     *
     * @param Famille Objet de la classe Famille
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Famille $famille) {
        $this->db->where('familleId', $famille->getFamilleId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Familles correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des familles
     * @param array $tri Critères de tri des familles
     * @return array Liste d'objets de la classe Famille
     */
    public function liste($where = array(), $tri = 'f.familleNom ASC', $type = 'object') {
        $query = $this->db->select('f.*')
                ->from('familles f')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Famille correspondant à l'id
     * @param type $ref
     * @return \Famille|boolean
     */
    public function getFamilleById($familleId, $type = 'object') {
        $query = $this->db->select('*')->from($this->table)
                ->where(array('familleId' => intval($familleId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
