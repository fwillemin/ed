<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_factureLignes extends MY_model {

    protected $table = 'facturelignes';

    const classe = 'FactureLigne';

    /**
     * Ajout d'un objet de la classe FactureLigne à la BDD
     * @param FactureLigne $factureLigne Objet de la classe FactureLigne
     */
    public function ajouter(FactureLigne $factureLigne) {
        $this->db
                ->set('factureLigneFactureId', $factureLigne->getFactureLigneFactureId())
                ->set('factureLigneAffaireId', $factureLigne->getFactureLigneAffaireId())
                ->set('factureLigneAffaireArticleId', $factureLigne->getFactureLigneAffaireArticleId())
                ->set('factureLigneDesignation', $factureLigne->getFactureLigneDesignation())
                ->set('factureLigneDescription', $factureLigne->getFactureLigneDescription())
                ->set('factureLigneQte', $factureLigne->getFactureLigneQte())
                ->set('factureLigneTarif', $factureLigne->getFactureLigneTarif())
                ->set('factureLigneRemise', $factureLigne->getFactureLigneRemise())
                ->set('factureLigneTotalHT', $factureLigne->getFactureLigneTotalHT())
                ->set('factureLigneTotalTVA', $factureLigne->getFactureLigneTotalTVA())
                ->set('factureLigneTotalTTC', $factureLigne->getFactureLigneTotalTTC())
                ->set('factureLigneQuota', $factureLigne->getFactureLigneQuota())
                ->insert($this->table);
        $factureLigne->setFactureLigneId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe FactureLigne
     * @param FactureLigne $factureLigne Objet de la classe FactureLigne
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(FactureLigne $factureLigne) {
        $this->db
                ->set('factureLigneFactureId', $factureLigne->getFactureLigneFactureId())
                ->set('factureLigneAffaireId', $factureLigne->getFactureLigneAffaireId())
                ->set('factureLigneAffaireArticleId', $factureLigne->getFactureLigneAffaireArticleId())
                ->set('factureLigneDesignation', $factureLigne->getFactureLigneDesignation())
                ->set('factureLigneDescription', $factureLigne->getFactureLigneDescription())
                ->set('factureLigneQte', $factureLigne->getFactureLigneQte())
                ->set('factureLigneTarif', $factureLigne->getFactureLigneTarif())
                ->set('factureLigneRemise', $factureLigne->getFactureLigneRemise())
                ->set('factureLigneTotalHT', $factureLigne->getFactureLigneTotalHT())
                ->set('factureLigneTotalTVA', $factureLigne->getFactureLigneTotalTVA())
                ->set('factureLigneTotalTTC', $factureLigne->getFactureLigneTotalTTC())
                ->set('factureLigneQuota', $factureLigne->getFactureLigneQuota())
                ->where('factureLigneId', $factureLigne->getFactureLigneId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe factureLigne
     *
     * @param FactureLigne Objet de la classe FactureLigne
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(FactureLigne $factureLigne) {
        $this->db->where('factureLigneId', $factureLigne->getFactureLigneId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des FactureLignes correspondant aux critères su paralètre $where
     * @param array $where Critères de selection des factureLigne
     * @param string $type Type de retour des données (array, Object)
     * @return array Liste d'objets de la classe FactureLigne
     */
    public function liste($where = array(), $tri = 'factureLigneAffaireId ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe FactureLigne correspondant à l'id d'une affaire
     * @param type $affaireId ID de l'affaire
     * @return \FactureLigne|boolean
     */
    public function getFactureLigneByFactureId($factureId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('factureLigneFactureId', $factureId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
