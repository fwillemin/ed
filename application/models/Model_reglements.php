<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_reglements extends MY_model {

    protected $table = 'reglements';

    const classe = 'Reglement';

    /**
     * Ajout d'un objet de la classe Reglement à la BDD
     * @param Reglement $reglement Objet de la classe Reglement
     */
    public function ajouter(Reglement $reglement) {
        $this->db
                ->set('reglementDate', $reglement->getReglementDate())
                ->set('reglementMontant', $reglement->getReglementMontant())
                ->set('reglementToken', $reglement->getReglementToken())
                ->set('reglementAffaireId', $reglement->getReglementAffaireId())
                ->set('reglementClientId', $reglement->getReglementClientId())
                ->set('reglementType', $reglement->getReglementType())
                ->set('reglementFactureId', $reglement->getReglementFactureId())
                ->set('reglementMode', $reglement->getReglementMode())
                ->set('reglementGroupeId', $reglement->getReglementGroupeId())
                ->set('reglementSourceId', $reglement->getReglementSourceId())
                ->set('reglementUtile', $reglement->getReglementUtile())
                ->insert($this->table);
        $reglement->setReglementId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Reglement
     * @param Reglement $reglement Objet de la classe Reglement
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Reglement $reglement) {
        $this->db
                ->set('reglementDate', $reglement->getReglementDate())
                ->set('reglementMontant', $reglement->getReglementMontant())
                ->set('reglementToken', $reglement->getReglementToken())
                ->set('reglementAffaireId', $reglement->getReglementAffaireId())
                ->set('reglementClientId', $reglement->getReglementClientId())
                ->set('reglementType', $reglement->getReglementType())
                ->set('reglementFactureId', $reglement->getReglementFactureId())
                ->set('reglementMode', $reglement->getReglementMode())
                ->set('reglementGroupeId', $reglement->getReglementGroupeId())
                ->set('reglementSourceId', $reglement->getReglementSourceId())
                ->set('reglementUtile', $reglement->getReglementUtile())
                ->where('reglementId', $reglement->getReglementId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe reglement
     *
     * @param Reglement Objet de la classe Reglement
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Reglement $reglement) {
        $this->db->where('reglementId', $reglement->getReglementId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Reglements correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des reglements
     * @param array $tri Critères de tri des reglements
     * @return array Liste d'objets de la classe Reglement
     */
    public function liste($where = array(), $tri = 'reglementDate ASC', $type = 'object') {
        $query = $this->db->select('r.*')
                ->from('reglements r')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Reglement correspondant à l'id
     * @param integer $reglementId ID de l'reglement
     * @return \Reglement|boolean
     */
    public function getReglementById($reglementId, $type = 'object') {
        $query = $this->db->select('r.*')
                ->from('reglements r')
                ->where('r.reglementId', $reglementId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne un array avec d'historique d'un réglement à savoir tous les réglements l'ayant pour source
     * @param integer $reglementSourceId ID du réglement source
     * @return array Liste d'objets de la classe Reglement
     */
    public function historique($reglementSourceId, $type = 'object') {
        $query = $this->db->select('r.*')
                ->from('reglements r')
                ->where(array('reglementSourceId' => $reglementSourceId, 'reglementId <>' => $reglementSourceId))
                ->order_by('reglementDate ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
