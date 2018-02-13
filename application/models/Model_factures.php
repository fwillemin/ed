<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_factures extends MY_model {

    protected $table = 'factures';

    const classe = 'Facture';

    /**
     * Ajout d'un objet de la classe Facture à la BDD
     * @param Facture $facture Objet de la classe Facture
     */
    public function ajouter(Facture $facture) {
        $this->db
                ->set('factureClientId', $facture->getFactureClientId())
                ->set('factureAffaireId', $facture->getFactureAffaireId())
                ->set('factureObjet', $facture->getFactureObjet())
                ->set('factureTotalHT', $facture->getFactureTotalHT())
                ->set('factureTotalTVA', $facture->getFactureTotalTVA())
                ->set('factureTotalTTC', $facture->getFactureTotalTTC())
                ->set('factureTauxTVA', $facture->getFactureTauxTVA())
                ->set('factureModeReglement', $facture->getFactureModeReglement())
                ->insert($this->table);
        $facture->setFactureId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Facture
     * @param Facture $facture Objet de la classe Facture
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Facture $facture) {
        $this->db
                ->set('factureDate', $facture->getFactureDate())
                ->set('factureObjet', $facture->getFactureObjet())
                ->set('factureTotalHT', $facture->getFactureTotalHT())
                ->set('factureTotalTVA', $facture->getFactureTotalTVA())
                ->set('factureTotalTTC', $facture->getFactureTotalTTC())
                ->set('factureSolde', $facture->getFactureSolde())
                ->set('factureModeReglement', $facture->getFactureModeReglement())
                ->where('factureId', $facture->getFactureId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe facture
     *
     * @param Facture Objet de la classe Facture
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Facture $facture) {
        $this->db->where('factureId', $facture->getFactureId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Factures correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des factures
     * @param array $tri Critères de tri des factures
     * @return array Liste d'objets de la classe Facture
     */
    public function liste($where = array(), $tri = 'factureDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function listeAll($where = array(), $tri = 'factureDate ASC', $type = 'array') {
        $query = $this->db->select('f.*, c.clientRaisonSociale AS factureClient')
                ->from('factures f')
                ->join('clients c', 'c.clientId = f.factureClientId')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Facture correspondant à l'id
     * @param integer $factureId ID de l'facture
     * @return \Facture|boolean
     */
    public function getFactureById($factureId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('factures a')
                ->where('a.factureId', $factureId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne la derniere numérotation d'un devis
     * @return int
     */
    public function getLastDevis($type = 'array') {
        $query = $this->db->select('a.*')
                ->from('factures a')
                ->limit(1, 0)
                ->order_by('factureDevisId DESC')
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne la derniere numérotation d'une commande
     * @return int
     */
    public function getLastCommande($type = 'array') {
        $query = $this->db->select('a.*')
                ->from('factures a')
                ->limit(1, 0)
                ->order_by('factureCommandeId DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getSommeFacturesByAffaireId($affaireId) {
        $query = $this->db->select('SUM(factureTotalHT) AS totalEnFacture')
                ->from('factures')
                ->where('factureAffaireId', $affaireId)
                ->get()
                ->result();
        return $query[0]->totalEnFacture;
    }

}
