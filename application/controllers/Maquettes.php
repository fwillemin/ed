<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Maquettes extends My_Controller {

    const tauxTVA = 20;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) :
            redirect('secure/login');
        endif;
    }

    public function listeMaquettes() {

        $data = array(
            'clients' => $this->managerClients->liste(),
            'title' => 'Maquettes',
            'description' => 'Liste des Maquettes',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheMaquette($maquetteId = null) {

        if (!$maquetteId || !$this->existMaquette($maquetteId)):
            redirect('maquettes/listeMaquettes');
        endif;

        $maquette = $this->managerMaquettes->getMaquetteById($maquetteId);
        $maquette->hydrateClient();

        $data = array(
            'maquette' => $maquette,
            'clients' => $this->managerClients->liste(),
            'title' => 'Maquette',
            'description' => 'Fiche maquette',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    /**
     * Retourne la liste de toutes les maquettes
     */
    public function getAllMaquettes() {
        $maquettes = $this->managerMaquettes->listeAll(array(), 'maquettedateLimite ASC', 'array');
        if (!empty($maquettes)):
            foreach ($maquettes as $maquette):
                if ($maquette->maquetteClientId):
                    $maquette->client = $maquette->clientRS;
                else:
                    $maquette->client = $maquette->maquetteClientText;
                endif;

                switch ($maquette->maquetteAvancement):
                    case 1:
                        $maquette->maquetteAvancementText = '<span style="color: steelblue;"><i class="fas fa-pause"></i> Attente</span>';
                        break;
                    case 2:
                        $maquette->maquetteAvancementText = '<span style="color: goldenrod;"><i class="fas fa-play"></i> En cours</span>';
                        break;
                    case 3:
                        $maquette->maquetteAvancementText = '<span style="color: green;"><i class="fas fa-check"></i> Prêt</span>';
                        break;
                endswitch;
            endforeach;
        endif;
        echo json_encode($maquettes);
    }

    /**
     * Enregistre en BDD l'maquette en cours dans le concepteur
     */
    public function addMaquette() {

        if (!$this->form_validation->run('addMaquette')):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r(validation_errors(), true));
            redirect('maquettes/listeMaquettes/ajouter');
        else:

            if ($this->input->post('addMaquetteClientId')):
                $clientId = $this->input->post('addMaquetteClientId');
                $clientText = '';
            else:
                $clientId = null;
                $clientText = $this->input->post('addMaquetteClientText');
            endif;

            if ($this->input->post('addMaquetteId')):

                $maquette = $this->managerMaquettes->getMaquetteById($this->input->post('addMaquetteId'));
                $maquette->setMaquetteClientId($clientId);
                $maquette->setMaquetteClientText($clientText);
                $maquette->setMaquetteDateCreation($this->xth->mktimeFromInputDate($this->input->post('addMaquetteDateCreation')));
                $maquette->setMaquetteDateLimite($this->xth->mktimeFromInputDate($this->input->post('addMaquetteDateLimite')));

                $maquette->setMaquetteDescription($this->input->post('addMaquetteDescription'));
                $maquette->setMaquetteDifficulte($this->input->post('addMaquetteDifficulte'));
                $maquette->setMaquettePathFiles($this->input->post('addMaquettePathFiles'));

                $this->managerMaquettes->editer($maquette);

            else:

                $dataMaquette = array(
                    'maquetteDateCreation' => $this->xth->mktimeFromInputDate($this->input->post('addMaquetteDateCreation')),
                    'maquetteDateLimite' => $this->xth->mktimeFromInputDate($this->input->post('addMaquetteDateLimite')),
                    'maquetteClientId' => $clientId,
                    'maquetteClientText' => $clientText,
                    'maquetteDescription' => $this->input->post('addMaquetteDescription'),
                    'maquetteDifficulte' => $this->input->post('addMaquetteDifficulte'),
                    'maquetteAvancement' => 1,
                    'maquettePathFiles' => $this->input->post('addMaquettePathFiles')
                );

                $maquette = new Maquette($dataMaquette);
                $this->managerMaquettes->ajouter($maquette);

            endif;
            redirect('maquettes/ficheMaquette/' . $maquette->getMaquetteId());
        endif;
    }

    public function avancementMaquette() {
        if (!$this->input->post('maquetteId') || !$this->existMaquette($this->input->post('maquetteId'))):
            echo json_encode(array('type' => 'error', 'message' => 'Maquette introuvable'));
        else:
            $maquette = $this->managerMaquettes->getMaquetteById($this->input->post('maquetteId'));
            $maquette->avancement();
            $this->managerMaquettes->editer($maquette);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function delMaquette() {
        if (!$this->input->post('maquetteId') || !$this->existMaquette($this->input->post('maquetteId'))):
            echo json_encode(array('type' => 'error', 'message' => 'Maquette introuvable'));
        else:
            $maquette = $this->managerMaquettes->getMaquetteById($this->input->post('maquetteId'));
            $this->managerMaquettes->delete($maquette);
            echo json_encode(array('type' => 'success'));
        endif;
    }

}
