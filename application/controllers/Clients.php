<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clients extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) :
            redirect('secure/login');
        endif;
    }

    public function index() {
        redirect('clients/liste');
        exit;
    }

    public function liste() {
        $data = array(
            'title' => 'Gestion des clients',
            'description' => 'Module de gestion des clients',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function getAllClients() {
        $clients = $this->managerClients->liste(array(), 'clientRaisonSociale ASC', 'array');
        echo json_encode($clients);
    }

    public function ficheClient($clientId = null) {
        if (!$clientId || !$this->existClient($clientId)) :
            redirect('clients');
            exit;
        endif;

        $client = $this->managerClients->getClientById($clientId);
        $client->hydrateAffaires();
        $client->hydrateContacts();
        $client->hydrateFactures();
        $client->hydrateAvoirs();
        $client->hydrateRemises();
        if ($client->getClientRemises()):
            foreach ($client->getClientRemises() as $remise):
                $remise->hydrateFamille();
            endforeach;
        endif;
        //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($client, true));

        $data = array(
            'client' => $client,
            'familles' => $this->managerFamilles->liste(),
            'title' => 'Fiche client',
            'description' => 'Fiche du client ' . $client->getClientRaisonSociale(),
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    private function modClient() {

        $client = $this->managerClients->getClientById($this->input->post('addClientId'));

        $client->setClientRaisonSociale(strtoupper($this->input->post('addClientRaisonSociale')));
        $client->setClientExoneration($this->input->post('addClientExoneration') == 1 ? 1 : 0);
        $client->setClientAdresse1(strtoupper($this->input->post('addClientAdresse1')));
        $client->setClientAdresse2(strtoupper($this->input->post('addClientAdresse2')));
        $client->setClientCp($this->input->post('addClientCp'));
        $client->setClientVille(strtoupper($this->input->post('addClientVille')));
        $client->setClientPays($this->input->post('addClientPays'));
        $client->setClientTelephone($this->input->post('addClientTelephone'));
        $client->setClientNumTva($this->input->post('addClientNumTva'));
        $client->setClientEcheancePaiement($this->input->post('addClientEcheancePaiement'));

        $this->managerClients->editer($client);
        return true;
    }

    private function addClient() {

        $dataClient = array(
            'clientRaisonSociale' => strtoupper($this->input->post('addClientRaisonSociale')),
            'clientExoneration' => $this->input->post('addClientExoneration') == 1 ? 1 : 0,
            'clientAdresse1' => strtoupper($this->input->post('addClientAdresse1')),
            'clientAdresse2' => strtoupper($this->input->post('addClientAdresse2')),
            'clientCp' => $this->input->post('addClientCp'),
            'clientVille' => strtoupper($this->input->post('addClientVille')),
            'clientPays' => $this->input->post('addClientPays'),
            'clientTelephone' => $this->input->post('addClientTelephone'),
            'clientNumTva' => $this->input->post('addClientNumTva'),
            'clientEcheancePaiement' => $this->input->post('addClientEcheancePaiement')
        );
        $client = new Client($dataClient);
        $this->managerClients->ajouter($client);
        return true;
    }

    public function manageContacts() {

        if ($this->form_validation->run('addContact')) :

            if ($this->input->post('addContactId')):

                if ($this->modContact($_POST)):
                    echo json_encode(array('type' => 'success'));
                else:
                    echo json_encode(array('type' => 'error', 'message' => validation_errors()));
                endif;
                exit;

            else :

                if ($this->addContact($_POST)):
                    echo json_encode(array('type' => 'success'));
                else:
                    echo json_encode(array('type' => 'error', 'message' => validation_errors()));
                endif;
                exit;

            endif;

        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    private function addContact() {

        $dataContact = array(
            'contactClientId' => $this->input->post('addContactClientId'),
            'contactCivilite' => $this->input->post('addContactCivilite'),
            'contactNom' => $this->input->post('addContactNom'),
            'contactPrenom' => $this->input->post('addContactPrenom'),
            'contactFonction' => $this->input->post('addContactFonction'),
            'contactTelephone' => $this->input->post('addContactTelephone'),
            'contactPortable' => $this->input->post('addContactPortable'),
            'contactEmail' => strtolower($this->input->post('addContactEmail')),
        );
        $contact = new Contact($dataContact);
        $this->managerContacts->ajouter($contact);
        return true;
    }

    private function modContact() {

        $contact = $this->managerContacts->getContactById($this->input->post('addContactId'));

        $contact->setContactCivilite($this->input->post('addContactCivilite'));
        $contact->setContactNom(strtoupper($this->input->post('addContactNom')));
        $contact->setContactPrenom(strtoupper($this->input->post('addContactPrenom')));
        $contact->setContactFonction($this->input->post('addContactFonction'));
        $contact->setContactTelephone($this->input->post('addContactTelephone'));
        $contact->setContactPortable($this->input->post('addContactPortable'));
        $contact->setContactEmail(strtolower($this->input->post('addContactEmail')));

        $this->managerContacts->editer($contact);
        return true;
    }

    public function getContact() {
        if ($this->form_validation->run('getContact')):
            echo json_encode(array('contact' => $this->managerContacts->getContactById($this->input->post('contactId'), 'array')));
        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

    public function delContact() {

        if (!$this->form_validation->run('getContact')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $contact = $this->managerContacts->getContactById($this->input->post('contactId'));
        $this->managerContacts->delete($contact);
        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function manageClients() {

        if ($this->form_validation->run('addClient')) :

            if ($this->input->post('addClientId')):

                if ($this->modClient($_POST)):
                    echo json_encode(array('type' => 'success'));
                else:
                    echo json_encode(array('type' => 'error', 'message' => validation_errors()));
                endif;
                exit;

            else :

                if ($this->addClient($_POST)):
                    echo json_encode(array('type' => 'success'));
                else:
                    echo json_encode(array('type' => 'error', 'message' => validation_errors()));
                endif;
                exit;

            endif;

        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function delClient() {

        if (!$this->form_validation->run('delClient')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $client = $this->managerClients->getClientById(intval($this->input->post('clientId')));
        if (empty($client->getClientAffaires())) :
            $this->managerClients->delete($client);
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => 'Il y a des Affaires'));
        endif;
        exit;
    }

    public function getClient($session = null) {
        /* Si $session est défini on passe l'id client en variable de session */

        $this->form_validation->set_rules('clientId', 'client', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $client = $this->managerClients->getClientById(intval($this->input->post('clientId')), 'array');
            if (empty($client)) :
                echo json_encode(array('type' => 'error', 'message' => 'Client en cavale !'));
                exit;
            else :
                if ($session) :
                    $this->session->set_userdata(array('venteClientId' => $client['clientId'], 'venteClientType' => $client['clientType']));
                endif;
                echo json_encode(array('type' => 'success', 'client' => $client));
                exit;
            endif;
        endif;
    }

    public function modPassword() {

        $this->form_validation->set_rules('identity', 'Identité du client', 'required|valid_email');
        $this->form_validation->set_rules('new', 'Nouveau', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm', 'Confirmation', 'required|min_length[8]|matches[new]');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else :
            $change = $this->resetPassword($this->input->post('identity'), $this->input->post('new'));
            if (!$change) :
                echo json_encode(array('type' => 'error', 'message' => $this->ion_auth->errors()));
            else :
                echo json_encode(array('type' => 'success'));
            endif;
        endif;
        exit;
    }

    private function resetPassword($identity, $new) {
        if ($this->ion_auth->reset_password($identity, $new)) :
            return true;
        else :
            return false;
        endif;
    }

    public function clientSearch() {

        $this->form_validation->set_rules('clientSearch', 'Recherche', 'required|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            //$clients = $this->managerClients->recherche( array('clientNom LIKE' => '%' . $this->input->post('clientSearch') . '%'), 'clientNom ASC', 'array');
            $clients = $this->managerClients->recherche($this->input->post('clientSearch'), 'clientRaisonSociale ASC', 'array');
            echo json_encode(array('type' => 'success', 'clients' => $clients));
            exit;
        endif;
    }

    public function addRemise() {

        if (!$this->form_validation->run('addRemise')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $remise = $this->managerRemises->count(array('remiseFamilleId' => $this->input->post('addRemiseFamilleId'), 'remiseClientId' => $this->input->post('addRemiseClientId')));
        if ($remise > 0):
            echo json_encode(array('type' => 'error', 'message' => 'Ce client a déjà une remise pour cette famille'));
        else:
            $dataRemise = array(
                'remiseClientId' => $this->input->post('addRemiseClientId'),
                'remiseFamilleId' => $this->input->post('addRemiseFamilleId'),
                'remiseTaux' => $this->input->post('addRemiseTaux')
            );
            $remise = new Remise($dataRemise);
            $this->managerRemises->ajouter($remise);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function delRemise() {
        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($_POST, true));
        $remise = $this->managerRemises->getRemise($this->input->post('clientId'), $this->input->post('familleId'));
        $this->managerRemises->delete($remise);
        echo json_encode(array('type' => 'success'));
        exit;
    }

}
