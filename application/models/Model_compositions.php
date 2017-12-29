<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_compositions extends MY_model {

    protected $table = 'compositions';

    const classe = 'Composition';

    /**
     * Ajout d'un objet de la classe Composition à la BDD
     * @param Composition $composition Objet de la classe Composition
     */
    public function ajouter(Composition $composition) {
        $this->db
                ->set('compositionArticleId', $composition->getCompositionArticleId())
                ->set('compositionComposantId', $composition->getCompositionComposantId())
                ->set('compositionOptionId', $composition->getCompositionOptionId())
                ->set('compositionQte', $composition->getCompositionQte())
                ->insert($this->table);
        $composition->setCompositionId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Composition
     * @param Composition $composition Objet de la classe Composition
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Composition $composition) {
        $this->db
                ->set('compositionArticleId', $composition->getCompositionArticleId())
                ->set('compositionComposantId', $composition->getCompositionComposantId())
                ->set('compositionOptionId', $composition->getCompositionOptionId())
                ->set('compositionQte', $composition->getCompositionQte())
                ->where('compositionId', $composition->getCompositionId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe composition
     *
     * @param Composition Objet de la classe Composition
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Composition $composition) {
        $this->db->where('compositionId', $composition->getCompositionId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Compositions correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des compositions
     * @param array $tri Critères de tri des compositions
     * @param str $option Option de chargement des objets Article, Composant et Option
     * 100 => Chargement Article
     * 010 => Chargement Composant
     * 001 => Chargement Option
     * Les 3 combinables
     *
     * @return array Liste d'objets de la classe Composition
     */
    public function liste($where = array(), $tri = 'compositionDesignation ASC', $type = 'object', $option = '000') {
        $query = $this->db->select('c.*')
                ->from('compositions c')
                ->where($where)
                ->order_by($tri)
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($type == 'object'):
                    $compositions[] = new Composition((array) $row, $option);
                else:
                    $compositions[] = $row;
                endif;
            endforeach;
            return $compositions;
        else:
            return FALSE;
        endif;
    }

    /**
     * Retourne un objet de la classe Composition correspondant à l'id
     * @param integer $compositionId ID de la composition recherchée
     * @param str $option Option de chargement des objets Article, Composant et Option
     * 100 => Chargement Article
     * 010 => Chargement Composant
     * 001 => Chargement Option
     * Les 3 combinables
     *
     * @return \Composition|boolean
     */
    public function getCompositionById($compositionId, $type = 'object', $option = '000') {
        $query = $this->db->select('c.*')
                ->from('compositions c')
                ->where(array('c.compositionId' => intval($compositionId)))
                ->get();
        if ($query->num_rows() > 0):
            if ($type == 'object'):
                $composition = new Composition((array) $query->row(), $option);
            else:
                $composition = $query->row();
            endif;
            return $composition;
        else:
            return false;
        endif;
    }

    /**
     * Retourne des objet de la classe Composition correspondant à l'article
     * @param integer $articleId ID de l'article recherché
     *
     * @return \Composition|boolean
     */
    public function getCompositionsByArticleId($articleId, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from('compositions c')
                ->where(array('c.compositionArticleId' => intval($articleId)))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne des objet de la classe Composition correspondant à l'article
     * @param integer $articleId ID de l'article recherché
     *
     * @return \Composition|boolean
     */
    public function getCompositionsByComposantId($composantId, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from('compositions c')
                ->where(array('c.compositionComposantId' => intval($composantId)))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
