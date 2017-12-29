<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_composants extends MY_model {

    protected $table = 'composants';

    const classe = 'Composant';

    /**
     * Ajout d'un objet de la classe Composant à la BDD
     * @param Composant $composant Objet de la classe Composant
     */
    public function ajouter(Composant $composant) {
        $this->db
                ->set('composantFamilleId', $composant->getComposantFamilleId())
                ->set('composantDesignation', $composant->getComposantDesignation())
                ->set('composantUniteId', $composant->getComposantUniteId())
                ->set('composantTauxTVA', $composant->getComposantTauxTVA())
                ->set('composantDelete', $composant->getComposantDelete())
                ->insert($this->table);
        $composant->setComposantId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Composant
     * @param Composant $composant Objet de la classe Composant
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Composant $composant) {
        $this->db
                ->set('composantDesignation', $composant->getComposantDesignation())
                ->set('composantFamilleId', $composant->getComposantFamilleId())
                ->set('composantUniteId', $composant->getComposantUniteId())
                ->set('composantTauxTVA', $composant->getComposantTauxTVA())
                ->set('composantDelete', $composant->getComposantDelete())
                ->where('composantId', $composant->getComposantId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe composant
     *
     * @param Composant Objet de la classe Composant
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Composant $composant) {
        $this->db->where('composantId', $composant->getComposantId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Composants correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des composants
     * @param array $tri Critères de tri des composants
     * @return array Liste d'objets de la classe Composant
     */
    public function liste($where = array(), $tri = 'composantDesignation ASC', $type = 'object') {
        $query = $this->db->select('c.*, u.uniteNom AS composantUnite, f.familleNom AS composantFamille')
                ->from('composants c')
                ->join('unites u', 'u.uniteId = c.composantUniteId')
                ->join('familles f', 'f.familleId = c.composantFamilleId', 'left')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Composant correspondant à l'id
     * @param type $ref
     * @return \Composant|boolean
     */
    public function getComposantById($composantId, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from('composants c')
                ->where(array('composantId' => intval($composantId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
