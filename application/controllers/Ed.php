<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ed extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);

        if (!$this->ion_auth->logged_in()) :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Non connecté');
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

    /**
     * Retourne une selection des elements de la BDD qui sont à planifier (Dossiers et Affaires)
     */
    private function elementsAPlanifier() {

        $affaires = $this->managerAffaires->liste(array('affaireCloture' => 0, 'affaireCommandeId != ' => 'NULL'), 'affaireCommandeDate ASC');
        if ($affaires):
            foreach ($affaires as $affaire):
                $affaire->hydrateClients();
                $affaire->hydrateAffectations();
            endforeach;
        endif;
        $dossiers = $this->managerDossiers->liste(array('dossierClos' => 0));
        if ($dossiers):
            foreach ($dossiers as $dossier):
                $dossier->hydrateAffectations();
            endforeach;
        endif;

        return array('affaires' => $affaires, 'dossiers' => $dossiers);
    }

    public function planification() {

        $elements = $this->elementsAPlanifier();

        $data = array(
            'postes' => $this->postes,
            'dossiers' => $elements['dossiers'],
            'affaires' => $elements['affaires'],
            'title' => 'Planification',
            'description' => 'Planification des affaires et des dossiers',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
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

        foreach ($this->postes as $id => $poste) :
            for ($i = 0; $i < 5; $i++) :
                $d = date('d', $premierJourSemaine + ($i * 86400));
                $divs[$id . '-' . $d] = array();
            endfor;
        endforeach;


        /* Selection des affectations */
        $affectations = $this->managerAffectations->liste(array('affectationDate >= ' => $premierJourSemaine, 'affectationDate <= ' => $dernierJourSemaine));
        if (!empty($affectations)) :
            foreach ($affectations as $a) :
                $a->hydrateParent();
                $divs[$a->getAffectationType() . '-' . date('d', $a->getAffectationDate())][] = $this->affectationHebdoCodeHTML($a);
            endforeach;
        endif;

        /* Les récurrents */
        for ($i = $premierJourSemaine; $i < $dernierJourSemaine; $i += 86400) :
            $recur = $this->managerRecurrents->getJour($i);
            if (!empty($recur)) :
                foreach ($recur as $r) :
                    $divs[$r->getRecurrentType() . '-' . date('d', $i)][] = $this->recurrentHebdoCodeHTML($r);
                endforeach;
            endif;
        endfor;

        $elements = $this->elementsAPlanifier();

        $data = array(
            'postes' => $this->postes,
            'annee' => $annee,
            'semaine' => $semaine,
            'premierJourSemaine' => $premierJourSemaine,
            'dernierJourSemaine' => $dernierJourSemaine,
            'divs' => $divs,
            'dossiers' => $elements['dossiers'],
            'affaires' => $elements['affaires'],
            'title' => 'Hebdomadaire',
            'description' => 'Planning hebdomadaire',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    /**
     * Génère le code HTML de l'affectation pour le planning journalier
     * @param Affectation $affectation
     */
    private function affectationJournalierCodeHTML(Affectation $affectation) {

        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($affectation, 1));

        if ($affectation->getAffectationParentClos() == 1) :
            $classAffect = "progJourClos";
        elseif ($affectation->getAffectationEtat() == 3) :
            $classAffect = "progJourTermine";
        else :
            $classAffect = "progJour";
        endif;

        $code = '<div class="' . $classAffect . '" style="background-color:' . $affectation->getAffectationCouleur() . '; color:' . $affectation->getAffectationFontColor() . ';" data-affectId="' . $affectation->getAffectationId() . '">'
                . '<div class="intervenantJournalier" style="">Intervenants : <strong>'
                . $affectation->getAffectationIntervenant()
                . '</strong></div>'
                . '<div style="padding:7px;">'
                . '<center><span style="font-size:12px; font-weight:bold;">' . $affectation->getAffectationClient() . '</span></center>'
                . 'Objet : ';

        if ($affectation->getAffectationAffaire()):
            $code .= $affectation->getAffectationAffaire()->getAffaireObjet();
        else:
            $affectation->getAffectationDossier()->getDossierDescriptif();
        endif;

        $code .= $affectation->getAffectationCommentaire() ? 'Obs : ' . $affectation->getAffectationCommentaire() : '';
        $code .= '</div></div>';
        return $code;
    }

    /**
     * Génère le code HTML d'un recurrent pour le planning journalier
     * @param Affectation $affectation
     */
    private function recurrentJournalierCodeHTML(Recurrent $recurrent) {
        return '<div class="progJourRecurrent" style="background-color: yellow; color: #000;" data-affectid="0">'
                . '<div class="intervenantJournalier" style="">Intervenants :<br><strong></strong></div>'
                . '<div style="padding:7px;">'
                . '<center><span style="font-size:15px; font-weight:bold;">Enseigne Diffusion</span></center>'
                . $recurrent->getRecurrentCommentaire()
                . '</div></div>';
    }

    /**
     * Génère le code HTML de l'affectation pour le planning hebdomadaire
     * @param Affectation $affectation
     */
    private function affectationHebdoCodeHTML(Affectation $affectation) {

        return '<div class="progHebdo tooltiped" style="background-color:' . $affectation->getAffectationCouleur() . '; color:' . $affectation->getAffectationFontColor() . ';" data-affectId="' . $affectation->getAffectationId() . '" data-placement="top" title="' . $affectation->getAffectationCommentaire() . '"'
                . ' data-client="' . $affectation->getAffectationClient() . '">'
                . '<div class="btnModAffect" style="padding:7px; text-align: center; font-weight: bold; position:relative;" data-client="' . $affectation->getAffectationClient() . '"  data-objet="' . $affectation->getAffectationDescriptif() . '">'
                . '<button class="btn btn-default btn-xs btnHebdoNext" style="position:absolute; top:0px; right:0px;" data-affectid="' . $affectation->getAffectationId() . '"><i class="glyphicon glyphicon-repeat"></i></button>'
                . $affectation->getAffectationClient()
                . '<br><span style="font-weight: normal; font-size:10px; font-style: italic;">' . $affectation->getAffectationDescriptif() . '</span>'
                . '</div>'
                . '<div class="intervenant">Intervenants : <strong>'
                . $affectation->getAffectationIntervenant()
                . '</strong></div></div>';
    }

    /**
     * Génère le code HTML d'un recurrent pour le planning hebdomadaire
     * @param Affectation $affectation
     */
    private function recurrentHebdoCodeHTML(Recurrent $recurrent) {
        return '<div class="progHebdo" style="background-color: yellow; color: #000;" data-affectId="0">'
                . '<div style="padding:7px; text-align: center; font-weight: bold;">' . $recurrent->getRecurrentCommentaire() . '</div>'
                . '</div>';
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

        foreach ($this->postes as $id => $poste) :
            $affectations[$id] = array();
        endforeach;

        /* Selection des affectations */
        $affectationsListe = $this->managerAffectations->liste(array('affectationDate' => $this->session->userdata('jour')));
        if (!empty($affectationsListe)) :
            foreach ($affectationsListe as $a) :
                $a->hydrateParent();
                $affectations[$a->getAffectationType()][] = $this->affectationJournalierCodeHTML($a);
            endforeach;
        endif;

        $recurrentsListe = $this->managerRecurrents->getJour($this->session->userdata('jour'));
        if (!empty($recurrentsListe)) :
            foreach ($recurrentsListe as $r) :
                $affectations[$r->getRecurrentType()][] = $this->recurrentJournalierCodeHTML($r);
            endforeach;
        endif;

        $elements = $this->elementsAPlanifier();

        $data = array(
            'postes' => $this->postes,
            'dossiers' => $elements['dossiers'],
            'affaires' => $elements['affaires'],
            'affectations' => $affectations,
            'jour' => $this->session->userdata('jour'),
            'title' => 'Journalier ' . date('d/m/Y', $this->session->userdata('jour')),
            'description' => 'Planning intervention journalier',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
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
//        $this->session->sess_destroy();
        $this->ion_auth->logout();
        redirect('secure/login');
        exit;
    }

}
