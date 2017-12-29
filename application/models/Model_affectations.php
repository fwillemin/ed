<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_affectations extends MY_model {

    protected $table = 'affectations';

    const classe = 'Affectation';

    /**
     * Ajout d'un objet de la classe Affectation à la BDD
     * @param Affectation $affectation Objet de la classe Affectation
     */
    public function ajouter(Affectation $affectation) {
        $this->db
                ->set('affectationDossierId', $affectation->getAffectationDossierId())
                ->set('affectationType', $affectation->getAffectationType())
                ->set('affectationDate', $affectation->getAffectationDate())
                ->set('affectationEquipeId', $affectation->getAffectationEquipeId())
                ->set('affectationIntervenant', $affectation->getAffectationIntervenant())
                ->set('affectationPosition', $affectation->getAffectationPosition())
                ->set('affectationCommentaire', $affectation->getAffectationCommentaire())
                ->set('affectationEtat', $affectation->getAffectationEtat())
                ->insert($this->table);
        $affectation->setAffectationId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Affectation
     * @param Affectation $affectation Objet de la classe Affectation
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Affectation $affectation) {
        $this->db
                ->set('affectationDossierId', $affectation->getAffectationDossierId())
                ->set('affectationType', $affectation->getAffectationType())
                ->set('affectationDate', $affectation->getAffectationDate())
                ->set('affectationEquipeId', $affectation->getAffectationEquipeId())
                ->set('affectationIntervenant', $affectation->getAffectationIntervenant())
                ->set('affectationPosition', $affectation->getAffectationPosition())
                ->set('affectationCommentaire', $affectation->getAffectationCommentaire())
                ->set('affectationEtat', $affectation->getAffectationEtat())
                ->where('affectationId', $affectation->getAffectationId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe affectation
     *
     * @param Affectation Objet de la classe Affectation
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Affectation $affectation) {
        $this->db->where('affectationId', $affectation->getAffectationId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Affectations correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des affectations
     * @param array $tri Critères de tri des affectations
     * @return array Liste d'objets de la classe Affectation
     */
    public function liste($where = array(), $tri = 'affectationDate, affectationEquipeId, affectationPosition ASC', $type = 'object') {
        $query = $this->db->select('a.*, e.equipeNom AS affectationEquipe, d.dossierClient AS affectationClient, d.dossierDescriptif AS affectationDescriptif, d.dossierClos AS affectationDossierClos')
                ->from('affectations a')
                ->join('equipes e', 'e.equipeId = a.affectationEquipeId')
                ->join('dossiers d', 'd.dossierId = a.affectationDossierId')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Affectation correspondant à l'id
     * @param type $ref
     * @return \Affectation|boolean
     */
    public function getAffectationById($affectationId, $type = 'object') {
        $query = $this->db->select('a.*, e.equipeNom AS affectationEquipe, d.dossierClient AS affectationClient, d.dossierDescriptif AS affectationDescriptif, d.dossierClos AS affectationDossierClos')
                ->from('affectations a')
                ->join('equipes e', 'e.equipeId = a.affectationEquipeId')
                ->join('dossiers d', 'd.dossierId = a.affectationDossierId')
                ->where(array('affectationId' => intval($affectationId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne la position d'une nouvelle affectation pour une équipe et une date donnée
     * @param int $equipeId Equipe concernée
     * @param int $date Date demandée
     * @return int Position libre dans le planning
     */
    public function getNewPosition($equipeId, $date) {

        $query = $this->db->select('*')->from($this->table)
                ->where(array('affectationEquipeId' => $equipeId, 'affectationDate' => $date))
                ->get();
        return $query->num_rows() + 1;
    }

}
