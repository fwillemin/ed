<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_clients extends MY_model {

    protected $table = 'clients';

    const classe = 'Client';

    /**
     * Ajout d'un objet de la classe Client à la BDD
     * @param Client $client Objet de la classe Client
     */
    public function ajouter(Client $client) {
        $this->db
                ->set('clientRaisonSociale', $client->getClientRaisonSociale())
                ->set('clientNumTva', $client->getClientNumTva())
                ->set('clientExoneration', $client->getClientExoneration())
                ->set('clientTelephone', $client->getClientTelephone())
                ->set('clientAdresse1', $client->getClientAdresse1())
                ->set('clientAdresse2', $client->getClientAdresse2())
                ->set('clientCp', $client->getClientCp())
                ->set('clientVille', $client->getClientVille())
                ->set('clientPays', $client->getClientPays())
                ->insert($this->table);
        $client->setClientId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Client
     * @param Client $client Objet de la classe Client
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Client $client) {
        $this->db
                ->set('clientRaisonSociale', $client->getClientRaisonSociale())
                ->set('clientNumTva', $client->getClientNumTva())
                ->set('clientExoneration', $client->getClientExoneration())
                ->set('clientTelephone', $client->getClientTelephone())
                ->set('clientAdresse1', $client->getClientAdresse1())
                ->set('clientAdresse2', $client->getClientAdresse2())
                ->set('clientCp', $client->getClientCp())
                ->set('clientVille', $client->getClientVille())
                ->set('clientPays', $client->getClientPays())
                ->where('clientId', $client->getClientId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe client
     *
     * @param Client Objet de la classe Client
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Client $client) {
        $this->db->where('clientId', $client->getClientId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Clients correspondant aux critères su paralètre $where
     * @param array $where Critères de selection des clients
     * @param string $type Type de retour des données (array, Object)
     * @return array Liste d'objets de la classe Client
     */
    public function liste($where = array(), $tri = 'c.clientNom ASC', $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->where($where)
                ->order_by($tri)
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($type == 'object'):
                    $clients[] = new Client((array) $row);
                else:
                    $client = $row;
                    $this->hydrateClientContacts($client);
                    $clients[] = $client;
                endif;
            endforeach;
            return $clients;
        else:
            return FALSE;
        endif;
    }

    private function hydrateClientContacts($client) {

        $query = $this->db->select('*')->from('contacts')
                ->where('contactClientId', $client->clientId)
                ->get();
        $contacts = array();
        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                $contacts[] = (array) $row;
            endforeach;
        endif;
        $client->clientContacts = $contacts;
        return true;
    }

    /**
     * Retourne un objet de la classe Client correspondant à l'id
     * @param type $ref
     * @return \Client|boolean
     */
    public function getClientById($clientId, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->where(array('clientId' => $clientId))
                ->group_by('c.clientId')
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function recherche($chaine, $tri = 'c.clientRaisonSociale ASC', $type = 'object') {
        $query = $this->db->select('c.*')
                ->from('clients c')
                ->where('clientRaisonSociale LIKE ', '%' . $chaine . '%')
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
