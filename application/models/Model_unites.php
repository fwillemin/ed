<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_unites extends MY_model {

    protected $table = 'unites';

    const classe = 'Unite';

    /**
     * Retourne un array avec des Unites correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des unites
     * @param array $tri Critères de tri des unites
     * @return array Liste d'objets de la classe Unite
     */
    public function liste($where = array(), $tri = 'uniteNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Unite correspondant à l'id
     * @param type $ref
     * @return \Unite|boolean
     */
    public function getUniteById($uniteId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('uniteId' => intval($uniteId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
