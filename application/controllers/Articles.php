<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Articles extends CI_Controller {

    const TVA = 0.2;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) :
            redirect('secure/login');
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du composant dans la bdd
     *
     * @param int $id ID du composant
     * @return boolean TRUE si le composant exist
     */
    public function existComposant($composantId) {
        $this->form_validation->set_message('existComposant', 'Ce composant est introuvable.');
        if ($this->managerComposants->count(array('composantId' => $composantId)) == 1 || !$composantId) :
            return true;
        else :
            return false;
        endif;
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
     * Fonction pour from_validation qui vérifie l'existance de l'option dans la bdd
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
     * Fonction pour from_validation qui vérifie l'existance de l'option dans la bdd
     *
     * @param int $id ID de l'option
     * @return boolean
     */
    public function valideOptionListe($liste) {
        log_message('error', __CLASS__ . '/' . __FUNCTION__ . print_r($liste, 1));
        $this->form_validation->set_message('existOption', 'Toutes les options ne sont pas valides');
        $etat = true;
        foreach ($liste as $key => $optionId):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . $optionId);
//            if ( $this->managerOptions->count( array('optionId' => $optionId) ) =! 1 ) :
//                $etat = false;
//                continue;
//            endif;
        endforeach;
        return $etat;
    }

    /**
     * Fonction pour from_validation qui vérifie la famille de l'option dans la bdd
     * @param int $id ID de la famille
     * @return boolean
     */
    public function existFamille($familleId) {
        $this->form_validation->set_message('existFamille', 'Cette famille est introuvable.');
        if ($this->managerFamilles->count(array('familleId' => $familleId)) == 1 || !$familleId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance de la composition dans la bdd
     *
     * @param int $id ID de la composition
     * @return boolean
     */
    public function existComposition($compositionId) {
        $this->form_validation->set_message('existComposition', 'Cette composition est introuvable.');
        if ($this->managerCompositions->count(array('compositionId' => $compositionId)) == 1 || !$compositionId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * calcule et met à jour les prix d'achat et de vente dans la bdd
     * @param Article $article Article à mettre à jour
     */
    private function majArticlePrix(Article $article) {
        $article->updatePrice();
        $this->managerArticles->editer($article);
    }

    private function addOption($reference, $nom, Composant $composant, $prixCatalogue, $remise, $prixAchatNet, $coefficient, $active = 1) {

        //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . $prixAchatNet);
        $dataOption = array(
            'optionReference' => strtoupper($reference),
            'optionComposantId' => $composant->getComposantId(),
            'optionNom' => strtoupper($nom),
            'optionPrixCatalogue' => $prixCatalogue,
            'optionCoefficient' => $coefficient,
            'optionPrixAchat' => $prixAchatNet,
            'optionRemise' => $remise,
            'optionActive' => $active ? 1 : 0
        );

        $option = new Option($dataOption);
        $this->managerOptions->ajouter($option);
    }

    private function addComposition($articleId, $options, $qte) {

        foreach ($options as $key => $optionId):
            $option = $this->managerOptions->getOptionById($optionId);

            $dataComposition = array(
                'compositionArticleId' => $articleId,
                'compositionOptionId' => $option->getOptionId(),
                'compositionComposantId' => $option->getOptionComposantId(),
                'compositionQte' => $qte
            );

            $composition = new Composition($dataComposition);
            $this->managerCompositions->ajouter($composition);
        endforeach;

        /* Mise à jour de la valeur de l'article */
        $article = $this->managerArticles->getArticleById($articleId);
        $this->majArticlePrix($article);
    }

    private function addFamille($nom) {

        $dataFamille = array(
            'familleNom' => strtoupper($nom)
        );

        $famille = new Famille($dataFamille);
        $this->managerFamilles->ajouter($famille);
    }

    public function index() {
        redirect('clients/liste');
        exit;
    }

    /**
     * Les composants
     */
    public function getAllComposants() {
        echo json_encode(
                $this->managerComposants->liste(
                        array('composantDelete' => 0), 'c.composantDesignation', 'array'
                )
        );
    }

    public function getAllArticles() {
        echo json_encode($this->managerArticles->liste(array('articleDelete' => 0), 'a.articleDesignation', 'array'));
    }

    public function composantsListe() {

        $data = array(
            'unites' => $this->managerUnites->liste(),
            'familles' => $this->managerFamilles->liste(),
            'title' => 'Liste des composants',
            'description' => 'Gérer les composants de votre système',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function articlesListe() {

        $data = array(
            'familles' => $this->managerFamilles->liste(),
            'title' => 'Liste des articles',
            'description' => 'Gérer les articles de votre système',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    private function modComposant($composantId, $designation, $uniteId, $type, $familleId) {

        $composant = $this->managerComposants->getComposantById($composantId);

        $composant->setComposantFamilleId($familleId);
        $composant->setComposantDesignation(strtoupper($designation));
        $composant->setComposantUniteId($uniteId);
        $composant->setComposantType($type);

        $this->managerComposants->editer($composant);
        return true;
    }

    private function modComposition($compositionId, $qte) {

        $composition = $this->managerCompositions->getCompositionById($compositionId);

        $composition->setCompositionQte($qte);

        $this->managerCompositions->editer($composition);
        /* Mise à jour de la valeur de l'article */
        $article = $this->managerArticles->getArticleById($composition->getCompositionArticleId());
        $this->majArticlePrix($article);

        return true;
    }

    private function modFamille($familleId, $nom) {

        $famille = $this->managerFamilles->getFamilleById($familleId);

        $famille->setFamilleNom(strtoupper($nom));

        $this->managerFamilles->editer($famille);
        return true;
    }

    private function modArticle($articleId, $designation, $description, $familleId = NULL) {

        $article = $this->managerArticles->getArticleById($articleId);
        $article->setArticleFamilleId($familleId);
        $article->setArticleDesignation(strtoupper($designation));
        $article->setArticleDescription($description);

        $this->managerArticles->editer($article);
        return true;
    }

    private function modOption($optionId, $reference, $nom, $prixCatalogue, $remise, $prixAchatNet, $coefficient, $active) {

        $option = $this->managerOptions->getOptionById($optionId, 'object', '01');
        $option->setOptionReference(strtoupper($reference));
        $option->setOptionActive($active ? 1 : 0);
        $option->setOptionNom(strtoupper($nom));
        $option->setOptionPrixCatalogue($prixCatalogue);
        $option->setOptionRemise($remise);
        $option->setOptionPrixAchat($prixAchatNet);
        $option->setOptionCoefficient($coefficient);

        //log_message('error', __CLASS__ . '/' . __FUNCTION__ . print_r($option, 1));
        $this->managerOptions->editer($option);

        $option->hydrateArticles();

        if (!empty($option->getOptionArticles())) :
            foreach ($option->getOptionArticles() as $a) :
                $a->updatePrice();
                $this->managerArticles->editer($a);
            endforeach;
        endif;

        return true;
    }

    private function addComposant($designation, $uniteId, $type, $familleId) {

        $dataComposant = array(
            'composantFamilleId' => $familleId,
            'composantDesignation' => strtoupper($designation),
            'composantType' => $type,
            'composantUniteId' => $uniteId,
            'composantTauxTVA' => self::TVA * 100,
            'composantDelete' => 0
        );

        $composant = new Composant($dataComposant);
        $this->managerComposants->ajouter($composant);
        return $composant;
    }

    private function addArticle($designation, $description, $familleId = NULL) {

        $dataArticle = array(
            'articleFamilleId' => $familleId,
            'articleDesignation' => strtoupper($designation),
            'articleDescription' => $description,
            'articleHT' => 0,
            'articleAchatHT' => 0,
            'articleDelete' => 0
        );

        $article = new Article($dataArticle);
        $this->managerArticles->ajouter($article);
        return $article;
    }

    public function copyArticle() {

        /* Mêmes informations requises pour supprimer ou dupliquer un article */
        if (!$this->form_validation->run('delArticle')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $article = $this->managerArticles->getArticleById($this->input->post('articleId'));

        $cloneArticle = clone $article;
        $cloneArticle->setArticleDesignation('[COPY] ' . $cloneArticle->getArticleDesignation());

        $this->db->trans_start();
        $this->managerArticles->ajouter($cloneArticle);

        /* On duplique les compositions de l'article d'origine */
        foreach ((array) $article->getArticleCompositions() as $c):
            $dataComposition = array(
                'compositionArticleId' => $cloneArticle->getArticleId(),
                'compositionComposantId' => $c->getCompositionComposantId(),
                'compositionOptionId' => $c->getCompositionOptionId(),
                'compositionQte' => $c->getCompositionQte()
            );

            $this->managerCompositions->ajouter(new Composition($dataComposition));

        endforeach;

        $this->db->trans_complete();
        echo json_encode(array('type' => 'success', 'articleId' => $cloneArticle->getArticleId()));
        exit;
    }

    public function manageComposant() {

        if (!$this->form_validation->run('addComposant')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addComposantId')) :

            if (!$this->modComposant($this->input->post('addComposantId'), $this->input->post('addComposantDesignation'), $this->input->post('addComposantUniteId'), $this->input->post('addComposantType'), $this->input->post('addComposantFamilleId'))) :
                echo json_encode(array('type' => 'error', 'message' => 'Composant grillé !'));
                exit;
            endif;

            $composant = $this->managerComposants->getComposantById($this->input->post('addComposantId'));

        else :

            $this->db->trans_start();
            $composant = $this->addComposant($this->input->post('addComposantDesignation'), $this->input->post('addComposantUniteId'), $this->input->post('addComposantType'), $this->input->post('addComposantFamilleId'));
            $_POST['addOptionComposantId'] = $composant->getComposantId();

            /* Si on a les informations de la première option */
//            $this->form_validation->reset_validation();
//            if ($this->form_validation->run('addOption')) :
//                $this->addOption($this->input->post('addOptionNom'), $this->input->post('addOptionCoefficient'), $composant, $this->input->post('addOptionAchat'));
//            endif;

            $this->db->trans_complete();
        endif;

        echo json_encode(array('type' => 'success', 'composantId' => $composant->getComposantId()));
        exit;
    }

    public function manageArticle() {

        if (!$this->form_validation->run('addArticle')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addArticleId')) :
            if (!$this->modArticle($this->input->post('addArticleId'), $this->input->post('addArticleDesignation'), $this->input->post('addArticleDescription'), $this->input->post('addArticleFamilleId') ? $this->input->post('addArticleFamilleId') : null )) :
                echo json_encode(array('type' => 'error', 'message' => 'Article périmé !'));
                exit;
            endif;
            $article = $this->managerArticles->getArticleById($this->input->post('addArticleId'));
        else :
            $article = $this->addArticle($this->input->post('addArticleDesignation'), $this->input->post('addArticleDescription'), $this->input->post('addArticleFamilleId') ? $this->input->post('addArticleFamilleId') : null);
        endif;

        echo json_encode(array('type' => 'success', 'articleId' => $article->getArticleId()));
        exit;
    }

    public function manageOptions() {
        if (!$this->form_validation->run('addOption')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addOptionId')) :
            $this->modOption($this->input->post('addOptionId'), $this->input->post('addOptionReference'), $this->input->post('addOptionNom'), $this->input->post('addOptionPrixCatalogue'), $this->input->post('addOptionRemise'), $this->input->post('addOptionPrixAchatNet'), $this->input->post('addOptionCoefficient'), $this->input->post('addOptionActive'));
        else :
            $this->addOption($this->input->post('addOptionReference'), $this->input->post('addOptionNom'), $this->managerComposants->getComposantById($this->input->post('addOptionComposantId')), $this->input->post('addOptionPrixCatalogue'), $this->input->post('addOptionRemise'), $this->input->post('addOptionPrixAchatNet'), $this->input->post('addOptionCoefficient'), $this->input->post('addOptionActive'));
        endif;
        redirect('articles/ficheComposant/' . $this->input->post('addOptionComposantId'));
        exit;
    }

    public function delOption() {
        if ($this->form_validation->run('delOption')) :
            $option = $this->managerOptions->getOptionById(intval($this->input->post('optionId')));

            if ($option->getOptionNbUse() > 0) :
                echo json_encode(array('type' => 'error', 'message' => 'Impossible de supprimer une option utilisée dans un article'));
                exit;
            else :
                $this->managerOptions->delete($option);
                echo json_encode(array('type' => 'success'));
                exit;
            endif;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function delComposition() {
        if ($this->form_validation->run('delComposition')) :
            $composition = $this->managerCompositions->getCompositionById($this->input->post('compositionId'));

            $this->managerCompositions->delete($composition);

            /* Mise à jour de la valeur de l'article */
            $article = $this->managerArticles->getArticleById($composition->getCompositionArticleId());
            $this->majArticlePrix($article);
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function delFamille() {

        if ($this->form_validation->run('delFamille')) :
            $famille = $this->managerFamilles->getFamilleById(intval($this->input->post('familleId')));
            $this->managerFamilles->delete($famille);
            echo json_encode(array('type' => 'success'));
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

    public function copyComposant() {

        /* Mêmes informations requises pour supprimer ou dupliquer un composant */
        if (!$this->form_validation->run('delComposant')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $composant = $this->managerComposants->getComposantById($this->input->post('composantId'));

        $cloneComposant = clone $composant;
        $cloneComposant->setComposantDesignation('[COPY] ' . $cloneComposant->getComposantDesignation());

        $this->db->trans_start();
        $this->managerComposants->ajouter($cloneComposant);

        $composant->hydrateOptions();
        if (!empty($composant->getComposantOptions())) :
            foreach ($composant->getComposantOptions() as $o) :
                $cloneOption = clone $o;
                $cloneOption->setOptionComposantId($cloneComposant->getComposantId());
                $this->managerOptions->ajouter($cloneOption);
                unset($cloneOption);
            endforeach;
        endif;

        $this->db->trans_complete();
        echo json_encode(array('type' => 'success', 'composantId' => $cloneComposant->getComposantId()));
        exit;
    }

    public function ficheComposant($composantId) {

        $composant = $this->managerComposants->getComposantById($composantId);

        if (!$composant) :
            redirect('articles/composantsListe');
            exit;
        endif;

        $composant->hydrateOptions();
        $composant->hydrateCompositions();

        $articles = $this->managerArticles->getArticlesByComposantId($composant->getComposantId());

        $data = array(
            'composant' => $composant,
            'articles' => $articles,
            'unites' => $this->managerUnites->liste(),
            'familles' => $this->managerFamilles->liste(),
            'title' => 'Fiche composant',
            'description' => 'Détail du composant',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheArticle($articleId) {

        $article = $this->managerArticles->getArticleById($articleId);

        if (!$article) :
            redirect('articles/articlesListe');
            exit;
        endif;

        if (!empty($article->getArticleCompositions())) :
            foreach ($article->getArticleCompositions() as $c) :
                $c->hydrateComposant();
            endforeach;
        endif;

        $data = array(
            'article' => $article,
            'familles' => $this->managerFamilles->liste(),
            'composants' => $this->managerComposants->liste(array('composantDelete' => 0), 'f.familleNom, c.composantDesignation ASC'),
            'title' => 'Fiche article',
            'description' => 'Détail de l\'article',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function getOptions() {

        if ($this->form_validation->run('getOptions')) :
            echo json_encode(array(
                'type' => 'success',
                'options' => $this->managerOptions->getOptionsByComposantId(intval($this->input->post('composantId')), 'array')
            ));
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

    private function effacerComposant(Composant $composant) {

        if ($this->managerCompositions->count(array('compositionComposantId' => $composant->getComposantId())) > 0) :
            return 'Composant verrouillé ! Il appartient à des articles.';
        endif;

        $this->managerComposants->delete($composant);
        return true;
    }

    private function effacerArticle(Article $article) {

        $ventes = $this->managerAffaireArticles->count(array('affaireArticleArticleId' => $article->getArticleId()));
        if ($ventes > 0):
            return 'Impossible de supprimer un article utilisé dans une vente';
        else:
            $this->managerArticles->delete($article);
        endif;
        return true;
    }

    public function delComposant() {

        if (!$this->form_validation->run('delComposant')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $composant = $this->managerComposants->getComposantById($this->input->post('composantId'));

        $result = $this->effacerComposant($composant);
        if ($result === true) :
            echo json_encode(array('type' => 'success'));
        else :
            echo json_encode(array('type' => 'error', 'message' => $result));
        endif;
        exit;
    }

    public function delArticle() {

        if (!$this->form_validation->run('getArticle')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $article = $this->managerArticles->getArticleById($this->input->post('articleId'));

        $result = $this->effacerArticle($article);
        if ($result === true) :
            echo json_encode(array('type' => 'success'));
        else :
            echo json_encode(array('type' => 'error', 'message' => $result));
        endif;
        exit;
    }

    public function manageFamilles() {
        if (!$this->form_validation->run('addFamille')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addFamilleId')) :
            $this->modFamille($this->input->post('addFamilleId'), $this->input->post('addFamilleNom'));
        else :
            $this->addFamille($this->input->post('addFamilleNom'));
        endif;
        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function famillesListe() {

        $data = array(
            'familles' => $this->managerFamilles->liste(),
            'title' => 'Liste des familles',
            'description' => 'Familles pour le classement des composants',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function manageCompositions() {

        log_message('error', __CLASS__ . '/' . __FUNCTION__ . print_r($this->input->post('addCompositionOptionId'), 1));

        if ($this->input->post('modCompositionId')) :
            if (!$this->form_validation->run('modComposition')) :
                echo json_encode(array('type' => 'error', 'message' => validation_errors()));
                exit;
            else :
                $this->modComposition($this->input->post('modCompositionId'), $this->input->post('modCompositionQte'));
            endif;
        else :
            if (!$this->form_validation->run('addComposition')) :
                echo json_encode(array('type' => 'error', 'message' => validation_errors()));
                exit;
            else :
                $this->addComposition($this->input->post('addCompositionArticleId'), $this->input->post('addCompositionOptionId'), $this->input->post('addCompositionQte'));
            endif;
        endif;

        echo json_encode(array('type' => 'success'));
        exit;
    }

}
