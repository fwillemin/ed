<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ventes extends CI_Controller {

    const tauxTVA = 20;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) :
            redirect('secure/login');
        endif;

        //$this->affaireError = ''; /* Erreurs lors de l'enregistrement d'une affaire */
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance de l'article dans la bdd
     *
     * @param int $id ID de l'article
     * @return boolean
     */
    public function existArticle($articleId) {
        $this->form_validation->set_message('existArticle', 'Cet article est introuvable.');
        if ($this->managerArticles->count(array('articleId' => $articleId)) == 1 || !$articleId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'une option dans la bdd
     *
     * @param int $id ID de l'option
     * @return boolean
     */
    public function existOption($optionId) {
        $this->form_validation->set_message('existOption', 'Cette option est introuvable.');
        if ($this->managerOptions->count(array('optionId' => $optionId)) == 1 || !$optionId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du client dans la bdd
     *
     * @param int $clientId ID du client
     * @return boolean TRUE si le client existe
     */
    public function existClient($clientId) {
        $this->form_validation->set_message('existClient', 'Ce client est introuvable.');
        if ($this->managerClients->count(array('clientId' => $clientId)) == 1 || !$clientId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'une affaire dans la bdd
     *
     * @param int $affaireId ID de l'affaire
     * @return boolean TRUE si l'affaire existe
     */
    public function existAffaire($affaireId) {
        $this->form_validation->set_message('existAffaire', 'Cette affaire est introuvable.');
        if ($this->managerAffaires->count(array('affaireId' => $affaireId)) == 1 || !$affaireId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Initialisation d'une affaire (client, articles, options, ....)
     * @param integer $affaireId ID de l'affaire
     */
    public function reloadAffaire($affaireId = null) {

        if ($affaireId && $this->existAffaire($affaireId)):

            $affaire = $this->managerAffaires->getAffaireById($affaireId);
            $affaire->hydrateClients('array');

            $sessionClients = array();
            foreach ($affaire->getAffaireClients() as $c):
                $sessionClients[] = $c;
            endforeach;

            $dataSession = array(
                'affaireId' => $affaire->getAffaireId(),
                'affaireDate' => $affaire->getAffaireDate(),
                'affaireType' => $affaire->getAffaireType(),
                'affaireObjet' => $affaire->getAffaireObjet(),
                'affairePAO' => $affaire->getAffairePAO(),
                'affaireFabrication' => $affaire->getAffaireFabrication(),
                'affairePose' => $affaire->getAffairePose(),
                'affaireClients' => $sessionClients
            );

            $this->session->set_userdata($dataSession);

            /* Chargement des Articles et des Options de l'affaire */
            $this->cart->destroy();

            $affaireArticles = $this->managerAffaireArticles->liste(array('affaireArticleAffaireId' => $affaire->getAffaireId()));
            if ($affaireArticles):
                foreach ($affaireArticles as $article):
                    $article->hydrateAffaireOptions();
                    $this->reloadVenteArticle($article);
                endforeach;
            endif;

        endif;
        $this->session->set_userdata('pleaseSave', 0);
        redirect('ventes/concepteur');
        exit;
    }

    public function resetConcepteur() {

        $dataSession = array('affaireId', 'affaireClientId', 'affaireExonerationTVA', 'affaireDate');
        $this->session->unset_userdata($dataSession);
        $this->cart->destroy();

        $this->session->set_userdata(
                array(
                    'pleaseSave' => 0,
                    'affaireType' => 1,
                    'affaireClients' => array(),
                    'affaireObjet' => '',
                    'affairePAO' => 0,
                    'affaireFabrication' => 0,
                    'affairePose' => 0
                )
        );
        redirect('ventes/concepteur');
        exit;
    }

    private function getAffaireTotaux() {
        return array(
            'affaireTotalHT' => number_format($this->cart->total(), 2, ',', ' '),
            'affaireTotalTVA' => number_format(round($this->cart->total() * self::tauxTVA / 100, 2), 2, ',', ' '),
            'affaireTotalTTC' => number_format($this->cart->total() + round($this->cart->total() * self::tauxTVA / 100, 2), 2, ',', ' ')
        );
    }

    public function listeAffaires() {
        $data = array(
            'title' => 'Affaires',
            'description' => 'Liste des Affaires',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function concepteur() {

        if ($this->session->userdata('affaireId')):
            $affaire = $this->managerAffaires->getAffaireById($this->session->userdata('affaireId'));
            $affaire->hydrateClients();
            $affaire->hydrateFactures();
            $affaire->hydrateReglements();
            if ($affaire->getAffaireReglements()):
                foreach ($affaire->getAffaireReglements() as $r):
                    $r->hydrateClient();
                    $r->hydrateHistorique();
                endforeach;
            endif;
        else:
            $affaire = array();
        endif;

        $data = array(
            'affaire' => $affaire,
            'articles' => $this->managerArticles->liste(array('articleDelete' => 0), 'f.familleNom, a.articleDesignation ASC', 'object', 'right'),
            'composants' => $this->managerComposants->liste(array('composantDelete' => 0), 'f.familleNom ASC'),
            'title' => 'Conception d\'un devis',
            'description' => 'Création d\'un dossier',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    /**
     * Retourne le nombre de présence de cet article dans le panier
     * @param integer $articleId ID de l'article
     * @return integer Nombre de fois où l'article se trouve dans le panier
     */
    private function getNbOccurence($articleId) {
        $nb = 0;
        if (!empty($this->cart->contents())):
            foreach ($this->cart->contents() as $item):
                if (explode('-', $item['id'])[0] == $articleId):
                    $nb++;
                endif;
            endforeach;
        endif;
        return $nb;
    }

    public function addVenteArticle() {

        if ($this->form_validation->run('addVenteArticle')):

            $article = $this->managerArticles->getArticleById($this->input->post('articleId'));

            /* Recherche du meme article déjà présent dans le panier */
            $compteur = $this->getNbOccurence($article->getArticleId()) + 1;

            /* On créé un array avec les composants de l'article */
            foreach ((array) $article->getArticleCompositions() as $c):
                $c->hydrateOption();
                $c->hydrateComposant();
                $compositions[] = array(
                    'affaireOptionId' => null,
                    'optionId' => $c->getCompositionOption()->getOptionId(),
                    'optionUnite' => $c->getCompositionComposant()->getComposantUnite()->getUniteSymbole(),
                    'name' => $c->getCompositionComposant()->getComposantDesignation() . ' ' . $c->getCompositionOption()->getOptionNom(),
                    'qte' => $c->getCompositionQte(),
                    'prix' => $c->getCompositionOption()->getOptionHT(),
                    'originel' => 1 /* Composant issu de l'article orignial dans la bdd */
                );
            endforeach;


            $data = array(
                'id' => $article->getArticleId() . '-' . $compteur,
                'name' => $article->getArticleDesignation(),
                'description' => $article->getArticleDescription(),
                'qty' => 1,
                'price' => $article->getArticleHT(), /* Prix vendu - la remise eventuelle */
                'prixVendu' => $article->getArticleHT(), /* Prix de vente effectif dans le ligne de l'article. Soit "articleHT" soit un prix forcé */
                'articleHT' => $article->getArticleHT(), /* Prix public HT de l'article, somme des options */
                'remise' => 0,
                'affaireArticleId' => null,
                'composants' => $compositions
            );

            $this->cart->insert($data);

            $this->session->set_userdata('pleaseSave', 1);
            echo json_encode(array('type' => 'success'));

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

    private function reloadVenteArticle(AffaireArticle $affaireArticle) {

        $article = $this->managerArticles->getArticleById($affaireArticle->getAffaireArticleArticleId());

        /* Recherche du meme article déjà présent dans le panier */
        $compteur = $this->getNbOccurence($article->getArticleId()) + 1;
        $sommeOptions = 0; /* On recalcule la somme des options qui contituent le prix de vente NON FORCE */

        /* On créé un array avec les composants/options de l'article */
        $options = array();
        foreach ((array) $affaireArticle->getAffaireArticleOptions() as $affaireOption):

            $composant = $this->managerComposants->getComposantById($affaireOption->getAffaireOptionComposantId());
            $option = $this->managerOptions->getOptionById($affaireOption->getAffaireOptionOptionId());
            //$c->hydrateOption();
            //$c->hydrateComposant();
            $options[] = array(
                'affaireOptionId' => $affaireOption->getAffaireOptionId(),
                'optionId' => $option->getOptionId(),
                'optionUnite' => $composant->getComposantUnite()->getUniteSymbole(),
                'name' => $composant->getComposantDesignation() . ' ' . $option->getOptionNom(),
                'qte' => $affaireOption->getAffaireOptionQte(),
                'prix' => $affaireOption->getAffaireOptionPU(),
                'originel' => $affaireOption->getAffaireOptionOriginel() /* Composant issu de l'article orignial dans la bdd */
            );
            $sommeOptions += round($affaireOption->getAffaireOptionQte() * $affaireOption->getAffaireOptionPU(), 2);
        endforeach;


        $data = array(
            'id' => $article->getArticleId() . '-' . $compteur,
            'name' => $affaireArticle->getAffaireArticleDesignation(),
            'description' => $affaireArticle->getAffaireArticleDescription(),
            'qty' => $affaireArticle->getAffaireArticleQte(),
            'price' => $affaireArticle->getAffaireArticlePU(), /* Prix vendu - la remise eventuelle */
            'prixVendu' => $affaireArticle->getAffaireArticleTarif(), /* Prix de vente effectif dans le ligne de l'article. Soit "articleHT" soit un prix forcé */
            'articleHT' => $sommeOptions, /* Prix public HT de l'article, somme des options */
            'remise' => $affaireArticle->getAffaireArticleRemise(),
            'affaireArticleId' => $affaireArticle->getAffaireArticleId(),
            'composants' => $options
        );

        $this->cart->insert($data);
        return;
    }

    public function addComposantToArticle() {

        if ($this->form_validation->run('addComposantToArticle')):
            $option = $this->managerOptions->getOptionById($this->input->post('addComposantOptionId'));
            $option->hydrateComposant();

            $item = $this->cart->get_item($this->input->post('addComposantRowId'));

            foreach ($item['composants'] as $c):
                if ($c['optionId'] == $option->getOptionId()):
                    echo json_encode(array('type' => 'error', 'message' => 'Cette option est déjà présente dans la composition de cet article !'));
                    exit;
                endif;
            endforeach;

            $newComposant = array(
                'affaireOptionId' => null,
                'optionId' => $option->getOptionId(),
                'optionUnite' => $option->getOptionComposant()->getComposantUnite()->getUniteSymbole(),
                'name' => $option->getOptionComposant()->getComposantDesignation() . ' ' . $option->getOptionNom(),
                'qte' => $this->input->post('addComposantQte'),
                'prix' => $option->getOptionHT(),
                'originel' => 0
            );

            $item['composants'][] = $newComposant;

            $this->cart->update(array('rowid' => $item['rowid'], 'composants' => $item['composants']));

            if ($this->calculNouveauxPrixItem($this->cart->get_item($item['rowid']))):
                $this->session->set_userdata('pleaseSave', 1);
                echo json_encode(array('type' => 'success'));
                exit;
            endif;

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function delArticle() {
        $this->cart->remove($this->input->post('rowId'));
        $this->session->set_userdata('pleaseSave', 1);
    }

    public function delComposant() {

        $item = $this->cart->get_item($this->input->post('rowId'));

        foreach ($item['composants'] as $c):
            if ($c['optionId'] == $this->input->post('optionId')):
                unset($item['composants'][$i]);
                continue;
            endif;
            $i++;
        endforeach;

        $this->cart->update(array('rowid' => $item['rowid'], 'composants' => $item['composants']));

        if ($this->calculNouveauxPrixItem($this->cart->get_item($item['rowid']))):
            $this->session->set_userdata('pleaseSave', 1);
            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    /**
     * Mise à jour de tous les prix d'un item de la classe Cart
     * @param array $item Item de la classe Cart
     */
    public function calculNouveauxPrixItem($item) {

        $nouveauPrixArticleHT = 0;
        foreach ($item['composants'] as $option):
            $nouveauPrixArticleHT += round($option['prix'] * $option['qte'], 2);
        endforeach;

        if ($item['articleHT'] == $item['prixVendu']):
            $nouveauPrixVendu = $nouveauPrixArticleHT;
        else:
            $nouveauPrixVendu = $item['prixVendu'];
        endif;

        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . $nouveauPrixVendu);
        $nouveauPrixFinal = $this->calculPrixItem($nouveauPrixVendu, $item['remise']);

        $data = array(
            'rowid' => $item['rowid'],
            'articleHT' => $nouveauPrixArticleHT,
            'prixVendu' => $nouveauPrixVendu,
            'price' => $nouveauPrixFinal
        );

        $this->cart->update($data);
        return true;
    }

    /**
     * Retoure le prix de l'item correspondant au prix Vendu - remise
     * @param array $item Item de la classe Cart
     * @return float Prix de vente réél de l'item
     */
    private function calculPrixItem($prixVendu, $remise) {

        if ($remise > 0):
            return round($prixVendu * (100 - $remise ) / 100, 2);
        else:
            return $prixVendu;
        endif;
    }

    public function autoSaveArticle() {

        if ($this->form_validation->run('autoSave')):

            $data = array(
                'rowid' => $this->input->post('rowId'),
                $this->input->post('param') => $this->input->post('valeur')
            );
            $this->cart->update($data);

            /* Mise à jour des données de l'item */
            $item = $this->cart->get_item($this->input->post('rowId'));

            $this->cart->update(array('rowid' => $item['rowid'], 'price' => $this->calculPrixItem($item['prixVendu'], $item['remise'])));
            $this->session->set_userdata('pleaseSave', 1);
            echo json_encode(array('type' => 'success', 'newPrice' => number_format($this->cart->get_item($item['rowid'])['subtotal'], 2, ',', ''), 'totaux' => $this->getAffaireTotaux()));
            exit;
        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    /**
     * Change la quantité d'une option dans un article
     */
    public function changeOptionQte() {

        $item = $this->cart->get_item($this->input->post('rowid'));
        foreach ($item['composants'] as $o):
            if ($o['optionId'] == $this->input->post('optionId')):
                $o['qte'] = $this->input->post('qte');
            endif;
            $newListeOptions[] = $o;
        endforeach;

        $nouveauPrixArticleHT = $this->majPrixArticleHT($newListeOptions);

        $data = array(
            'rowid' => $this->input->post('rowid'),
            'articleHT' => $nouveauPrixArticleHT,
            'composants' => $newListeOptions
        );

        if ($item['articleHT'] == $item['prixVendu']):
            $data['prixVendu'] = $nouveauPrixArticleHT;
            $data['price'] = $this->calculPrixItem($nouveauPrixArticleHT, $item['remise']);
        else:
            $data['prixVendu'] = $item['prixVendu'];
            $data['price'] = $item['price'];
        endif;
        //log_message('error', __CLASS__.'/'.__FUNCTION__.print_r($data, 1));
        $this->cart->update($data);

        $this->session->set_userdata('pleaseSave', 1);
        echo json_encode(array('type' => 'success',
            'prixBase' => number_format($nouveauPrixArticleHT, 2, ',', ' '),
            'prixVendu' => number_format($data['prixVendu'], 2, ',', ' '),
            'prixTotal' => number_format($data['price'] * $item['qty'], 2, ',', ' '),
            'totaux' => $this->getAffaireTotaux()
                )
        );
        $this->session->set_userdata('pleaseSave', 1);
        exit;
    }

    /**
     * Retourne le prix d'un article correspondant à la somme des ses options
     * @param array $listeOptions Liste des options
     * @return float Prix de l'article
     */
    private function majPrixArticleHT($listeOptions) {

        $prixArticleHT = 0;
        foreach ($listeOptions as $option):
            $prixArticleHT += round($option['prix'] * $option['qte'], 2);
        endforeach;
        return $prixArticleHT;
    }

    public function delPanier() {
        $this->cart->destroy();
        $this->session->set_userdata('pleaseSave', 1);
    }

    /**
     * Ajoute un client à la vente
     */
    public function venteAddClient() {

        if (!$this->form_validation->run('selectionClient')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :

            $client = $this->managerClients->getClientById($this->input->post('clientId'), 'array');
            $clientsAffaire = $this->session->userdata('affaireClients');
            if (empty($clientsAffaire)):
                $principal = 1;
            else:
                foreach ($clientsAffaire as $c):
                    if ($c->clientId == $client->clientId):
                        echo json_encode(array('type' => 'error', 'message' => 'Ce client est déjà lié à cette affaire'));
                        exit;
                    endif;
                endforeach;
                $principal = 0;
            endif;
            $client->principal = $principal;
            $clientsAffaire[] = $client;

            $this->session->set_userdata('affaireClients', $clientsAffaire);
            $this->session->set_userdata('pleaseSave', 1);
            echo json_encode(array('type' => 'success'));
            exit;

        endif;
    }

    /**
     * Change le type d'affaire
     */
    public function modAffaireType() {

        if (in_array($this->input->post('affaireType'), array(1, 2))):
            $this->session->set_userdata('affaireType', $this->input->post('affaireType'));
            $this->session->set_userdata('pleaseSave', 1);
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Type invalide'));
        endif;
        exit;
    }

    /**
     * Change l'objet de l'affaire
     */
    public function modAffaireObjet() {
        $this->session->set_userdata('affaireObjet', $this->input->post('affaireObjet'));
        $this->session->set_userdata('pleaseSave', 1);
        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function devenirClientPrincipal() {

        foreach ($this->session->userdata('affaireClients') as $c):
            if ($c->clientId == $this->input->post('clientId')):
                $c->principal = 1;
            else:
                $c->principal = 0;
            endif;
            $clients[] = $c;
        endforeach;
        $this->session->set_userdata('affaireClients', $clients);
        $this->session->set_userdata('pleaseSave', 1);

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function deleteAffaireClient() {

        if ($this->isClientAffaireSupprimable($this->input->post('clientId'), $this->session->userdata('affaireId'))):

            foreach ($this->session->userdata('affaireClients') as $c):
                if ($c->clientId != $this->input->post('clientId')):
                    $clients[] = $c;
                endif;
            endforeach;
            $this->session->set_userdata('affaireClients', $clients);
            $this->session->set_userdata('pleaseSave', 1);

            echo json_encode(array('type' => 'success'));

        else:

            echo json_encode(array('type' => 'error', 'message' => 'Impossible de supprimer un client qui a effectué un réglement ou qui a été facturé.'));
            exit;

        endif;
        exit;
    }

    private function isClientAffaireSupprimable($clientId, $affaireId) {

        if ($this->managerReglements->liste(array('reglementClientId' => $clientId, 'reglementAffaireId' => $affaireId)) ||
                $this->managerFactures->liste(array('factureClientId' => $clientId, 'factureAffaireId' => $affaireId))):
            return false;
        else:
            return true;
        endif;
    }

    public function modOptionPlanification() {
        $this->session->set_userdata($this->input->post('option'), $this->input->post('valeur'));
        $this->session->set_userdata('pleaseSave', 1);
        echo json_encode(array('type' => 'success'));
        exit;
    }

}
