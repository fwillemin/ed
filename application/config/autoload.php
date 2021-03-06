<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------
  | AUTO-LOADER
  | -------------------------------------------------------------------
  | This file specifies which systems should be loaded by default.
  |
  | In order to keep the framework as light-weight as possible only the
  | absolute minimal resources are loaded by default. For example,
  | the database is not connected to automatically since no assumption
  | is made regarding whether you intend to use it.  This file lets
  | you globally define which systems you would like loaded with every
  | request.
  |
  | -------------------------------------------------------------------
  | Instructions
  | -------------------------------------------------------------------
  |
  | These are the things you can load automatically:
  |
  | 1. Packages
  | 2. Libraries
  | 3. Drivers
  | 4. Helper files
  | 5. Custom config files
  | 6. Language files
  | 7. Models
  |
 */

/*
  | -------------------------------------------------------------------
  |  Auto-load Packages
  | -------------------------------------------------------------------
  | Prototype:
  |
  |  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
  |
 */

/* $autoload['packages'] = array(APPPATH . 'third_party/community_auth/'); */
$autoload['packages'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Libraries
  | -------------------------------------------------------------------
  | These are the classes located in the system/libraries folder
  | or in your application/libraries folder.
  |
  | Prototype:
  |
  |	$autoload['libraries'] = array('database', 'email', 'session');
  |
  | You can also supply an alternative library name to be assigned
  | in the controller:
  |
  |	$autoload['libraries'] = array('user_agent' => 'ua');
 */

$autoload['libraries'] = array('session', 'database', 'form_validation', 'email', 'cart', 'xth',
    'equipe', 'dossier', 'affectation', 'recurrent', 'ion_auth',
    'client', 'contact', 'famille', 'composant', 'option', 'article', 'unite', 'composition',
    'affaire', 'affaireArticle', 'affaireOption', 'token', 'affaireClient',
    'reglement', 'facture', 'factureLigne', 'avoir', 'avoirLigne', 'Remise', 'Maquette'
);


/*
  | -------------------------------------------------------------------
  |  Auto-load Drivers
  | -------------------------------------------------------------------
  | These classes are located in the system/libraries folder or in your
  | application/libraries folder within their own subdirectory. They
  | offer multiple interchangeable driver options.
  |
  | Prototype:
  |
  |	$autoload['drivers'] = array('cache');
 */

$autoload['drivers'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Helper Files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['helper'] = array('url', 'file');
 */

$autoload['helper'] = array('url', 'form', 'security', 'text', 'cookie', 'download');


/*
  | -------------------------------------------------------------------
  |  Auto-load Config files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['config'] = array('config1', 'config2');
  |
  | NOTE: This item is intended for use ONLY if you have created custom
  | config files.  Otherwise, leave it blank.
  |
 */

/* $autoload['config'] = array('db_tables','authentication'); */
$autoload['config'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Language files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['language'] = array('lang1', 'lang2');
  |
  | NOTE: Do not include the "_lang" part of your file.  For example
  | "codeigniter_lang.php" would be referenced as array('codeigniter');
  |
 */

$autoload['language'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Models
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['model'] = array('first_model', 'second_model');
  |
  | You can also supply an alternative model name to be assigned
  | in the controller:
  |
  |	$autoload['model'] = array('first_model' => 'first');
 */

$autoload['model'] = array(
    'Model_equipes' => 'managerEquipes',
    'Model_dossiers' => 'managerDossiers',
    'Model_affectations' => 'managerAffectations',
    'Model_recurrents' => 'managerRecurrents',
    'Model_clients' => 'managerClients',
    'Model_contacts' => 'managerContacts',
    'Model_familles' => 'managerFamilles',
    'Model_articles' => 'managerArticles',
    'Model_composants' => 'managerComposants',
    'Model_unites' => 'managerUnites',
    'Model_options' => 'managerOptions',
    'Model_compositions' => 'managerCompositions',
    'Model_affaires' => 'managerAffaires',
    'Model_affaireArticles' => 'managerAffaireArticles',
    'Model_affaireOptions' => 'managerAffaireOptions',
    'Model_affaireClients' => 'managerAffaireClients',
    'Model_reglements' => 'managerReglements',
    'Model_factures' => 'managerFactures',
    'Model_factureLignes' => 'managerFactureLignes',
    'model_avoirs' => 'managerAvoirs',
    'model_avoirlignes' => 'managerAvoirlignes',
    'model_remises' => 'managerRemises',
    'model_maquettes' => 'managerMaquettes'
);
