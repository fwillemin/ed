<?php

/**
 * Classe de gestion des Catégories d'articles
 * Manager : Model_familles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Xth {

    public function getUrlArticle(Article $article) {
        $forbidden_caract = array(
            ' ' => '-',
            '!' => '-',
            '?' => '-',
            '(' => '-',
            ')' => '-',
            '[' => '-',
            ']' => '-',
            '+' => '',
            '*' => 'x',
            ',' => '-',
            '&' => '-',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'à' => 'a',
            'À' => 'A',
            'ù' => 'u',
            '/' => '-',
            'ô' => 'o',
            '’' => '-',
            '\'' => '-',
            '"' => '',
            ';' => '',
            'ö' => 'o',
            'É' => 'E',
            'ü' => 'u',
            '€' => 'E',
            ':' => '',
            '°' => '',
            'î' => 'i',
            'Î' => 'i',
            '®' => ''
        );
        $cleanning = array(
            '--' => '-',
            '---' => '-',
            '----' => '-',
            '-----' => '-',
        );
        $articleUrl = strtr((strtoupper($article->getArticleMarque()) . '-' . $article->getArticleDesignation() . '-' . $article->getArticleContenance() . '-' . $article->getArticleId()), $forbidden_caract);
        $articleUrl = strtr($articleUrl, $cleanning);
        return site_url($articleUrl);
    }

    /**
     * Retourne un timestamp de la date retournée par un input formulaire au format AAAA-MM-JJ
     * @param string $input Valeur de l'input à convertir
     * @return int Timestamp de la date
     */
    function mktimeFromInputDate($input = null) {
        date_default_timezone_set('Europe/Paris');
        if ($input == '' || !$input || $input == 0): return 0;
        else:
            $temp = explode('-', $input);
            return mktime(0, 0, 0, $temp[1], $temp[2], $temp[0]);
        endif;
    }

    /**
     * Génère un mot de passe aléatoire
     * @param int $nb_caractere Nombre de caractère pour le mot de passe
     * @return string Mot de passe
     */
    function generatePassword($nb_caractere = 8) {
        $mot_de_passe = "";
        $chiffres = '023456789';
        $minuscules = 'abcdefghjkmnopqrstuvwxyz';
        $majuscules = 'ABCDEFGHKMNOPQRSTUVWXYZ';
        $chaine = "abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ023456789+@!$%?&";
        $longeur_chaine = strlen($chaine);

        /* Afin de passer le contrôle de Community Auth, il est necessaire de s'assurer que le mot de passe contient
         *  1 chiffre, 1 minuscule et 1 majuscule et au mini 8 caractères
         */
        $mot_de_passe .= $chiffres[mt_rand(0, (strlen($chiffres) - 1))];
        $mot_de_passe .= $majuscules[mt_rand(0, (strlen($majuscules) - 1))];
        $mot_de_passe .= $minuscules[mt_rand(0, (strlen($minuscules) - 1))];
        for ($i = 1; $i <= $nb_caractere - 3; $i++) {
            $place_aleatoire = mt_rand(0, ($longeur_chaine - 1));
            $mot_de_passe .= $chaine[$place_aleatoire];
        }

        return $mot_de_passe;
    }

    function affModeReglement($modeReglement) {
        switch ($modeReglement):
            case 1:
                return 'Chèque';
                break;
            case 2:
                return 'Virement';
                break;
            case 3:
                return 'Espèces';
                break;
            case 4:
                return 'CB';
                break;
            case 5:
                return 'Traite';
                break;
        endswitch;
    }

    private function _enteteEmail() {

        $code = '<!DOCTYPE HTML>'
                . '<html xmlns="http://www.w3.org/1999/xhtml">'
                . '<head>'
                . '<title>Document envoyé par Enseigne Diffusion</title>'
                . '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
                . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
                . '</head>'
                . '<body style="margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; background-color: #FFF;">'
                . '<table width="100%" height="100%" cellpadding="0" style="padding: 20px 0px 20px 0px">'
                . '<tr><td style="width:15px;"></td><td>';
        return $code;
    }

    private function _footerEmail() {

        $code = '</td><td style="width:15px;"></td></tr>'
                . '</table>'
                . '</body>'
                . '</html>';

        return $code;
    }

    public function emailDevis(Devis $devis) {

        $CI = &get_instance();
        $CI->email->from('contact@enseignediffusion.com');
        $CI->email->to($devis->getDevisClient()->getClientEmail());
        $CI->email->subject('Votre devis  Enseigne Diffusion');

        /* Création du message */
        $message = $this->_enteteEmail();

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre devis.'
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>Julien Deplano';
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Devis' . $devis->getDevisId() . '.pdf');
        return $CI->email->send();
    }

    public function emailFacture(Facture $facture) {

        $CI = &get_instance();
        $CI->email->from('contact@enseignediffusion.com');
        $CI->email->to($facture->getFactureClient()->getClientEmail());
        $CI->email->subject('Votre Facture  Enseigne Diffusion');

        /* Création du message */
        $message = $this->_enteteEmail();

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre facture N°' . $facture->getFactureId()
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>Julien Deplano';
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Facture' . $facture->getFactureId() . '.pdf');
        return $CI->email->send();
    }

    public function emailAvoir(Avoir $avoir) {

        $CI = &get_instance();
        $CI->email->from('contact@enseignediffusion.com');
        $CI->email->to($avoir->getAvoirClient()->getClientEmail());
        $CI->email->subject('Votre avoir Enseigne Diffusion');

        /* Création du message */
        $message = $this->_enteteEmail();

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre avoir N°' . $avoir->getAvoirId()
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>Julien Deplano';
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Avoir' . $avoir->getAvoirId() . '.pdf');
        return $CI->email->send();
    }

}
