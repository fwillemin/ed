<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_avoirlignes extends MY_model {

    protected $table = 'avoirlignes';

    const classe = 'AvoirLigne';

    /**
     * Ajout d'un objet de la classe Avoirligne à la BDD
     * @param AvoirLigne $ligne Objet de la classe Ligne
     */
    public function ajouter(AvoirLigne $ligne) {
        $this->db
                ->set('avoirLigneAvoirId', $ligne->getAvoirLigneAvoirId())
                ->set('avoirLigneFactureLigneId', $ligne->getAvoirLigneFactureLigneId())
                ->set('avoirLigneDesignation', $ligne->getAvoirLigneDesignation())
                ->set('avoirLigneDescription', $ligne->getAvoirLigneDescription())
                ->set('avoirLigneQte', $ligne->getAvoirLigneQte())
                ->set('avoirLignePrixUnitaire', $ligne->getAvoirLignePrixUnitaire())
                ->set('avoirLigneTauxTVA', $ligne->getAvoirLigneTauxTVA())
                ->set('avoirLigneTotalHT', $ligne->getAvoirLigneTotalHT())
                ->insert($this->table);
        $ligne->setAvoirLigneId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Ligne
     * @param Ligne $ligne Objet de la classe Ligne
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(AvoirLigne $ligne) {
        $this->db
                ->set('avoirLigneAvoirId', $ligne->getAvoirLigneAvoirId())
                ->set('avoirLigneFactureLigneId', $ligne->getAvoirLigneFactureLigneId())
                ->set('avoirLigneDesignation', $ligne->getAvoirLigneDesignation())
                ->set('avoirLigneDescription', $ligne->getAvoirLigneDescription())
                ->set('avoirLigneQte', $ligne->getAvoirLigneQte())
                ->set('avoirLignePrixUnitaire', $ligne->getAvoirLignePrixUnitaire())
                ->set('avoirLigneTauxTVA', $ligne->getAvoirLigneTauxTVA())
                ->set('avoirLigneTotalHT', $ligne->getAvoirLigneTotalHT())
                ->where('avoirLigneId', $ligne->getAvoirLigneId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('avoirs f', 'f.avoirId = j.avoirLigneAvoirId', 'left')
                ->where($where)
                ->order_by('l.avoirLigneDesignation', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAvoirLignesByAvoirId(Avoir $avoir, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('avoirs f', 'f.avoirId = l.avoirLigneAvoirId', 'left')
                ->where('l.avoirLigneAvoirId', $avoir->getAvoirId())
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAvoirLigneById($ligneId, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('avoirs f', 'f.avoirId = l.avoirLigneAvoirId', 'left')
                ->where('l.avoirLigneId', intval($ligneId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
