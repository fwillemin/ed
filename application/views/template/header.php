<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title><?= (!empty($title)) ? $title : false; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?= (!empty($description)) ? $description : false; ?>">
        <meta name="author" content="Xanthellis - Créateur de sites internet et d'applications professionnelles - http://www.xanthellis.com">

        <!-- Le styles -->
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap3.3.7/css/bootstrap.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap3.3.7/css/bootstrap-datepicker.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap3.3.7/css/bootstrap-select.min.css'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap-table/bootstrap-table.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css'); ?>" >

        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbar/MegaNavbar.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbar/navbar-inverse-dark.css'); ?>">

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/tables/datatables.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/jquery-ui/jquery-ui.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/jqueryConfirm/jquery-confirm.min.css'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/enseignediffusion.css'); ?>" >

        <!-- reload grunt --><script src="//localhost:35729/livereload.js"></script>

    </head>

    <body>

        <?php if ($this->ion_auth->logged_in()): ?>

            <nav class="navbar navbar-inverse-dark" role="navigation" id="navHeader">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar_id">
                            <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                        </button>

                    </div>
                    <div class="collapse navbar-collapse" id="navbar_id">
                        <ul class="nav navbar-nav navbar-left">
                            <li class="dropdown-short" style="padding:0px; margin: 0px;">
                                <a href="<?= site_url(); ?>" class=""><img src="<?= base_url('assets/img/logo.png'); ?>" style="max-height: 30px;" ></a>
                            </li>
                            <li class="dropdown-grid">
                                <a href="<?= site_url('clients'); ?>" class=""><i class="fa fa-user-circle"></i> Clients</a>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">Composants & Articles <span class="fa fa-caret-down" style="margin-left: 5px;"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= site_url('articles/composantsListe'); ?>"><i class="fas fa-compress"></i> Composants</a></li>
                                    <li><a href="<?= site_url('articles/articlesListe'); ?>"><i class="fa fa-square"></i> Articles</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?= site_url('articles/famillesListe'); ?>"><i class="fa fa-cog"></i> Familles</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?= site_url('articles/inventaire'); ?>"><i class="fas fa-th-list"></i> Inventaire</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fas fa-handshake"></i> Affaires <span class="fa fa-caret-down" style="margin-left: 5px;"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= site_url('ventes/resetConcepteur'); ?>"><i class="fa fa-asterisk"></i> Nouvelle</a></li>
                                    <li><a href="<?= site_url('ventes/listeAffaires'); ?>"><i class="fa fa-list-ul"></i> Liste des Affaires</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fas fa-building"></i> Gestion <span class="fa fa-caret-down" style="margin-left: 5px;"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= site_url('facturation/listeFactures'); ?>"><i class="far fa-file"></i> Liste factures</a></li>
                                    <li><a href="<?= site_url('facturation/listeReglements'); ?>"><i class="far fa-credit-card"></i> Réglements</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?= site_url('facturation/listeFacturesNonEnvoyees'); ?>"><i class="far fa-file"></i> Factures à envoyer</a></li>
                                    <li><a href="<?= site_url('facturation/listeFacturesRelances'); ?>"><i class="fas fa-file-excel"></i> Relances</a></li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown-grid">
                                <?php if ($this->ion_auth->is_admin()): ?>
                                    <a href="<?= site_url('ed/dossiers'); ?>">Dossiers</a>
                                <?php endif; ?>
                                <a href="<?= site_url('ed/journalier'); ?>"><i class="glyphicon glyphicon-calendar"></i> Journalier</a>
                                <?php if ($this->ion_auth->is_admin()): ?>
                                    <a href="<?= site_url('ed/hebdomadaire'); ?>"><i class="glyphicon glyphicon-calendar"></i> Hebdomadaire</a>
                                    <a href="<?= site_url('ed/recurrent'); ?>"><i class="glyphicon glyphicon-repeat"></i> Recurrent</a>
                                <?php endif; ?>

                            </li>
                            <li class="dropdown-grid">
                                <a href="<?= site_url('ed/logout'); ?>" style="color: orangered;">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>


        <?php endif;
        ?>