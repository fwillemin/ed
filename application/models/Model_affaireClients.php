<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_affaireClients extends MY_model {

    protected $table = 'affaireClients';

    const classe = 'AffaireClient';

    /**
     * Ajout d'un objet de la classe AffaireClient à la BDD
     * @param AffaireClient $affaireClient Objet de la classe AffaireClient
     */
    public function ajouter(AffaireClient $affaireClient) {
        $this->db
                ->set('affaireClientAffaireId', $affaireClient->getAffaireClientAffaireId())
                ->set('affaireClientClientId', $affaireClient->getAffaireClientClientId())
                ->set('affaireClientPrincipal', $affaireClient->getAffaireClientPrincipal())
                ->insert($this->table);
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe AffaireClient
     * @param AffaireClient $affaireClient Objet de la classe AffaireClient
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(AffaireClient $affaireClient) {
        $this->db
                ->set('affaireClientPrincipal', $affaireClient->getAffaireClientPrincipal())
                ->where(array('affaireClientAffaireId' => $affaireClient->getAffaireClientAffaireId(), 'affaireClientClientId' => $affaireClient->getAffaireClientClientId()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe affaireClient
     *
     * @param AffaireClient Objet de la classe AffaireClient
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(AffaireClient $affaireClient) {
        $this->db->where(array('affaireClientAffaireId' => $affaireClient->getAffaireClientAffaireId(), 'affaireClientClientId' => $affaireClient->getAffaireClientClientId()))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function deleteClientsByAffaireId(AffaireClient $affaireClient) {
        $this->db->where('affaireClientAffaireId', $affaireClient->getAffaireClientAffaireId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des AffaireClients correspondant aux critères su paralètre $where
     * @param array $where Critères de selection des affaireClient
     * @param string $type Principal de retour des données (array, Object)
     * @return array Liste d'objets de la classe AffaireClient
     */
    public function getClientsByAffaireId($affaireId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireClientAffaireId', $affaireId)
                ->order_by('affaireClientPrincipal DESC')
                ->get();
        if ($query->num_rows() > 0):

            foreach ($query->result() AS $row):

                $client = $this->managerClients->getClientById($row->affaireClientClientId, $type);
                if ($type == 'object'):
                    $client->setClientPrincipal($row->affaireClientPrincipal);
                else:
                    $client->principal = $row->affaireClientPrincipal;
                endif;
                $affaireClient[] = $client;
            endforeach;

            return $affaireClient;
        else:
            return FALSE;
        endif;
    }

    public function resetAffaire(Affaire $affaire) {
        $this->db->where('affaireClientAffaireId', $affaire->getAffaireId())->delete('affaireClients');
    }

    public function getAffairesByClientId($clientId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireClientClientId', $clientId)
                ->get();
        if ($query->num_rows() > 0):

            foreach ($query->result() AS $row):

                $affaire = $this->managerAffaires->getAffaireById($row->affaireClientAffaireId, $type);
                $clientAffaires[] = $affaire;
            endforeach;

            return $clientAffaires;
        else:
            return FALSE;
        endif;
    }

}
