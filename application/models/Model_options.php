<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_options extends MY_model {

    protected $table = 'options';

    const classe = 'Option';

    /**
     * Ajout d'un objet de la classe Option à la BDD
     * @param Option $option Objet de la classe Option
     */
    public function ajouter(Option $option) {
        $this->db
                ->set('optionComposantId', $option->getOptionComposantId())
                ->set('optionReference', $option->getOptionReference())
                ->set('optionNom', $option->getOptionNom())
                ->set('optionPrixCatalogue', $option->getOptionPrixCatalogue())
                ->set('optionCoefficient', $option->getOptionCoefficient())
                ->set('optionPrixAchat', $option->getOptionPrixAchat())
                ->set('optionRemise', $option->getOptionRemise())
                ->set('optionHT', $option->getOptionHT())
                ->set('optionTVA', $option->getOptionTVA())
                ->set('optionTTC', $option->getOptionTTC())
                ->set('optionActive', $option->getOptionActive())
                ->insert($this->table);
        $option->setOptionId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Option
     * @param Option $option Objet de la classe Option
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Option $option) {
        $this->db
                ->set('optionComposantId', $option->getOptionComposantId())
                ->set('optionReference', $option->getOptionReference())
                ->set('optionNom', $option->getOptionNom())
                ->set('optionPrixCatalogue', $option->getOptionPrixCatalogue())
                ->set('optionPrixAchat', $option->getOptionPrixAchat())
                ->set('optionCoefficient', $option->getOptionCoefficient())
                ->set('optionRemise', $option->getOptionRemise())
                ->set('optionHT', $option->getOptionHT())
                ->set('optionTVA', $option->getOptionTVA())
                ->set('optionTTC', $option->getOptionTTC())
                ->set('optionActive', $option->getOptionActive())
                ->where('optionId', $option->getOptionId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe option
     *
     * @param Option Objet de la classe Option
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Option $option) {
        $this->db->where('optionId', $option->getOptionId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Options correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des options
     * @param array $tri Critères de tri des options
     * @return array Liste d'objets de la classe Option
     */
    public function getOptionsByComposantId($composantId, $type = 'object') {
        $query = $this->db->select('o.*')
                ->from('options o')
                ->where('o.optionComposantId', $composantId)
                ->order_by('CAST(o.optionNom AS UNSIGNED), o.optionNom ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Option correspondant à l'id
     * @param type $ref
     * @return \Option|boolean
     */
    public function getOptionById($optionId, $type = 'object', $option = '00') {
        $query = $this->db->select('c.*')
                ->from('options c')
                ->where(array('optionId' => intval($optionId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
