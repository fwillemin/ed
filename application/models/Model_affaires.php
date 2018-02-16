<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_affaires extends MY_model {

    protected $table = 'affaires';

    const classe = 'Affaire';

    /**
     * Ajout d'un objet de la classe Affaire à la BDD
     * @param Affaire $affaire Objet de la classe Affaire
     */
    public function ajouter(Affaire $affaire) {
        $this->db
                ->set('affaireDate', $affaire->getAffaireDate())
                ->set('affaireType', $affaire->getAffaireType())
                ->set('affaireObjet', $affaire->getAffaireObjet())
                ->set('affaireTauxTVA', $affaire->getAffaireTauxTVA())
                ->set('affaireTotalHT', $affaire->getAffaireTotalHT())
                ->set('affaireTotalTVA', $affaire->getAffaireTotalTVA())
                ->set('affaireTotalTTC', $affaire->getAffaireTotalTTC())
                ->set('affairePAO', $affaire->getAffairePAO())
                ->set('affaireFabrication', $affaire->getAffaireFabrication())
                ->set('affairePose', $affaire->getAffairePose())
                ->set('affaireCloture', 0)
                ->insert($this->table);
        $affaire->setAffaireId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Affaire
     * @param Affaire $affaire Objet de la classe Affaire
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Affaire $affaire) {
        $this->db
                ->set('affaireDate', $affaire->getAffaireDate())
                ->set('affaireType', $affaire->getAffaireType())
                ->set('affaireObjet', $affaire->getAffaireObjet())
                ->set('affaireTauxTVA', $affaire->getAffaireTauxTVA())
                ->set('affaireTotalHT', $affaire->getAffaireTotalHT())
                ->set('affaireTotalTVA', $affaire->getAffaireTotalTVA())
                ->set('affaireTotalTTC', $affaire->getAffaireTotalTTC())
                ->set('affaireDevisId', $affaire->getAffaireDevisId())
                ->set('affaireDevisDate', $affaire->getAffaireDevisDate())
                ->set('affaireDevisTauxAcompte', $affaire->getAffaireDevisTauxAcompte())
                ->set('affaireDevisEnvoye', $affaire->getAffaireDevisEnvoye())
                ->set('affaireCommandeId', $affaire->getAffaireCommandeId())
                ->set('affaireCommandeDate', $affaire->getAffaireCommandeDate())
                ->set('affairePAO', $affaire->getAffairePAO())
                ->set('affaireFabrication', $affaire->getAffaireFabrication())
                ->set('affairePose', $affaire->getAffairePose())
                ->set('affaireCloture', $affaire->getAffaireCloture())
                ->where('affaireId', $affaire->getAffaireId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe affaire
     *
     * @param Affaire Objet de la classe Affaire
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Affaire $affaire) {
        $this->db->where('affaireId', $affaire->getAffaireId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Affaires correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des affaires
     * @param array $tri Critères de tri des affaires
     * @return array Liste d'objets de la classe Affaire
     */
    public function liste($where = array(), $tri = 'affaireDate DESC', $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('affaires a')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function listeAll($where = array(), $tri = 'affaireDate DESC', $type = 'array') {
        $query = $this->db->select('a.*, c.clientRaisonSociale AS client')
                ->from('affaires a')
                ->join('affaireClients ac', 'ac.affaireClientAffaireId = a.affaireId')
                ->join('clients c', 'c.clientId = ac.affaireClientClientId')
                ->where('ac.affaireClientPrincipal', 1)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Affaire correspondant à l'id
     * @param integer $affaireId ID de l'affaire
     * @return \Affaire|boolean
     */
    public function getAffaireById($affaireId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('affaires a')
                ->where('a.affaireId', $affaireId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne la derniere numérotation d'un devis
     * @return int
     */
    public function getLastDevis($type = 'array') {
        $query = $this->db->select('a.*')
                ->from('affaires a')
                ->limit(1, 0)
                ->order_by('affaireDevisId DESC')
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne la derniere numérotation d'une commande
     * @return int
     */
    public function getLastCommande($type = 'array') {
        $query = $this->db->select('a.*')
                ->from('affaires a')
                ->limit(1, 0)
                ->order_by('affaireCommandeId DESC')
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
