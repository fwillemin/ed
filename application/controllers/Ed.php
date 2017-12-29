<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ed extends CI_Controller {

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

    public function index() {

        if (!$this->ion_auth->is_admin()) :
            redirect('ed/journalier');
        else :
            redirect('ed/hebdomadaire');
        endif;
        exit;
    }

    public function hebdomadaire($semaine = null, $annee = null) {
        if (!$this->ion_auth->is_admin()) :
            redirect('ed/journalier');
            exit;
        endif;

        if ($annee && intval($annee) > 2014 && intval($annee) < 2050) :
            $this->session->set_userdata('annee', intval($annee));
        endif;
        $annee = $this->session->userdata('annee');

        if ($semaine && intval($semaine) > 0 && intval($semaine) < 54) :
            /* On vérifie que la semaine existe bien dans l'année (cas de l'année avec 53 semaines) */
            $nbSemaineAnnee = date("W", mktime(12, 0, 0, 12, 30, $annee));
            if (intval($semaine) > $nbSemaineAnnee) :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => La semaine est supérieure au nombre possible dans l\'année (' . $semaine . '/' . $nbSemaineAnnee . ')');
                $this->session->set_userdata('semaine', 52);
            else :
                $this->session->set_userdata('semaine', intval($semaine));
            endif;
        endif;
        $semaine = $this->session->userdata('semaine');

        /* On recherche le premier jour de la semaine */
        $premierJourSemaine = (mktime(0, 0, 0, 1, 1, $annee) - (date('N', mktime(0, 0, 0, 1, 1, $annee)) - 1) * 86400) + ($semaine - 1) * 604800;

        /* cas des années à 53 semaines (le 01/01 est un jeudi ou plus tard dans la semaine) */
        if (date('N', mktime(0, 0, 0, 1, 1, $annee)) > 4) :
            $premierJourSemaine += 604800;
        endif;
        if (date('I', $premierJourSemaine) == 1) {
            $premierJourSemaine -= 3600;
        }

        $dernierJourSemaine = $premierJourSemaine + (7 * 86400);

        $divs = array();
        $equipes = $this->managerEquipes->liste();
        if (!empty($equipes)) :
            foreach ($equipes as $e) :
                for ($i = 0; $i < 5; $i++) :
                    $d = date('d', $premierJourSemaine + ($i * 86400));
                    $divs[$e->getEquipeId() . '-' . $d] = array();
                endfor;
            endforeach;
        endif;

        /* Selection des affectations */
        $affectations = $this->managerAffectations->liste(array('affectationDate >= ' => $premierJourSemaine, 'affectationDate <= ' => $dernierJourSemaine));

        if (!empty($affectations)) :
            foreach ($affectations as $a) :
                $divs[$a->getAffectationEquipeId() . '-' . date('d', $a->getAffectationDate())][] = '<div class="progHebdo tooltiped" style="background-color:' . $a->getAffectationCouleur() . '; color:' . $a->getAffectationFontColor() . ';" data-affectId="' . $a->getAffectationId() . '" data-placement="top" title="' . $a->getAffectationCommentaire() . '">' .
                        '<div class="btnModAffect" style="padding:7px; text-align: center; font-weight: bold; position:relative;">' .
                        '<button class="btn btn-default btn-xs btnHebdoNext" style="position:absolute; top:0px; right:0px;" data-affectid="' . $a->getAffectationId() . '"><i class="glyphicon glyphicon-repeat"></i></button>' .
                        $a->getAffectationClient() .
                        '</div>' .
                        '<div class="intervenant">Intervenants : <strong>' .
                        $a->getAffectationIntervenant() .
                        '</strong></div>' .
                        '</div>';
            endforeach;
        endif;


        /* Les récurrents */
        for ($i = $premierJourSemaine; $i < $dernierJourSemaine; $i += 86400) :
            $recur = $this->managerRecurrents->getJour($i);
            if (!empty($recur)) :
                foreach ($recur as $r) :
                    $divs[$r->getRecurrentEquipeId() . '-' . date('d', $i)][] = '<div class="progHebdo tooltiped" style="background-color: yellow; color: #000;" data-affectId="0" data-placement="top" title="' . $r->getRecurrentCommentaire() . '">' .
                            '<div style="padding:7px; text-align: center; font-weight: bold;">Enseigne diffusion</div>' .
                            '</div>';
                endforeach;
            endif;
        endfor;

        $data = array(
            'equipes' => $this->managerEquipes->liste(),
            'annee' => $annee,
            'semaine' => $semaine,
            'premierJourSemaine' => $premierJourSemaine,
            'dernierJourSemaine' => $dernierJourSemaine,
            'divs' => $divs,
            'dossiers' => $this->managerDossiers->liste(array('dossierClos' => 0)),
            'title' => 'Hebdomadaire',
            'description' => 'Planning hebdomadaire',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function journalier($jour = null) {
        /* $jour au format aaaa-mm-dd */
        if ($jour && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $jour) && checkdate(explode('-', $jour)[1], explode('-', $jour)[2], explode('-', $jour)[0])) :
            /* C'est une date valide */
            $this->session->set_userdata('jour', $this->xth->mktimeFromInputDate($jour));
        else :
            if (!$this->session->userdata('jour')) :
                $this->session->set_userdata('jour', $this->xth->mktimeFromInputDate(date("Y-m-d")));
            endif;
        endif;

        $affectations = array();
        //$sorties = array();

        $equipes = $this->managerEquipes->liste();
        if (!empty($equipes)) :
            foreach ($equipes as $e) :
                $affectations[$e->getEquipeId()] = array();
            endforeach;
        endif;


        /* Selection des affectations */
        $affectationsListe = $this->managerAffectations->liste(array('affectationDate' => $this->session->userdata('jour')));
        if (!empty($affectationsListe)) :
            foreach ($affectationsListe as $a) :
                if ($a->getAffectationDossierClos() == 1) :
                    $classAffect = "progJourClos";
                elseif ($a->getAffectationEtat() == 3) :
                    $classAffect = "progJourTermine";
                else :
                    $classAffect = "progJour";
                endif;

                $affectations[$a->getAffectationEquipeId()][] = '<div class="' . $classAffect . '" style="background-color:' . $a->getAffectationCouleur() . '; color:' . $a->getAffectationFontColor() . ';" data-affectId="' . $a->getAffectationId() . '">' .
                        '<div class="intervenantJournalier" style="">Intervenants :<br><strong>' .
                        $a->getAffectationIntervenant() .
                        '</strong></div>' .
                        '<div style="padding:7px;">' .
                        '<center><span style="font-size:15px; font-weight:bold;">' . $a->getAffectationClient() . '</span></center>' .
                        $a->getAffectationCommentaire() .
                        '</div>' .
                        '</div>';
            endforeach;
        endif;

        $recurrentsListe = $this->managerRecurrents->getJour($this->session->userdata('jour'));
        if (!empty($recurrentsListe)) :
            foreach ($recurrentsListe as $r) :
                $affectations[$r->getRecurrentEquipeId()][] = '<div class="progJourRecurrent" style="background-color: yellow; color: #000;" data-affectid="0">' .
                        '<div class="intervenantJournalier" style="">Intervenants :<br><strong></strong></div>' .
                        '<div style="padding:7px;">' .
                        '<center><span style="font-size:15px; font-weight:bold;">Enseigne Diffusion</span></center>' .
                        $r->getRecurrentCommentaire() .
                        '</div>' .
                        '</div>';
            endforeach;
        endif;

//        $sortiesListe = $this->managerDossiers->liste(array('dossierDateSortie' => $jour));
//        if (!empty($sortiesListe)):
//            foreach ($sortiesListe as $s):
//
//                $couleur = $s->getDossierSortieEtat() == 1 ? '#FFF' : '#b8f4ae';
//
//                $sorties[] = '<div class="progJourSortie" style="background-color:' . $couleur . '; color: #FFF;" data-dossierid="' . $s->getDossierId() . '">' .
//                        '<div style="padding:7px;">' .
//                        $s->getDossierDescriptif() .
//                        '</div>' .
//                        '</div>';
//
//            endforeach;
//        endif;

        $data = array(
//            'sorties' => $sorties,
            'affectations' => $affectations,
            'equipes' => $equipes,
            'jour' => $this->session->userdata('jour'),
            'title' => 'Journalier ' . date('d/m/Y', $this->session->userdata('jour')),
            'description' => 'Planning intervention journalier',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addDossier() {

        $this->form_validation->set_rules('addDossierId', 'ID du dossier', 'is_natural_no_zero|trim');
        $this->form_validation->set_rules('addDossierClient', 'Client', 'required|trim');
        $this->form_validation->set_rules('addDossierDescriptif', 'Descrition du dossier', 'trim');
        $this->form_validation->set_rules('addDossierDateSortie', 'Date de sortie du dossier', 'trim');
        $this->form_validation->set_rules('addDossierFab', 'Y-a-t-il de la fabrication sur ce dossier', 'in_list[1]');
        $this->form_validation->set_rules('addDossierPose', 'Y-a-t-il de la pose sur ce dossier', 'in_list[1]');
        $this->form_validation->set_rules('addDossierClos', 'Le dossier est-il clos', 'in_list[0,1]');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            if (intval($this->input->post('addDossierId')) > 0) :
                $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('addDossierId')));
                if (!$dossier) :
                    echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier ce dossier'));
                    exit;
                endif;

                $dossier->setDossierClient($this->input->post('addDossierClient'));
                $dossier->setDossierDescriptif($this->input->post('addDossierDescriptif'));
                $dossier->setDossierDateSortie($this->input->post('addDossierDateSortie') ? $this->xth->mktimeFromInputDate($this->input->post('addDossierDateSortie')) : 0);
                $dossier->setDossierFab($this->input->post('addDossierFab') ? 1 : 0);
                $dossier->setDossierPose($this->input->post('addDossierPose') ? 1 : 0);
                $dossier->setDossierPao($this->input->post('addDossierPao') ? 1 : 0);

                $this->managerDossiers->editer($dossier);
            else :
                $dataDossier = array(
                    'dossierClient' => $this->input->post('addDossierClient'),
                    'dossierDescriptif' => $this->input->post('addDossierDescriptif'),
                    'dossierDateSortie' => $this->input->post('addDossierDateSortie') ? $this->xth->mktimeFromInputDate($this->input->post('addDossierDateSortie')) : 0,
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
        endif;
    }

    public function delDossier() {
        $this->form_validation->set_rules('dossierId', 'ID du dossier', 'required|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('dossierId')));
            if (empty($dossier)) :
                echo json_encode(array('type' => 'error', 'message' => 'Dossier introuvable'));
                exit;
            else :
                /* recherche des affectations du dossier */
                $affects = $this->managerAffectations->liste(array('affectationDossierId' => $dossier->getDossierId()));
                if (!empty($affects)) :
                    foreach ($affects as $a) :
                        /* on renumérote les autres affectations de ce jour */
                        $this->renumerotation($a->getAffectationEquipeId(), $a->getAffectationDate());
                        $this->managerAffectations->delete($a);
                    endforeach;
                endif;
                /* Suppression du dossier */
                $this->managerDossiers->delete($dossier);
                echo json_encode(array('type' => 'success'));
                exit;
            endif;
        endif;
    }

    public function addAffectation() {
        if (!$this->ion_auth->is_admin()) :
            echo json_encode(array('type' => 'error', 'message' => 'Vous devez être administrateur pour ajouter une affectation'));
            exit;
        endif;

        $this->form_validation->set_rules('addAffectId', 'ID de l\'affectation', 'is_natural_no_zero');
        $this->form_validation->set_rules('addAffectDossierId', 'ID du dossier', 'is_natural_no_zero');
        $this->form_validation->set_rules('addAffectType', 'Type', 'required|in_list[1,2,3]');
        $this->form_validation->set_rules('addAffectDate', 'Date', 'required|trim');
        $this->form_validation->set_rules('addAffectNbJour', 'Nombre de jours', 'required|trim|is_natural_no_zero');
        $this->form_validation->set_rules('addAffectEquipeId', 'ID Equipe', 'required|is_natural_no_zero|trim');
        $this->form_validation->set_rules('addAffectIntervenant', 'Intervenants', 'trim');
        $this->form_validation->set_rules('addAffectCommentaire', 'Commentaire', 'trim');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('addAffectDossierId')));
            if (!$dossier) :
                echo json_encode(array('type' => 'error', 'message' => 'Dossier introuvable.'));
                exit;
            else :
                $equipe = $this->managerEquipes->getEquipeById(intval($this->input->post('addAffectEquipeId')));
                if (!$equipe) :
                    echo json_encode(array('type' => 'error', 'message' => 'Equipe introuvable.'));
                    exit;
                else :
                    if (intval($this->input->post('addAffectId')) > 0) :
                        $affect = $this->managerAffectations->getAffectationById(intval($this->input->post('addAffectId')));
                        if (!$affect) :
                            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
                            exit;
                        else :
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

                            $affect->setAffectationType(intval($this->input->post('addAffectType')));
                            $affect->setAffectationIntervenant($this->input->post('addAffectIntervenant'));
                            $affect->setAffectationCommentaire($this->input->post('addAffectCommentaire'));
                            $this->managerAffectations->editer($affect);

                            echo json_encode(array('type' => 'success'));
                            exit;
                        endif;
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
                                'affectationDossierId' => intval($this->input->post('addAffectDossierId')),
                                'affectationType' => intval($this->input->post('addAffectType')),
                                'affectationEquipeId' => intval($this->input->post('addAffectEquipeId')),
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
                endif;
            endif;
        endif;
    }

    public function getAffectation() {
        $this->form_validation->set_rules('affectationId', 'ID de l\'affectation', 'required|trim|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $affectation = $this->managerAffectations->getAffectationById(intval($this->input->post('affectationId')), 'array');
            echo json_encode(array('type' => 'success', 'affectation' => $affectation));
            exit;
        endif;
    }

    public function delAffectation() {
        $this->form_validation->set_rules('affectationId', 'ID de l\'affectation', 'required|trim|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $affectation = $this->managerAffectations->getAffectationById(intval($this->input->post('affectationId')));
            /* on renumérote les autres affectations de ce jour */
            $this->renumerotation($affectation->getAffectationEquipeId(), $affectation->getAffectationDate());

            $this->managerAffectations->delete($affectation);
            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    public function renumerotation($equipe, $date) {
        /* On recalcule les positions des autres affectations du même jour */
        $others = $this->managerAffectations->liste(array('affectationDate' => $date, 'affectationEquipeId' => $equipe));
        $num = 1;
        if ($others) :
            foreach ($others as $o) :
                $o->setAffectationPosition($num);
                $this->managerAffectations->editer($o);
                $num++;
            endforeach;
        endif;
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
        $this->form_validation->set_rules('affectationId', 'ID de l\'affectation', 'required|trim|is_natural_no_zero');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $affectation = $this->managerAffectations->getAffectationById(intval($this->input->post('affectationId')));
            if (!$affectation) :
                echo json_encode(array('type' => 'error', 'message' => 'Affectation introuvable.'));
                exit;
            else :
                if ($affectation->getAffectationDossierClos() == 0) :
                    $affectation->nextStep();
                    $this->managerAffectations->editer($affectation);

                    echo json_encode(array('type' => 'success', 'backgroundColor' => $affectation->getAffectationCouleur(), 'fontColor' => $affectation->getAffectationFontColor()));
                    exit;
                else :
                    echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier cette affectation car le dossier est clos.'));
                    exit;
                endif;
            endif;
        endif;
    }

    public function nextStepSortie() {
        $this->form_validation->set_rules('dossierId', 'ID du dossier', 'required|trim|is_natural_no_zero');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('dossierId')));
            if (!$dossier) :
                echo json_encode(array('type' => 'error', 'message' => 'Dossier introuvable.'));
                exit;
            else :
                if ($dossier->getDossierClos() == 0) :
                    $dossier->nextStepSortie();
                    $this->managerDossiers->editer($dossier);
                    echo json_encode(array('type' => 'success'));
                    exit;
                else :
                    echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier ce dossier car il est clos.'));
                    exit;
                endif;
            endif;
        endif;
    }

    /**
     *
     * @param bool $clos 0 pour les dossiers ouverts et 1 pour les dossiers clos
     */
    public function dossiers($clos = 0) {
        if (!$this->ion_auth->is_admin()) :
            redirect('ed/journalier');
            exit;
        endif;
        $data = array(
            'equipes' => $this->managerEquipes->liste(),
            'dossiers' => $this->managerDossiers->liste(array('dossierClos' => $clos)),
            'title' => 'Gestion des dossiers',
            'description' => 'Création, modification et suivi de vos dossiers.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function getDossier() {
        $this->form_validation->set_rules('dossierId', 'ID du dossier', 'required|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('dossierId')), 'array');
            if (empty($dossier)) :
                echo json_encode(array('type' => 'error', 'message' => 'Dossier introuvable'));
                exit;
            else :
                $affectationsPao = $this->managerAffectations->liste(array('affectationDossierId' => $dossier->dossierId, 'affectationType' => 3), 'affectationDate ASC', 'array');
                $affectationsFab = $this->managerAffectations->liste(array('affectationDossierId' => $dossier->dossierId, 'affectationType' => 1), 'affectationDate ASC', 'array');
                $affectationsPose = $this->managerAffectations->liste(array('affectationDossierId' => $dossier->dossierId, 'affectationType' => 2), 'affectationDate ASC', 'array');

                echo json_encode(array('type' => 'success', 'dossier' => $dossier, 'fab' => $affectationsFab, 'pose' => $affectationsPose, 'pao' => $affectationsPao));
                exit;
            endif;
        endif;
    }

    public function clotureDossier() {
        $this->form_validation->set_rules('dossierId', 'ID du dossier', 'required|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('dossierId')));
            if (empty($dossier)) :
                echo json_encode(array('type' => 'error', 'message' => 'Impossible de clôturer ce dossier'));
                exit;
            else :
                $dossier->cloture();
                $this->managerDossiers->editer($dossier);
                echo json_encode(array('type' => 'success'));
                exit;
            endif;
        endif;
    }

    public function ouvertureDossier() {
        $this->form_validation->set_rules('dossierId', 'ID du dossier', 'required|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $dossier = $this->managerDossiers->getDossierById(intval($this->input->post('dossierId')));
            if (empty($dossier)) :
                echo json_encode(array('type' => 'error', 'message' => 'Impossible d\'ouvrir ce dossier'));
                exit;
            else :
                $dossier->ouverture();
                $this->managerDossiers->editer($dossier);
                echo json_encode(array('type' => 'success'));
                exit;
            endif;
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

        $this->form_validation->set_rules('addRecurrentId', 'ID de la récurrence', 'is_natural_no_zero|trim');
        $this->form_validation->set_rules('addRecurrentCritere', 'Critère', 'required|trim');
        $this->form_validation->set_rules('addRecurrentEquipeId', 'ID de l\'équipe', 'required|is_natural_no_zero|trim');
        $this->form_validation->set_rules('addRecurrentCommentaire', 'Commentaire', 'trim');

        if (!$this->form_validation->run() || (!in_array(strtolower($this->input->post('addRecurrentCritere')), array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi')) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])$/", $this->input->post('addRecurrentCritere')) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->input->post('addRecurrentCritere')) )
        ) :
            echo json_encode(array('type' => 'error', 'message' => 'Vous devez saisir un critère de récurrence valide.'));
            exit;
        else :
            if (intval($this->input->post('addRecurrentId')) > 0) :
                $recurrent = $this->managerRecurrents->getRecurrentById(intval($this->input->post('addRecurrentId')));
                if (!$recurrent) :
                    echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier cette récurrence'));
                    exit;
                endif;

                $recurrent->setRecurrentEquipeId(intval($this->input->post('addRecurrentEquipeId')));
                $recurrent->setRecurrentCommentaire($this->input->post('addRecurrentCommentaire'));
                $recurrent->setRecurrentCritere(strtolower($this->input->post('addRecurrentCritere')));

                $this->managerRecurrents->editer($recurrent);
            else :
                $dataRecurrent = array(
                    'recurrentEquipeId' => intval($this->input->post('addRecurrentEquipeId')),
                    'recurrentCommentaire' => $this->input->post('addRecurrentCommentaire'),
                    'recurrentCritere' => strtolower($this->input->post('addRecurrentCritere'))
                );

                $recurrent = new Recurrent($dataRecurrent);
                $this->managerRecurrents->ajouter($recurrent);
            endif;

            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    public function getRecurrent() {
        $this->form_validation->set_rules('recurrentId', 'ID de la récurrence', 'required|trim|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $recurrent = $this->managerRecurrents->getRecurrentById(intval($this->input->post('recurrentId')), 'array');
            echo json_encode(array('type' => 'success', 'recurrent' => $recurrent));
            exit;
        endif;
    }

    public function delRecurrent() {
        $this->form_validation->set_rules('recurrentId', 'ID de la récurrence', 'required|trim|is_natural_no_zero');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $recurrent = $this->managerRecurrents->getRecurrentById(intval($this->input->post('recurrentId')));

            $this->managerRecurrents->delete($recurrent);
            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    public function noway() {
        $this->output->set_status_header('404');
        $data = array(
            'title' => 'Page inexistante',
            'description' => 'Vous tentez d\'accéder à une mpage qui n\'existe pas ouy plus',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->ion_auth->logout();
        redirect('secure/tryLogin');
        exit;
    }

}
