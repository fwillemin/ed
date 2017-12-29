<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_affaireArticles extends MY_model {

    protected $table = 'affaireArticles';

    const classe = 'AffaireArticle';

    /**
     * Ajout d'un objet de la classe AffaireArticle à la BDD
     * @param AffaireArticle $affaireArticle Objet de la classe AffaireArticle
     */
    public function ajouter(AffaireArticle $affaireArticle) {
        $this->db
                ->set('affaireArticleArticleId', $affaireArticle->getAffaireArticleArticleId())
                ->set('affaireArticleAffaireId', $affaireArticle->getAffaireArticleAffaireId())
                ->set('affaireArticleDesignation', $affaireArticle->getAffaireArticleDesignation())
                ->set('affaireArticleDescription', $affaireArticle->getAffaireArticleDescription())
                ->set('affaireArticleQte', $affaireArticle->getAffaireArticleQte())
                ->set('affaireArticleTarif', $affaireArticle->getAffaireArticleTarif())
                ->set('affaireArticleRemise', $affaireArticle->getAffaireArticleRemise())
                ->set('affaireArticlePU', $affaireArticle->getAffaireArticlePU())
                ->set('affaireArticleTotalHT', $affaireArticle->getAffaireArticleTotalHT())
                ->set('affaireArticlePrixForce', $affaireArticle->getAffaireArticlePrixForce())
                ->insert($this->table);
        $affaireArticle->setAffaireArticleId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe AffaireArticle
     * @param AffaireArticle $affaireArticle Objet de la classe AffaireArticle
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(AffaireArticle $affaireArticle) {
        $this->db
                ->set('affaireArticleArticleId', $affaireArticle->getAffaireArticleArticleId())
                ->set('affaireArticleAffaireId', $affaireArticle->getAffaireArticleAffaireId())
                ->set('affaireArticleDesignation', $affaireArticle->getAffaireArticleDesignation())
                ->set('affaireArticleDescription', $affaireArticle->getAffaireArticleDescription())
                ->set('affaireArticleQte', $affaireArticle->getAffaireArticleQte())
                ->set('affaireArticleTarif', $affaireArticle->getAffaireArticleTarif())
                ->set('affaireArticleRemise', $affaireArticle->getAffaireArticleRemise())
                ->set('affaireArticlePU', $affaireArticle->getAffaireArticlePU())
                ->set('affaireArticleTotalHT', $affaireArticle->getAffaireArticleTotalHT())
                ->set('affaireArticlePrixForce', $affaireArticle->getAffaireArticlePrixForce())
                ->where('affaireArticleId', $affaireArticle->getAffaireArticleId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe affaireArticle
     *
     * @param AffaireArticle Objet de la classe AffaireArticle
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(AffaireArticle $affaireArticle) {
        $this->db->where('affaireArticleId', $affaireArticle->getAffaireArticleId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des AffaireArticles correspondant aux critères su paralètre $where
     * @param array $where Critères de selection des affaireArticle
     * @param string $type Type de retour des données (array, Object)
     * @return array Liste d'objets de la classe AffaireArticle
     */
    public function liste($where = array(), $tri = 'affaireArticleAffaireId ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe AffaireArticle correspondant à l'id d'une affaire
     * @param type $affaireId ID de l'affaire
     * @return \AffaireArticle|boolean
     */
    public function getAffaireArticlesByAffaireId($affaireId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireArticleAffaireId', $affaireId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe AffaireArticle correspondant à un ID
     * @param type $affaireArticleId ID de l'article
     * @return \AffaireArticle|boolean
     */
    public function getAffaireArticleById($affaireArticleId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireArticleId', $affaireArticleId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
