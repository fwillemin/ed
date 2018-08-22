<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dossiers extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);

        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;
    }

    public function addDossier() {

        if (!$this->form_validation->run('addDossier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addDossierId')) :
            $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('addDossierId')));

            $dossier->setDossierClient($this->input->post('addDossierClient'));
            $dossier->setDossierDescriptif($this->input->post('addDossierDescriptif'));
            $dossier->setDossierFab($this->input->post('addDossierFab') ? 1 : 0);
            $dossier->setDossierPose($this->input->post('addDossierPose') ? 1 : 0);
            $dossier->setDossierPao($this->input->post('addDossierPao') ? 1 : 0);

            $this->managerDossiers->editer($dossier);
        else :
            $dataDossier = array(
                'dossierClient' => $this->input->post('addDossierClient'),
                'dossierDescriptif' => $this->input->post('addDossierDescriptif'),
                'dossierSortieEtat' => 1,
                'dossierPao' => $this->input->post('addDossierPao') ? 1 : 0,
                'dossierFab' => $this->input->post('addDossierFab') ? 1 : 0,
                'dossierPose' => $this->input->post('addDossierPose') ? 1 : 0,
                'dossierClos' => 0
            );

            $dossier = new Dossier($dataDossier);
            $this->managerDossiers->ajouter($dossier);
        endif;

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function delDossier() {

        if (!$this->form_validation->run('getDossier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $dossier = $this->managerDossiers->getDossierById($this->input->post('dossierId'));
        $dossier->hydrateAffectations();

        if ($dossier->getDossierAffectations()) :
            foreach ($dossier->getDossierAffectations() as $a) :
                $this->managerAffectations->delete($a);
                /* on renumérote les autres affectations de ce jour */
                $this->renumerotation($a->getAffectationEquipeId(), $a->getAffectationDate());
            endforeach;
        endif;

        /* Suppression du dossier */
        $this->managerDossiers->delete($dossier);
        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function nextStepSortie() {

        if (!$this->form_validation->run('getDossier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('dossierId')));

        if ($dossier->getDossierClos() == 0) :
            $dossier->nextStepSortie();
            $this->managerDossiers->editer($dossier);
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier ce dossier car il est clos.'));
            exit;
        endif;
    }

    public function dossiersClos() {
        if (!$this->ion_auth->is_admin()) :
            redirect('ed/journalier');
            exit;
        endif;

        $dossiers = $this->managerDossiers->liste(array('dossierClos' => 1), 'dossierId DESC');
        if (!empty($dossiers)):
            foreach ($dossiers as $d):
                $d->hydrateAffectations();
            endforeach;
        endif;

        $data = array(
            'equipes' => $this->managerEquipes->liste(),
            'dossiers' => $dossiers,
            'title' => 'Dossiers clos',
            'description' => 'Liste des dossiers clos',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function getDossier() {

        if (!$this->form_validation->run('getDossier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $dossier = $this->managerDossiers->getDossierById($this->input->post('dossierId'), 'array');

        $affectationsPao = $this->managerAffectations->liste(array('affectationDossierId' => $dossier->dossierId, 'affectationType' => 3), 'affectationDate ASC', 'array');
        $affectationsFab = $this->managerAffectations->liste(array('affectationDossierId' => $dossier->dossierId, 'affectationType' => 1), 'affectationDate ASC', 'array');
        $affectationsPose = $this->managerAffectations->liste(array('affectationDossierId' => $dossier->dossierId, 'affectationType' => 2), 'affectationDate ASC', 'array');

        echo json_encode(array('type' => 'success', 'dossier' => $dossier, 'fab' => $affectationsFab, 'pose' => $affectationsPose, 'pao' => $affectationsPao));
        exit;
    }

    public function clotureDossier() {

        if (!$this->form_validation->run('getDossier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $dossier = $this->managerDossiers->getDossierById($this->input->post('dossierId'));
        $dossier->cloture();
        $this->managerDossiers->editer($dossier);
        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function ouvertureDossier() {

        if (!$this->form_validation->run('getDossier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $dossier = $this->managerDossiers->getDossierById($this->input->post('dossierId'));
        $dossier->ouverture();
        $this->managerDossiers->editer($dossier);
        echo json_encode(array('type' => 'success'));
        exit;
    }

}
