<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Documents extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;

        // Include the main TCPDF library (search for installation path).
        require_once('application/libraries/tcpdf/tcpdf.php');

        $this->parametres = $this->db->select('*')->from('parametres')->get()->result();
        $this->piedPage1 = $this->parametres[0]->valeur . ', ' . $this->parametres[1]->valeur . ', ' . $this->parametres[3]->valeur . ' ' . $this->parametres[4]->valeur . ' - Tel: ' . $this->parametres[5]->valeur . ' - Email: ' . $this->parametres[6]->valeur . ' - APE: ' . $this->parametres[8]->valeur;
        $this->piedPage2 = '<br>Siret: ' . $this->parametres[7]->valeur . ' - APE: ' . $this->parametres[8]->valeur . ' - N° TVA Intracommunautaire : ' . $this->parametres[9]->valeur;
    }

    public function editionDevis($affaireId = null) {

        if (!$affaireId):
            redirect('ventes/noway');
            exit;
        endif;

        $affaire = $this->managerAffaires->getAffaireById($affaireId);
        $affaire->hydrateClients();
        foreach ($affaire->getAffaireClients() as $c):
            if ($c->getClientPrincipal() == 1):
                $client = $c;
                continue;
            endif;
        endforeach;
        $articles = $this->managerAffaireArticles->liste(array('affaireArticleAffaireId' => $affaire->getAffaireId()));

        $data = array(
            'affaire' => $affaire,
            'client' => $client,
            'articles' => $articles,
            'title' => 'Devis',
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->parametres[0]->valeur);
        $pdf->SetTitle('Devis ');
        $pdf->SetSubject('Devis ');
        //$pdf->SetKeywords('Devis');

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('facture.pdf', 'FI');
    }

    public function editionFacture($factureId = null) {

        if (!$factureId):
            redirect('ventes/noway');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->hydrateClient();
        $facture->hydrateLignes();

        $data = array(
            'facture' => $facture,
            'title' => 'Facture ' . $facture->getFactureId(),
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->parametres[0]->valeur);
        $pdf->SetTitle('Facture ' . $facture->getFactureId());
        $pdf->SetSubject('Facture ' . $facture->getFactureId());

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Facture ' . $facture->getFactureId() . '.pdf', 'FI');
    }

//    public function esquisseFacture($factureId = null) {
//
//        if (!$factureId):
//            redirect('ventes/noway');
//            exit;
//        endif;
//
//        $facture = $this->managerFactures->getFactureById($factureId);
//
//        /* Si la facture est déjà générée, on redirige vers l'impression de la facture définitive */
//        if ($facture->getFactureNum() || $facture->getFactureDate()):
//            redirect('documents/editionFacture/' . $facture->getFactureId());
//            exit;
//        endif;
//
//        $facture->hydrateClient();
//        $facture->hydrateLignes();
//
//        $data = array(
//            'facture' => $facture,
//            'title' => 'Esquisse de facture',
//            'description' => '',
//            'keywords' => '',
//            'content' => $this->viewFolder . __FUNCTION__
//        );
//        $this->load->view('template/contentDocuments', $data);
//
//        // Extend the TCPDF class to create custom Header and Footer
//        $html = $this->output->get_output();
//
//        // create new PDF document
//        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
//        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetAuthor($this->parametres[0]->valeur);
//        $pdf->SetTitle('Provisoire ');
//        $pdf->SetSubject('Provisoire ');
//        //$pdf->SetKeywords('Devis');
//
//        $pdf->SetMargins(5, 5, 5);
//        // set auto page breaks
//        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
//        $pdf->AddPage();
//
//        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
//
//        $pdf->Output('Provisoire.pdf', 'FI');
//    }
}
