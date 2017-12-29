<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_affaireOptions extends MY_model {

    protected $table = 'affaireOptions';

    const classe = 'AffaireOption';

    /**
     * Ajout d'un objet de la classe AffaireOption à la BDD
     * @param AffaireOption $affaireOption Objet de la classe AffaireOption
     */
    public function ajouter(AffaireOption $affaireOption) {
        $this->db
                ->set('affaireOptionAffaireId', $affaireOption->getAffaireOptionAffaireId())
                ->set('affaireOptionArticleId', $affaireOption->getAffaireOptionArticleId())
                ->set('affaireOptionOptionId', $affaireOption->getAffaireOptionOptionId())
                ->set('affaireOptionComposantId', $affaireOption->getAffaireOptionComposantId())
                ->set('affaireOptionQte', $affaireOption->getAffaireOptionQte())
                ->set('affaireOptionPU', $affaireOption->getAffaireOptionPU())
                ->set('affaireOptionOriginel', $affaireOption->getAffaireOptionOriginel())
                ->insert($this->table);
        $affaireOption->setAffaireOptionId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe AffaireOption
     * @param AffaireOption $affaireOption Objet de la classe AffaireOption
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(AffaireOption $affaireOption) {
        $this->db
                ->set('affaireOptionAffaireId', $affaireOption->getAffaireOptionAffaireId())
                ->set('affaireOptionArticleId', $affaireOption->getAffaireOptionArticleId())
                ->set('affaireOptionOptionId', $affaireOption->getAffaireOptionOptionId())
                ->set('affaireOptionComposantId', $affaireOption->getAffaireOptionComposantId())
                ->set('affaireOptionQte', $affaireOption->getAffaireOptionQte())
                ->set('affaireOptionPU', $affaireOption->getAffaireOptionPU())
                ->set('affaireOptionOriginel', $affaireOption->getAffaireOptionOriginel())
                ->where('affaireOptionId', $affaireOption->getAffaireOptionId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe affaireOption
     *
     * @param AffaireOption Objet de la classe AffaireOption
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(AffaireOption $affaireOption) {
        $this->db->where('affaireOptionId', $affaireOption->getAffaireOptionId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des AffaireOptions correspondant aux critères su paralètre $where
     * @param array $where Critères de selection des affaireOption
     * @param string $type Type de retour des données (array, Object)
     * @return array Liste d'objets de la classe AffaireOption
     */
    public function liste($where = array(), $tri = 'affaireOptionAffaireId ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAffaireOptionByAffaireArticleId($affaireArticleId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireOptionArticleId', $affaireArticleId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe AffaireOption correspondant à l'id d'une affaire
     * @param type $affaireId ID de l'affaire
     * @return \AffaireOption|boolean
     */
    public function getAffaireOptionById($affaireOptionId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireOptionId', $affaireOptionId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
