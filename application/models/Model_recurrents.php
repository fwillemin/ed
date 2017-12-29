<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_recurrents extends MY_model {

    protected $table = 'recurrents';

    const classe = 'Recurrent';

    /**
     * Ajout d'un objet de la classe Recurrent à la BDD
     * @param Recurrent $recurrent Objet de la classe Recurrent
     */
    public function ajouter(Recurrent $recurrent) {
        $this->db
                ->set('recurrentCritere', $recurrent->getRecurrentCritere())
                ->set('recurrentEquipeId', $recurrent->getRecurrentEquipeId())
                ->set('recurrentCommentaire', $recurrent->getRecurrentCommentaire())
                ->insert($this->table);
        $recurrent->setRecurrentId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Recurrent
     * @param Recurrent $recurrent Objet de la classe Recurrent
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Recurrent $recurrent) {
        $this->db
                ->set('recurrentCritere', $recurrent->getRecurrentCritere())
                ->set('recurrentEquipeId', $recurrent->getRecurrentEquipeId())
                ->set('recurrentCommentaire', $recurrent->getRecurrentCommentaire())
                ->where('recurrentId', $recurrent->getRecurrentId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe recurrent
     *
     * @param Recurrent Objet de la classe Recurrent
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Recurrent $recurrent) {
        $this->db->where('recurrentId', $recurrent->getRecurrentId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Recurrents correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des recurrents
     * @param array $tri Critères de tri des recurrents
     * @return array Liste d'objets de la classe Recurrent
     */
    public function liste($where = array(), $tri = 'recurrentId ASC', $type = 'object') {
        $query = $this->db->select('*, equipeNom AS recurrentEquipe')
                ->from('recurrents r')
                ->join('equipes e', 'e.equipeId = r.recurrentEquipeId', 'left')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Recurrent correspondant à l'id
     * @param type $ref
     * @return \Recurrent|boolean
     */
    public function getRecurrentById($recurrentId, $type = 'object') {
        $query = $this->db->select('*, equipeNom AS recurrentEquipe')
                ->from('recurrents r')
                ->join('equipes e', 'e.equipeId = r.recurrentEquipeId', 'left')
                ->where(array('recurrentId' => intval($recurrentId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne les affectations récurrentes pour une journée
     * @param int $jour UnixTime de la journée
     * @return boolean|\Recurrent Array avec les Recurrents pour la journée
     */
    public function getJour($jour, $type = 'object') {
        $CI = & get_instance();
        $query = $this->db->select('*, equipeNom AS recurrentEquipe')
                ->from('recurrents r')
                ->join('equipes e', 'e.equipeId = r.recurrentEquipeId', 'left')
                ->where('recurrentCritere', $CI->lang->line('cal_' . strtolower(date('l', $jour))))
                ->or_where('recurrentCritere', date('d', $jour))
                ->or_where('recurrentCritere', date('d-m', $jour))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getSemaine($premierJour, $dernierJour, $type = 'object') {

        $query = $this->db->select('*, equipeNom AS recurrentEquipe')
                ->from('recurrents r')
                ->join('equipes e', 'e.equipeId = r.recurrentEquipeId', 'left')
                ->where_in('recurrentCritere', array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'))
                ->or_where(' ( recurrentCritere >= ', date('d', $premierJour))
                ->where('recurrentCritere <=', date('d', $dernierJour) . ')')
                ->or_where(' ( recurrentCritere >= ', date('d-m', $premierJour))
                ->where('recurrentCritere <=', date('d-m', $dernierJour) . ')')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
