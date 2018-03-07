<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Affectations extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);

        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;

        /* Initialisation des variables de session */
        if (!$this->session->userdata('annee') || intval($this->session->userdata('annee')) > 2050 || intval($this->session->userdata('annee')) < 2015) :
            $this->session->set_userdata('annee', date('Y'));
        endif;
        if (!$this->session->userdata('semaine')) :
            $this->session->set_userdata('semaine', date('W'));
        endif;

        $this->lang->load('calendar_lang', 'french');
    }

    public function addAffectation() {
        if (!$this->ion_auth->is_admin()) :
            echo json_encode(array('type' => 'error', 'message' => 'Vous devez être administrateur pour ajouter une affectation'));
            exit;
        endif;

        if (!$this->form_validation->run('addAffectation')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $dossier = $this->managerDossiers->getDossierById($this->input->post('addAffectDossierId'));
        $affaire = $this->managerAffaires->getAffaireById($this->input->post('addAffectAffaireId'));
        $equipe = $this->managerEquipes->getEquipeById($this->input->post('addAffectEquipeId'));

        if ($this->input->post('addAffectId')) :
            $affect = $this->managerAffectations->getAffectationById($this->input->post('addAffectId'));

            /* Si on change la date ou l'équipe, on intervient sur les positions de l'affectation */
            if ($affect->getAffectationDate() != $this->xth->mktimeFromInputDate($this->input->post('addAffectDate')) || $affect->getAffectationEquipeId() != $this->input->post('addAffectEquipeId')) :
                $dateOrigine = $affect->getAffectationDate();
                $equipeOrigine = $affect->getAffectationEquipeId();

                /* Position dans les nouvelles conditions */
                $position = $this->managerAffectations->getNewPosition(intval($this->input->post('addAffectEquipeId')), $this->xth->mktimeFromInputDate($this->input->post('addAffectDate')));
                $affect->setAffectationPosition($position);

                $affect->setAffectationEquipeId(intval($this->input->post('addAffectEquipeId')));
                $affect->setAffectationDate($this->xth->mktimeFromInputDate($this->input->post('addAffectDate')));

                /* On renumérote le jour/Equipe d'origine */
                $this->renumerotation($equipeOrigine, $dateOrigine);
            endif;

            $affect->setAffectationType($this->input->post('addAffectType'));
            $affect->setAffectationIntervenant($this->input->post('addAffectIntervenant'));
            $affect->setAffectationCommentaire($this->input->post('addAffectCommentaire'));
            $this->managerAffectations->editer($affect);

            echo json_encode(array('type' => 'success'));
            exit;

        else :
            /* On ajoute une affectation par nombre de jours demandés */
            $jourAffect = $this->xth->mktimeFromInputDate($this->input->post('addAffectDate'));
            for ($i = 0; $i < $this->input->post('addAffectNbJour'); $i++) :
                if (date('N', $jourAffect) == 6) :
                    $jourAffect += 172800;
                endif;

                /* Recherche du nombre d'affectation ce jour pour cette équipe pour connaitre la position par défaut de l'affectation */
                $position = $this->managerAffectations->getNewPosition(intval($this->input->post('addAffectEquipeId')), $jourAffect);

                $dataAffect = array(
                    'affectationDossierId' => $this->input->post('addAffectDossierId') ?: null,
                    'affectationAffaireId' => $this->input->post('addAffectAffaireId') ?: null,
                    'affectationType' => $this->input->post('addAffectType'),
                    'affectationEquipeId' => $this->input->post('addAffectEquipeId'),
                    'affectationDate' => $jourAffect,
                    'affectationEtat' => 1,
                    'affectationPosition' => $position,
                    'affectationCommentaire' => $this->input->post('addAffectCommentaire'),
                    'affectationIntervenant' => $this->input->post('addAffectIntervenant')
                );

                $affect = new Affectation($dataAffect);
                $this->managerAffectations->ajouter($affect);

                $jourAffect += 86400;
            endfor;

            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    public function getAffectation() {
        if (!$this->form_validation->run('getAffectation')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $affectation = $this->managerAffectations->getAffectationById($this->input->post('affectationId'), 'array');
        echo json_encode(array('type' => 'success', 'affectation' => $affectation));
        exit;
    }

    public function delAffectation() {

        if (!$this->form_validation->run('getAffectation')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $affectation = $this->managerAffectations->getAffectationById(intval($this->input->post('affectationId')));
        /* on renumérote les autres affectations de ce jour */
        $this->renumerotation($affectation->getAffectationEquipeId(), $affectation->getAffectationDate());

        $this->managerAffectations->delete($affectation);
        echo json_encode(array('type' => 'success'));
        exit;
    }

    private function _monter(Affectation $affectation) {

        $positionActuelle = $affectation->getAffectationPosition();

        $affectation->setAffectationPosition($positionActuelle - 1);
        $this->managerAffectations->editer($affectation);

        /* on recherche l'affectation à décaler */
        $other = $this->managerAffectations->liste(array('affectationDate' => $affectation->getAffectationDate(), 'affectationEquipeId' => $affectation->getAffectationEquipeId(), 'affectationPosition' => $positionActuelle - 1, 'affectationId <> ' => $affectation->getAffectationId()));
        if ($other) :
            $other[0]->setAffectationPosition($positionActuelle);
            $this->managerAffectations->editer($other[0]);
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . 'Pas d\'autres affectation ? hummm...');
        endif;
    }

    private function _descendre(Affectation $affectation) {

        $positionActuelle = $affectation->getAffectationPosition();

        $affectation->setAffectationPosition($positionActuelle + 1);
        $this->managerAffectations->editer($affectation);

        /* on recherche l'affectation à décaler */
        $other = $this->managerAffectations->liste(array('affectationDate' => $affectation->getAffectationDate(), 'affectationEquipeId' => $affectation->getAffectationEquipeId(), 'affectationPosition' => $positionActuelle + 1, 'affectationId <> ' => $affectation->getAffectationId()));
        if ($other) :
            $other[0]->setAffectationPosition($positionActuelle);
            $this->managerAffectations->editer($other[0]);
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . 'Pas d\'autres affectation ? hummm...');
        endif;
    }

    public function changerPosition() {
        $this->form_validation->set_rules('affectId', 'ID de l\'affectation', 'required|trim|is_natural_no_zero');
        $this->form_validation->set_rules('newPosition', 'ID de l\'affectation', 'required|trim|is_natural');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $affectation = $this->managerAffectations->getAffectationById(intval($this->input->post('affectId')));
            if (!$affectation) :
                echo json_encode(array('type' => 'error', 'message' => 'Affectation introuvable.'));
                exit;
            else :
                $positionActuelle = $affectation->getAffectationPosition();
                $nouvellePosition = intval($this->input->post('newPosition')) + 1; /* l'index du DOM démarre à 0 et celui de la BDD à 1 */

                if ($positionActuelle > $nouvellePosition) :
                    for ($i = $positionActuelle; $i > $nouvellePosition; $i--) {
                        $this->_monter($affectation);
                    }
                else :
                    for ($i = $positionActuelle; $i < $nouvellePosition; $i++) {
                        $this->_descendre($affectation);
                    }
                endif;

                echo json_encode(array('type' => 'success'));
                exit;
            endif;
        endif;
    }

    public function nextStep() {

        if (!$this->form_validation->run('getAffectation')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $affectation = $this->managerAffectations->getAffectationById($this->input->post('affectationId'));

        if ($affectation->getAffectationDossierClos() == 0) :
            $affectation->nextStep();
            $this->managerAffectations->editer($affectation);

            echo json_encode(array('type' => 'success', 'backgroundColor' => $affectation->getAffectationCouleur(), 'fontColor' => $affectation->getAffectationFontColor()));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier cette affectation car le dossier est clos.'));
            exit;
        endif;
    }

    /** RECURRENT */
    public function recurrent() {
        if (!$this->ion_auth->is_admin()) :
            redirect('ed/journalier');
            exit;
        endif;
        $data = array(
            'recurrents' => $this->managerRecurrents->liste(),
            'equipes' => $this->managerEquipes->liste(),
            'title' => 'Liste des opérations récurrentes',
            'description' => 'Gérer vos opérations récurrentes',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addRecurrent() {

        if (!$this->form_validation->run('addRecurrent') || (!in_array(strtolower($this->input->post('addRecurrentCritere')), array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi')) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])$/", $this->input->post('addRecurrentCritere')) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->input->post('addRecurrentCritere')) )
        ) :
            echo json_encode(array('type' => 'error', 'message' => 'Vous devez saisir un critère de récurrence valide.'));
            exit;
        endif;

        if ($this->input->post('addRecurrentId')) :
            $recurrent = $this->managerRecurrents->getRecurrentById(intval($this->input->post('addRecurrentId')));

            $recurrent->setRecurrentEquipeId($this->input->post('addRecurrentEquipeId'));
            $recurrent->setRecurrentCommentaire($this->input->post('addRecurrentCommentaire'));
            $recurrent->setRecurrentCritere(strtolower($this->input->post('addRecurrentCritere')));

            $this->managerRecurrents->editer($recurrent);
        else :
            $dataRecurrent = array(
                'recurrentEquipeId' => $this->input->post('addRecurrentEquipeId'),
                'recurrentCommentaire' => $this->input->post('addRecurrentCommentaire'),
                'recurrentCritere' => strtolower($this->input->post('addRecurrentCritere'))
            );

            $recurrent = new Recurrent($dataRecurrent);
            $this->managerRecurrents->ajouter($recurrent);
        endif;

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function getRecurrent() {
        if (!$this->form_validation->run('getRecurrent')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $recurrent = $this->managerRecurrents->getRecurrentById($this->input->post('recurrentId'), 'array');
        echo json_encode(array('type' => 'success', 'recurrent' => $recurrent));
        exit;
    }

    public function delRecurrent() {

        if (!$this->form_validation->run('getRecurrent')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $recurrent = $this->managerRecurrents->getRecurrentById($this->input->post('recurrentId'));

        $this->managerRecurrents->delete($recurrent);
        echo json_encode(array('type' => 'success'));
        exit;
    }

}
