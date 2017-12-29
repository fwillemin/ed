<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_articles extends MY_model {

    protected $table = 'articles';

    const classe = 'Article';

    /**
     * Ajout d'un objet de la classe Article à la BDD
     * @param Article $article Objet de la classe Article
     */
    public function ajouter(Article $article) {
        $this->db
                ->set('articleFamilleId', $article->getArticleFamilleId())
                ->set('articleDesignation', $article->getArticleDesignation())
                ->set('articleDescription', $article->getArticleDescription())
                ->set('articleHT', $article->getArticleHT())
                ->set('articleAchatHT', $article->getArticleAchatHT())
                ->set('articleDelete', $article->getArticleDelete())
                ->insert($this->table);
        $article->setArticleId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Article
     * @param Article $article Objet de la classe Article
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Article $article) {
        $this->db
                ->set('articleFamilleId', $article->getArticleFamilleId())
                ->set('articleDesignation', $article->getArticleDesignation())
                ->set('articleDescription', $article->getArticleDescription())
                ->set('articleHT', $article->getArticleHT())
                ->set('articleAchatHT', $article->getArticleAchatHT())
                ->set('articleDelete', $article->getArticleDelete())
                ->where('articleId', $article->getArticleId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe article
     *
     * @param Article Objet de la classe Article
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Article $article) {
        $this->db->where('articleId', $article->getArticleId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Articles correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des articles
     * @param array $tri Critères de tri des articles
     * @return array Liste d'objets de la classe Article
     */
    public function liste($where = array(), $tri = 'articleDesignation ASC', $type = 'object', $jointure = 'left') {
        $query = $this->db->select('a.*, f.familleNom as articleFamille')
                ->from('articles a')
                ->join('familles f', 'f.familleId = a.articleFamilleId', 'left')
                ->join('compositions c', 'c.compositionArticleId = a.articleId', $jointure)
                ->where($where)
                ->order_by($tri)
                ->group_by('a.articleId')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getArticlesByOptionId($optionId, $tri = 'articleDesignation ASC', $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('articles a')
                ->join('compositions c', 'c.compositionArticleId = a.articleId')
                ->where('c.compositionOptionId', $optionId)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getArticlesByComposantId($composantId, $tri = 'articleDesignation ASC', $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('articles a')
                ->join('compositions c', 'c.compositionArticleId = a.articleId')
                ->where('c.compositionComposantId', $composantId)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Article correspondant à l'id
     * @param type $ref
     * @return \Article|boolean
     */
    public function getArticleById($articleId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('articles a')
                ->where(array('a.articleId' => intval($articleId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
