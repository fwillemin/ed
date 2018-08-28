<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_maquettes extends MY_model {

    protected $table = 'maquettes';

    const classe = 'Maquette';

    /**
     * Ajout d'un objet de la classe Maquette à la BDD
     * @param Maquette $maquette Objet de la classe Maquette
     */
    public function ajouter(Maquette $maquette) {
        $this->db
                ->set('maquetteDateCreation', $maquette->getMaquetteDateCreation())
                ->set('maquetteDateLimite', $maquette->getMaquetteDateLimite())
                ->set('maquetteClientId', $maquette->getMaquetteClientId())
                ->set('maquetteClientText', $maquette->getMaquetteClientText())
                ->set('maquetteDescription', $maquette->getMaquetteDescription())
                ->set('maquetteDifficulte', $maquette->getMaquetteDifficulte())
                ->set('maquetteAvancement', $maquette->getMaquetteAvancement())
                ->set('maquetteAffaireId', $maquette->getMaquetteAffaireId())
                ->set('maquettePathFiles', $maquette->getMaquettePathFiles())
                ->insert($this->table);
        $maquette->setMaquetteId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Maquette
     * @param Maquette $maquette Objet de la classe Maquette
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Maquette $maquette) {
        $this->db
                ->set('maquetteDateCreation', $maquette->getMaquetteDateCreation())
                ->set('maquetteDateLimite', $maquette->getMaquetteDateLimite())
                ->set('maquetteClientId', $maquette->getMaquetteClientId())
                ->set('maquetteClientText', $maquette->getMaquetteClientText())
                ->set('maquetteDescription', $maquette->getMaquetteDescription())
                ->set('maquetteDifficulte', $maquette->getMaquetteDifficulte())
                ->set('maquetteAvancement', $maquette->getMaquetteAvancement())
                ->set('maquetteAffaireId', $maquette->getMaquetteAffaireId())
                ->set('maquettePathFiles', $maquette->getMaquettePathFiles())
                ->where('maquetteId', $maquette->getMaquetteId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe maquette
     *
     * @param Maquette Objet de la classe Maquette
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Maquette $maquette) {
        $this->db->where('maquetteId', $maquette->getMaquetteId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Maquettes correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des maquettes
     * @param array $tri Critères de tri des maquettes
     * @return array Liste d'objets de la classe Maquette
     */
    public function liste($where = array(), $tri = 'maquetteDate DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function listeAll($where = array(), $tri = 'maquetteDateLimite ASC', $type = 'array') {
        $query = $this->db->select('m.*, c.clientRaisonSociale AS clientRS')
                ->from('maquettes m')
                ->join('clients c', 'c.clientId = m.maquetteClientId', 'left')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Maquette correspondant à l'id
     * @param integer $maquetteId ID de l'maquette
     * @return \Maquette|boolean
     */
    public function getMaquetteById($maquetteId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('maquetteId', $maquetteId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
