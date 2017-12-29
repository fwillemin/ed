-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 29 Décembre 2017 à 14:42
-- Version du serveur :  5.7.20-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `enseigne`
--

-- --------------------------------------------------------

--
-- Structure de la table `affaireArticles`
--

CREATE TABLE `affaireArticles` (
  `affaireArticleId` int(11) NOT NULL,
  `affaireArticleAffaireId` int(11) NOT NULL,
  `affaireArticleArticleId` int(11) DEFAULT NULL,
  `affaireArticleDesignation` varchar(255) NOT NULL,
  `affaireArticleDescription` text NOT NULL,
  `affaireArticleQte` decimal(5,2) NOT NULL,
  `affaireArticleTarif` decimal(8,2) NOT NULL,
  `affaireArticleRemise` int(11) NOT NULL,
  `affaireArticlePU` decimal(8,2) NOT NULL,
  `affaireArticleTotalHT` decimal(8,2) NOT NULL,
  `affaireArticlePrixForce` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `affaireClients`
--

CREATE TABLE `affaireClients` (
  `affaireClientAffaireId` int(11) NOT NULL,
  `affaireClientClientId` int(11) NOT NULL,
  `affaireClientPrincipal` tinyint(1) NOT NULL COMMENT '1 = principal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `affaireOptions`
--

CREATE TABLE `affaireOptions` (
  `affaireOptionId` int(11) NOT NULL,
  `affaireOptionAffaireId` int(11) NOT NULL,
  `affaireOptionArticleId` int(11) NOT NULL,
  `affaireOptionOptionId` int(11) NOT NULL,
  `affaireOptionComposantId` int(11) NOT NULL,
  `affaireOptionQte` decimal(5,2) NOT NULL,
  `affaireOptionPU` decimal(8,2) NOT NULL,
  `affaireOptionOriginel` tinyint(1) NOT NULL COMMENT '1 Si l''optin est d''origine sur l''article'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `affaires`
--

CREATE TABLE `affaires` (
  `affaireId` int(11) NOT NULL,
  `affaireDate` int(11) NOT NULL,
  `affaireType` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1 = prestation, 2 = Vente',
  `affaireObjet` varchar(255) COLLATE utf8_bin NOT NULL,
  `affaireTauxTVA` decimal(4,2) NOT NULL,
  `affaireTotalHT` decimal(8,2) NOT NULL,
  `affaireTotalTVA` decimal(8,2) NOT NULL,
  `affaireTotalTTC` decimal(8,2) NOT NULL,
  `affaireDevisId` int(11) DEFAULT NULL,
  `affaireDevisDate` int(11) NOT NULL,
  `affaireCommandeId` int(11) DEFAULT NULL,
  `affaireCommandeDate` int(11) NOT NULL,
  `affairePAO` tinyint(1) NOT NULL,
  `affaireFabrication` tinyint(1) NOT NULL,
  `affairePose` tinyint(1) NOT NULL,
  `affaireCloture` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `affectations`
--

CREATE TABLE `affectations` (
  `affectationId` int(11) NOT NULL,
  `affectationType` tinyint(4) NOT NULL COMMENT '1=Fabrication, 2=Pose',
  `affectationDossierId` int(11) NOT NULL,
  `affectationEquipeId` int(11) NOT NULL,
  `affectationIntervenant` varchar(255) COLLATE utf8_bin NOT NULL,
  `affectationDate` int(11) NOT NULL,
  `affectationPosition` smallint(6) NOT NULL,
  `affectationCommentaire` text COLLATE utf8_bin NOT NULL,
  `affectationEtat` tinyint(1) NOT NULL COMMENT '1=Attente, 2=Encours, 3=Termine'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `articleId` int(11) NOT NULL,
  `articleFamilleId` int(11) DEFAULT NULL,
  `articleDesignation` varchar(255) COLLATE utf8_bin NOT NULL,
  `articleDescription` longtext COLLATE utf8_bin NOT NULL,
  `articleHT` float NOT NULL,
  `articleAchatHT` float NOT NULL,
  `articleDelete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `clientId` int(11) NOT NULL,
  `clientRaisonSociale` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientNumTva` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientExoneration` tinyint(1) NOT NULL COMMENT 'Exoneration de TVA',
  `clientTelephone` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientAdresse1` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientAdresse2` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientCp` varchar(20) COLLATE utf8_bin NOT NULL,
  `clientVille` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientPays` varchar(80) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `composants`
--

CREATE TABLE `composants` (
  `composantId` int(11) NOT NULL,
  `composantFamilleId` int(11) DEFAULT NULL,
  `composantDesignation` varchar(255) COLLATE utf8_bin NOT NULL,
  `composantUniteId` tinyint(4) NOT NULL,
  `composantTauxTVA` decimal(5,2) NOT NULL DEFAULT '20.00',
  `composantDelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `compositions`
--

CREATE TABLE `compositions` (
  `compositionId` int(11) NOT NULL,
  `compositionArticleId` int(11) NOT NULL,
  `compositionOptionId` int(11) NOT NULL,
  `compositionComposantId` int(11) NOT NULL,
  `compositionQte` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `contactId` int(11) NOT NULL,
  `contactClientId` int(11) NOT NULL,
  `contactCivilite` varchar(5) COLLATE utf8_bin NOT NULL,
  `contactNom` varchar(50) COLLATE utf8_bin NOT NULL,
  `contactPrenom` varchar(50) COLLATE utf8_bin NOT NULL,
  `contactFonction` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `contactTelephone` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `contactPortable` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `contactEmail` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `dossiers`
--

CREATE TABLE `dossiers` (
  `dossierId` int(11) NOT NULL,
  `dossierClient` varchar(255) COLLATE utf8_bin NOT NULL,
  `dossierDescriptif` text COLLATE utf8_bin NOT NULL,
  `dossierDateSortie` int(11) NOT NULL,
  `dossierSortieEtat` tinyint(4) NOT NULL COMMENT '1 = Attente, 2 = Fait',
  `dossierPao` tinyint(1) NOT NULL,
  `dossierFab` tinyint(1) NOT NULL,
  `dossierPose` tinyint(1) NOT NULL,
  `dossierClos` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `equipes`
--

CREATE TABLE `equipes` (
  `equipeId` int(11) NOT NULL,
  `equipeNom` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `facturelignes`
--

CREATE TABLE `facturelignes` (
  `factureLigneId` int(11) NOT NULL,
  `factureLigneFactureId` int(11) NOT NULL,
  `factureLigneAffaireId` int(11) NOT NULL,
  `factureLigneAffaireArticleId` int(11) NOT NULL,
  `factureLigneDesignation` varchar(255) NOT NULL,
  `factureLigneDescription` text NOT NULL,
  `factureLigneQte` decimal(7,2) NOT NULL,
  `factureLigneTarif` decimal(10,2) NOT NULL,
  `factureLigneRemise` decimal(4,2) NOT NULL,
  `factureLigneTotalHT` decimal(10,2) NOT NULL,
  `factureLigneTotalTTC` decimal(10,2) NOT NULL,
  `factureLigneTotalTVA` decimal(10,2) NOT NULL,
  `factureLigneQuota` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `factureId` int(11) NOT NULL,
  `factureAffaireId` int(11) NOT NULL,
  `factureClientId` int(11) NOT NULL,
  `factureNum` int(11) NOT NULL,
  `factureDate` int(11) NOT NULL,
  `factureObjet` varchar(255) NOT NULL,
  `factureSolde` decimal(10,2) NOT NULL,
  `factureTotalHT` decimal(10,2) NOT NULL,
  `factureTotalTVA` decimal(10,2) NOT NULL,
  `factureTotalTTC` decimal(10,2) NOT NULL,
  `factureTauxTVA` decimal(4,2) NOT NULL DEFAULT '20.00' COMMENT 'Taux de TVA e vigueur pour la facture',
  `factureModeReglement` tinyint(4) NOT NULL COMMENT '1CHQ 2VIR 3ESP 4CB 5TR',
  `factureAbandon` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 Si la creation de la facture est abandonnée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `familles`
--

CREATE TABLE `familles` (
  `familleId` int(11) NOT NULL,
  `familleNom` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

CREATE TABLE `options` (
  `optionId` int(11) NOT NULL,
  `optionComposantId` int(11) NOT NULL,
  `optionReference` varchar(20) COLLATE utf8_bin NOT NULL,
  `optionNom` varchar(255) COLLATE utf8_bin NOT NULL,
  `optionPrixCatalogue` float(8,2) NOT NULL COMMENT 'Prix d''achat catalogue fournisseur',
  `optionRemise` float(5,2) NOT NULL COMMENT 'Remise conssentie par le fournisseur',
  `optionPrixAchat` float(8,2) NOT NULL COMMENT 'prix d''achat net',
  `optionCoefficient` float(4,2) NOT NULL DEFAULT '2.50',
  `optionHT` float(8,2) NOT NULL,
  `optionTVA` float(8,2) NOT NULL,
  `optionTTC` float(8,2) NOT NULL,
  `optionActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

CREATE TABLE `parametres` (
  `parametre` varchar(255) COLLATE utf8_bin NOT NULL,
  `valeur` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `recurrents`
--

CREATE TABLE `recurrents` (
  `recurrentId` int(11) NOT NULL,
  `recurrentCritere` varchar(255) COLLATE utf8_bin NOT NULL,
  `recurrentEquipeId` int(11) NOT NULL,
  `recurrentCommentaire` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `reglements`
--

CREATE TABLE `reglements` (
  `reglementId` int(11) NOT NULL,
  `reglementDate` int(11) NOT NULL,
  `reglementMontant` decimal(8,2) NOT NULL,
  `reglementToken` varchar(255) NOT NULL,
  `reglementAffaireId` int(11) NOT NULL,
  `reglementClientId` int(11) NOT NULL,
  `reglementType` tinyint(1) NOT NULL COMMENT '1ACOMPTE 2REGLEMENT',
  `reglementFactureId` int(11) DEFAULT NULL,
  `reglementMode` tinyint(2) NOT NULL,
  `reglementSourceId` int(11) NOT NULL COMMENT 'ID du réglement originel',
  `reglementUtile` tinyint(1) NOT NULL COMMENT 'TRUE si c''est le dernier réglement enregistré pour un meme ID source'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `unites`
--

CREATE TABLE `unites` (
  `uniteId` tinyint(4) NOT NULL,
  `uniteNom` varchar(60) COLLATE utf8_bin NOT NULL,
  `uniteSymbole` varchar(4) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `affaireArticles`
--
ALTER TABLE `affaireArticles`
  ADD PRIMARY KEY (`affaireArticleId`),
  ADD KEY `affaireArticleAffaireId` (`affaireArticleAffaireId`),
  ADD KEY `affaireArticleArticleId` (`affaireArticleArticleId`);

--
-- Index pour la table `affaireClients`
--
ALTER TABLE `affaireClients`
  ADD PRIMARY KEY (`affaireClientAffaireId`,`affaireClientClientId`),
  ADD KEY `affaireClientClientId` (`affaireClientClientId`),
  ADD KEY `affaireClientAffaireId` (`affaireClientAffaireId`);

--
-- Index pour la table `affaireOptions`
--
ALTER TABLE `affaireOptions`
  ADD PRIMARY KEY (`affaireOptionId`),
  ADD KEY `affaireOptionAffaireId` (`affaireOptionAffaireId`),
  ADD KEY `affaireOptionArticleId` (`affaireOptionArticleId`),
  ADD KEY `affaireOptionOptionId` (`affaireOptionOptionId`),
  ADD KEY `affaireOptionComposantId` (`affaireOptionComposantId`);

--
-- Index pour la table `affaires`
--
ALTER TABLE `affaires`
  ADD PRIMARY KEY (`affaireId`),
  ADD UNIQUE KEY `affaireDevisId` (`affaireDevisId`),
  ADD UNIQUE KEY `affaireCommandeId` (`affaireCommandeId`),
  ADD KEY `affaireDate` (`affaireDate`);

--
-- Index pour la table `affectations`
--
ALTER TABLE `affectations`
  ADD PRIMARY KEY (`affectationId`),
  ADD KEY `affectationDossierId` (`affectationDossierId`),
  ADD KEY `affectationEquipeId` (`affectationEquipeId`),
  ADD KEY `affectationEtat` (`affectationEtat`),
  ADD KEY `affectationDate` (`affectationDate`);

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`articleId`),
  ADD KEY `articleFamilleId` (`articleFamilleId`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`clientId`);

--
-- Index pour la table `composants`
--
ALTER TABLE `composants`
  ADD PRIMARY KEY (`composantId`),
  ADD KEY `composantDelete` (`composantDelete`),
  ADD KEY `composantFamilleId` (`composantFamilleId`),
  ADD KEY `composantUniteId` (`composantUniteId`);

--
-- Index pour la table `compositions`
--
ALTER TABLE `compositions`
  ADD PRIMARY KEY (`compositionId`),
  ADD UNIQUE KEY `compositionArticleId` (`compositionArticleId`,`compositionOptionId`),
  ADD KEY `compoComposantId` (`compositionComposantId`),
  ADD KEY `compoArticleId` (`compositionArticleId`),
  ADD KEY `compoOptionId` (`compositionOptionId`);

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contactId`),
  ADD KEY `contactClientId` (`contactClientId`);

--
-- Index pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`dossierId`);

--
-- Index pour la table `equipes`
--
ALTER TABLE `equipes`
  ADD PRIMARY KEY (`equipeId`);

--
-- Index pour la table `facturelignes`
--
ALTER TABLE `facturelignes`
  ADD PRIMARY KEY (`factureLigneId`),
  ADD KEY `factureLigneFactureId` (`factureLigneFactureId`),
  ADD KEY `factureLigneAffaireId` (`factureLigneAffaireId`),
  ADD KEY `factureLigneQte` (`factureLigneQte`),
  ADD KEY `factureLigneAffaireArtileId` (`factureLigneAffaireArticleId`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`factureId`),
  ADD KEY `factureAffaireId` (`factureAffaireId`),
  ADD KEY `factureClientId` (`factureClientId`);

--
-- Index pour la table `familles`
--
ALTER TABLE `familles`
  ADD PRIMARY KEY (`familleId`);

--
-- Index pour la table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`optionId`),
  ADD KEY `optionComposantId` (`optionComposantId`);

--
-- Index pour la table `recurrents`
--
ALTER TABLE `recurrents`
  ADD PRIMARY KEY (`recurrentId`),
  ADD KEY `recurrentEquipeId` (`recurrentEquipeId`);

--
-- Index pour la table `reglements`
--
ALTER TABLE `reglements`
  ADD PRIMARY KEY (`reglementId`),
  ADD KEY `reglementAffaireId` (`reglementAffaireId`),
  ADD KEY `reglementFactureId` (`reglementFactureId`),
  ADD KEY `reglementClientId` (`reglementClientId`),
  ADD KEY `reglementSourceId` (`reglementSourceId`);

--
-- Index pour la table `unites`
--
ALTER TABLE `unites`
  ADD PRIMARY KEY (`uniteId`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `affaireArticles`
--
ALTER TABLE `affaireArticles`
  MODIFY `affaireArticleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `affaireOptions`
--
ALTER TABLE `affaireOptions`
  MODIFY `affaireOptionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `affaires`
--
ALTER TABLE `affaires`
  MODIFY `affaireId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `affectations`
--
ALTER TABLE `affectations`
  MODIFY `affectationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=925;
--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `articleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `clientId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1686;
--
-- AUTO_INCREMENT pour la table `composants`
--
ALTER TABLE `composants`
  MODIFY `composantId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT pour la table `compositions`
--
ALTER TABLE `compositions`
  MODIFY `compositionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contactId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `dossierId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=512;
--
-- AUTO_INCREMENT pour la table `equipes`
--
ALTER TABLE `equipes`
  MODIFY `equipeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `facturelignes`
--
ALTER TABLE `facturelignes`
  MODIFY `factureLigneId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `factureId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `familles`
--
ALTER TABLE `familles`
  MODIFY `familleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `options`
--
ALTER TABLE `options`
  MODIFY `optionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT pour la table `recurrents`
--
ALTER TABLE `recurrents`
  MODIFY `recurrentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `reglements`
--
ALTER TABLE `reglements`
  MODIFY `reglementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `unites`
--
ALTER TABLE `unites`
  MODIFY `uniteId` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `affaireArticles`
--
ALTER TABLE `affaireArticles`
  ADD CONSTRAINT `affaireArticles_ibfk_1` FOREIGN KEY (`affaireArticleAffaireId`) REFERENCES `affaires` (`affaireId`) ON DELETE CASCADE,
  ADD CONSTRAINT `affaireArticles_ibfk_2` FOREIGN KEY (`affaireArticleArticleId`) REFERENCES `articles` (`articleId`);

--
-- Contraintes pour la table `affaireClients`
--
ALTER TABLE `affaireClients`
  ADD CONSTRAINT `affaireClients_ibfk_1` FOREIGN KEY (`affaireClientAffaireId`) REFERENCES `affaires` (`affaireId`) ON DELETE CASCADE,
  ADD CONSTRAINT `affaireClients_ibfk_2` FOREIGN KEY (`affaireClientClientId`) REFERENCES `clients` (`clientId`);

--
-- Contraintes pour la table `affaireOptions`
--
ALTER TABLE `affaireOptions`
  ADD CONSTRAINT `affaireOptions_ibfk_1` FOREIGN KEY (`affaireOptionAffaireId`) REFERENCES `affaires` (`affaireId`) ON DELETE CASCADE,
  ADD CONSTRAINT `affaireOptions_ibfk_2` FOREIGN KEY (`affaireOptionArticleId`) REFERENCES `affaireArticles` (`affaireArticleId`) ON DELETE CASCADE,
  ADD CONSTRAINT `affaireOptions_ibfk_3` FOREIGN KEY (`affaireOptionOptionId`) REFERENCES `options` (`optionId`),
  ADD CONSTRAINT `affaireOptions_ibfk_4` FOREIGN KEY (`affaireOptionComposantId`) REFERENCES `composants` (`composantId`);

--
-- Contraintes pour la table `affectations`
--
ALTER TABLE `affectations`
  ADD CONSTRAINT `affectations_ibfk_1` FOREIGN KEY (`affectationDossierId`) REFERENCES `dossiers` (`dossierId`),
  ADD CONSTRAINT `affectations_ibfk_2` FOREIGN KEY (`affectationEquipeId`) REFERENCES `equipes` (`equipeId`);

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`articleFamilleId`) REFERENCES `familles` (`familleId`);

--
-- Contraintes pour la table `composants`
--
ALTER TABLE `composants`
  ADD CONSTRAINT `composants_ibfk_1` FOREIGN KEY (`composantFamilleId`) REFERENCES `familles` (`familleId`) ON DELETE SET NULL,
  ADD CONSTRAINT `composants_ibfk_2` FOREIGN KEY (`composantUniteId`) REFERENCES `unites` (`uniteId`);

--
-- Contraintes pour la table `compositions`
--
ALTER TABLE `compositions`
  ADD CONSTRAINT `compositions_ibfk_1` FOREIGN KEY (`compositionArticleId`) REFERENCES `articles` (`articleId`) ON DELETE CASCADE,
  ADD CONSTRAINT `compositions_ibfk_2` FOREIGN KEY (`compositionComposantId`) REFERENCES `composants` (`composantId`),
  ADD CONSTRAINT `compositions_ibfk_3` FOREIGN KEY (`compositionOptionId`) REFERENCES `options` (`optionId`);

--
-- Contraintes pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`contactClientId`) REFERENCES `clients` (`clientId`) ON DELETE CASCADE;

--
-- Contraintes pour la table `facturelignes`
--
ALTER TABLE `facturelignes`
  ADD CONSTRAINT `facturelignes_ibfk_1` FOREIGN KEY (`factureLigneFactureId`) REFERENCES `factures` (`factureId`) ON DELETE CASCADE,
  ADD CONSTRAINT `facturelignes_ibfk_2` FOREIGN KEY (`factureLigneAffaireId`) REFERENCES `affaires` (`affaireId`),
  ADD CONSTRAINT `facturelignes_ibfk_3` FOREIGN KEY (`factureLigneAffaireArticleId`) REFERENCES `affaireArticles` (`affaireArticleId`);

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`factureAffaireId`) REFERENCES `affaires` (`affaireId`) ON DELETE CASCADE,
  ADD CONSTRAINT `factures_ibfk_2` FOREIGN KEY (`factureClientId`) REFERENCES `clients` (`clientId`);

--
-- Contraintes pour la table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`optionComposantId`) REFERENCES `composants` (`composantId`) ON DELETE CASCADE;

--
-- Contraintes pour la table `recurrents`
--
ALTER TABLE `recurrents`
  ADD CONSTRAINT `recurrents_ibfk_1` FOREIGN KEY (`recurrentEquipeId`) REFERENCES `equipes` (`equipeId`);

--
-- Contraintes pour la table `reglements`
--
ALTER TABLE `reglements`
  ADD CONSTRAINT `reglements_ibfk_1` FOREIGN KEY (`reglementAffaireId`) REFERENCES `affaires` (`affaireId`) ON DELETE CASCADE,
  ADD CONSTRAINT `reglements_ibfk_2` FOREIGN KEY (`reglementClientId`) REFERENCES `clients` (`clientId`);

--
-- Contraintes pour la table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
