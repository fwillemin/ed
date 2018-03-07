<?php

$config = array(
    /* Connexion */
    'identification' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pass',
            'label' => 'Mot de passe',
            'rules' => 'required|trim'
        )
    ),
    /* Ajout d'un composant */
    'addComposant' => array(
        array(
            'field' => 'addComposantId',
            'label' => 'ID du composant',
            'rules' => 'is_natural_no_zero|callback_existComposant'
        ),
        array(
            'field' => 'addComposantFamilleId',
            'label' => 'ID de la famille du composant',
            'rules' => 'is_natural_no_zero|required'
        ),
        array(
            'field' => 'addComposantDesignation',
            'label' => 'Désignation du composant',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addComposantUniteId',
            'label' => 'Unite du composant',
            'rules' => 'required|in_list[1,2,3,4]'
        )
    ),
    /* Supprime un composant */
    'delComposant' => array(
        array(
            'field' => 'composantId',
            'label' => 'ID du composant',
            'rules' => 'is_natural_no_zero|required|callback_existComposant'
        )
    ),
    /* Ajout d'une option à un composant */
    'addOption' => array(
        array(
            'field' => 'addOptionComposantId',
            'label' => 'Id du composant',
            'rules' => 'required|is_natural_no_zero|callback_existComposant'
        ),
        array(
            'field' => 'addOptionReference',
            'label' => 'Référence fournisseur de l\'option',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addOptionNom',
            'label' => 'Nom de option',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addOptionCatalogue',
            'label' => 'Prix Catalogue HT',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'addOptionCoefficient',
            'label' => 'Coefficient',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'addOptionPrixAchatNet',
            'label' => 'Prix achat net',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'addOptionRemise',
            'label' => 'Remise fournisseur',
            'rules' => 'numeric'
        )
    ),
    /* Supprimer une option */
    'delOption' => array(
        array(
            'field' => 'optionId',
            'label' => 'Id de l\'option',
            'rules' => 'required|is_natural_no_zero|callback_existOption'
        )
    ),
    /* Ajout d'un article */
    'addArticle' => array(
        array(
            'field' => 'addArticleId',
            'label' => 'ID de l\'article',
            'rules' => 'is_natural_no_zero|callback_existArticle'
        ),
        array(
            'field' => 'addArticleFamilleId',
            'label' => 'ID de la famille',
            'rules' => 'callback_existFamille'
        ),
        array(
            'field' => 'addArticleDesignation',
            'label' => 'Désignation du composant',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addArticleDescription',
            'label' => 'Description du composant',
            'rules' => 'required|trim'
        )
    ),
    /* Get article */
    'getArticle' => array(
        array(
            'field' => 'articleId',
            'label' => 'ID de l\'article',
            'rules' => 'is_natural_no_zero|required|callback_existArticle'
        )
    ),
    /* Appel Options */
    'getOptions' => array(
        array(
            'field' => 'composantId',
            'label' => 'ID du composant',
            'rules' => 'is_natural_no_zero|required|callback_existComposant'
        )
    ),
    /* Ajout d'une famille */
    'addFamille' => array(
        array(
            'field' => 'addFamilleId',
            'label' => 'ID de la famille',
            'rules' => 'is_natural_no_zero|callback_existFamille'
        ),
        array(
            'field' => 'addFamilleNom',
            'label' => 'Nom de la famille',
            'rules' => 'required|trim'
        )
    ),
    /* Suppression d'une famille */
    'delFamille' => array(
        array(
            'field' => 'familleId',
            'label' => 'ID de la famille',
            'rules' => 'is_natural_no_zero|callback_existFamille'
        )
    ),
    /* Ajout d'une composition */
    'addComposition' => array(
        array(
            'field' => 'addCompositionArticleId',
            'label' => 'ID de l\'article',
            'rules' => 'is_natural_no_zero|required|callback_existArticle'
        ),
        array(
            'field' => 'addCompositionOptionId',
            'label' => 'ID de l\'option',
            'rules' => 'is_array'
        ),
        array(
            'field' => 'addCompositionComposantId',
            'label' => 'ID du composant',
            'rules' => 'is_natural_no_zero|required|callback_existComposant'
        ),
        array(
            'field' => 'addCompositionQte',
            'label' => 'Qte de la composition',
            'rules' => 'required|greater_than_equal_to[0]'
        )
    ),
    /* modification d'une composition */
    'modComposition' => array(
        array(
            'field' => 'modCompositionId',
            'label' => 'ID de la composition',
            'rules' => 'is_natural_no_zero|required|callback_existComposition'
        ),
        array(
            'field' => 'modCompositionQte',
            'label' => 'Qte de la composition',
            'rules' => 'required|greater_than_equal_to[0]'
        )
    ),
    /* Supprime une composition */
    'delComposition' => array(
        array(
            'field' => 'compositionId',
            'label' => 'ID de la composition',
            'rules' => 'is_natural_no_zero|required|callback_existComposition'
        )
    ),
    /* Ajout d'un client */
    'addClient' => array(
        array(
            'field' => 'addClientId',
            'label' => 'ID du client',
            'rules' => 'is_natural_no_zero|callback_existClient'
        ),
        array(
            'field' => 'addClientRaisonSociale',
            'label' => 'Raison sociale du client',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addClientAdresse1',
            'label' => 'Adresse du client',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addClientAdresse2',
            'label' => 'Adresse complément du client',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addClientCp',
            'label' => 'Code postal du client',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addClientVille',
            'label' => 'Ville du client',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addClientPays',
            'label' => 'Pays du client',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addClientTelephone',
            'label' => 'Téléphone du client',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addClientIntracom',
            'label' => 'Intracommunautaire du client',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addClientEcheancePaiement',
            'label' => 'Echeance de paiement',
            'rules' => 'trim|required|in_list[1,2,3,4]'
        )
    ),
    /* Supprime un client */
    'delClient' => array(
        array(
            'field' => 'clientId',
            'label' => 'ID du client',
            'rules' => 'is_natural_no_zero|required|callback_existClient'
        )
    ),
    /* Ajoute un article au panier */
    'addVenteArticle' => array(
        array(
            'field' => 'articleId',
            'label' => 'ID de l\'article',
            'rules' => 'is_natural_no_zero|required|callback_existArticle'
        )
    ),
    /* Dans une vente, ajoute un composant à un article */
    'addComposantToArticle' => array(
        array(
            'field' => 'addComposantRowId',
            'label' => 'ID dans le panier',
            'rules' => 'required'
        ),
        array(
            'field' => 'addComposantOptionId',
            'label' => 'ID de l\'option',
            'rules' => 'required|callback_existOption'
        ),
        array(
            'field' => 'addComposantQte',
            'label' => 'Quantité à ajouter',
            'rules' => 'required|numeric'
        ),
    ),
    /* Ajoute un article au panier */
    'autoSave' => array(
        array(
            'field' => 'rowId',
            'label' => 'ID de l\'item',
            'rules' => 'required'
        ),
        array(
            'field' => 'param',
            'label' => 'Parametre à modifier',
            'rules' => 'required'
        ),
        array(
            'field' => 'valeur',
            'label' => 'Valeur du paramètre',
            'rules' => 'required'
        )
    ),
    /* Selection du client dans le dossier */
    'selectionClient' => array(
        array(
            'field' => 'clientId',
            'label' => 'ID du client',
            'rules' => 'required|callback_existClient'
        )
    ),
    /* Get client */
    'getContact' => array(
        array(
            'field' => 'contactId',
            'label' => 'ID du contact',
            'rules' => 'required|callback_existContact'
        )
    ),
    /* Ajout d'un contact */
    'addContact' => array(
        array(
            'field' => 'addContactId',
            'label' => 'ID du contact',
            'rules' => 'callback_existContact'
        ),
        array(
            'field' => 'addContactClientId',
            'label' => 'Nom du client',
            'rules' => 'trim|callback_existClient'
        ),
        array(
            'field' => 'addContactPrenom',
            'label' => 'Prenom du contact',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addContactNom',
            'label' => 'Nom du contact',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addContactCivilite',
            'label' => 'Civilité du contact',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addContactFonction',
            'label' => 'Fonction du contact',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addContactTelephone',
            'label' => 'Téléphone du contact',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addContactPortable',
            'label' => 'Portable du contact',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addContactEmail',
            'label' => 'Email du contact',
            'rules' => 'trim|valid_email'
        )
    ),
    /* Affaire */
    'getAffaire' => array(
        array(
            'field' => 'affaireId',
            'label' => 'ID Affaire',
            'rules' => 'required|callback_existAffaire'
        )
    ),
    /* addReglement */
    'addReglement' => array(
        array(
            'field' => 'addReglementAffaireId',
            'label' => 'ID Affaire',
            'rules' => 'required|callback_existAffaire'
        ), array(
            'field' => 'addReglementId',
            'label' => 'ID du réglement père',
            'rules' => 'callback_existReglement'
        ),
        array(
            'field' => 'addReglementClientId',
            'label' => 'ID Client',
            'rules' => 'required|callback_existClient'
        ),
        array(
            'field' => 'addReglementDate',
            'label' => 'Date du réglement',
            'rules' => 'required'
        ),
        array(
            'field' => 'addReglementType',
            'label' => 'Type de réglement',
            'rules' => 'required|in_list[1,2]'
        ),
        array(
            'field' => 'addReglementMode',
            'label' => 'Mode de réglement',
            'rules' => 'required|in_list[1,2,3,4,5]'
        ),
        array(
            'field' => 'addReglementFactureId',
            'label' => 'ID de la facture',
            'rules' => 'callback_existFacture'
//'rules' => ''
        ),
        array(
            'field' => 'addReglementMontant',
            'label' => 'Montant du réglement',
            'rules' => 'required|numeric|trim'
        )
    ),
    /* Reglement */
    'getReglement' => array(
        array(
            'field' => 'reglementId',
            'label' => 'ID du réglement',
            'rules' => 'required|callback_existReglement'
        )
    ),
    /* addFacture */
    'addFacture' => array(
        array(
            'field' => 'addFactureAffaireId',
            'label' => 'ID Affaire',
            'rules' => 'required|callback_existAffaire'
        ),
        array(
            'field' => 'addFactureClientId',
            'label' => 'ID Client',
            'rules' => 'required|callback_existClient'
        ),
        array(
            'field' => 'addFactureLignes',
            'label' => 'Lignes',
            'rules' => ''
        ),
        array(
            'field' => 'addFactureMode',
            'label' => 'Mode de réglement',
            'rules' => 'required|in_list[1,2,3,4,5]'
        ),
        array(
            'field' => 'addFactureObjet',
            'label' => 'Objet de la facture',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addFactureEcheancePaiement',
            'label' => 'Echeance de paiement',
            'rules' => 'required|trim|in_list[1,2,3,4]'
        )
    ),
    /* Facture */
    'getFacture' => array(
        array(
            'field' => 'factureId',
            'label' => 'ID de la facture',
            'rules' => 'required|callback_existFacture'
        )
    ),
    /* modEcheanceFacture */
    'modEcheanceFacture' => array(
        array(
            'field' => 'factureId',
            'label' => 'ID de la facture',
            'rules' => 'required|callback_existFacture'
        ),
        array(
            'field' => 'echeanceId',
            'label' => 'Echeance',
            'rules' => 'required|in_list[1,2,3,4]'
        )
    ),
    'modAvoirQte' => array(
        array(
            'field' => 'rowId',
            'label' => 'Row Id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'qte',
            'label' => 'Qte',
            'rules' => 'required|numeric|greater_than_equal_to[0]'
        )
    ),
    'modAvoirName' => array(
        array(
            'field' => 'rowId',
            'label' => 'Row Id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'name',
            'label' => 'Description',
            'rules' => 'required'
        )
    ),
    'modAvoirPrix' => array(
        array(
            'field' => 'rowId',
            'label' => 'Row Id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'prix',
            'label' => 'Prix',
            'rules' => 'required|numeric|greater_than_equal_to[0]'
        )
    ),
    /* addDossier */
    'addDossier' => array(
        array(
            'field' => 'addDossierId',
            'label' => 'ID Dossier',
            'rules' => 'callback_existDossier'
        ),
        array(
            'field' => 'addDossierClient',
            'label' => 'Client',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addDossierDescriptif',
            'label' => 'Descriptif du dossier',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addDossierFab',
            'label' => 'Fabrication',
            'rules' => 'in_list[1]'
        ),
        array(
            'field' => 'addDossierPose',
            'label' => 'Pose',
            'rules' => 'in_list[1]'
        ),
        array(
            'field' => 'addDossierPAO',
            'label' => 'Conception POA',
            'rules' => 'in_list[1]'
        ),
        array(
            'field' => 'addDossierClos',
            'label' => 'Cloture',
            'rules' => 'in_list[1]'
        )
    ),
    /* Dossier */
    'getDossier' => array(
        array(
            'field' => 'dossierId',
            'label' => 'ID du dossier',
            'rules' => 'required|callback_existDossier'
        )
    ),
    /* Affectation */
    'getAffectation' => array(
        array(
            'field' => 'affectationId',
            'label' => 'ID Affectation',
            'rules' => 'required|callback_existAffectation'
        )
    ),
    /* Ajout d'une affectation */
    'addAffectation' => array(
        array(
            'field' => 'addAffectId',
            'label' => 'ID Affectation',
            'rules' => 'callback_existAffectation'
        ),
        array(
            'field' => 'addAffectDossierId',
            'label' => 'ID Dossier',
            'rules' => 'required|callback_existDossier'
        ),
        array(
            'field' => 'addAffectType',
            'label' => 'Type affectation',
            'rules' => 'required|in_list[1,2,3]'
        ),
        array(
            'field' => 'addAffectDate',
            'label' => 'Date',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addAffectNbJour',
            'label' => 'Nombre de jours',
            'rules' => 'required|trim|is_natural_no_zero'
        ),
        array(
            'field' => 'addAffectEquipeId',
            'label' => 'ID Equipe',
            'rules' => 'required|callback_existEquipe'
        ),
        array(
            'field' => 'addAffectIntervenant',
            'label' => 'Intervenants',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addAffectCommentaire',
            'label' => 'Commentaire',
            'rules' => 'trim'
        )
    ),
    /* Ajout d'un recurrent */
    'addRecurrent' => array(
        array(
            'field' => 'addRecurrentId',
            'label' => 'ID Recurrent',
            'rules' => 'callback_existRecurrent'
        ),
        array(
            'field' => 'addRecurrentCritere',
            'label' => 'Critère',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addRecurrentEquipeId',
            'label' => 'ID Equipe',
            'rules' => 'required|callback_existEquipe'
        ),
        array(
            'field' => 'addRecurrentCommentaire',
            'label' => 'Commentaire',
            'rules' => 'trim'
        )
    ),
    'getRecurrent' => array(
        array(
            'field' => 'recurrentId',
            'label' => 'ID du recurrent',
            'rules' => 'required|callback_existRecurrent'
        )
    )
);
?>