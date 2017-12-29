<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title><?php echo (!empty($title)) ? $title : false; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo (!empty($description)) ? $description : false; ?>">
        <meta name="author" content="Xanthellis - Créateur de sites internet et d'applications professionnelles - http://www.xanthellis.com">

        <!-- Le styles -->
        <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/favicon.png'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap3.3.7/css/bootstrap.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap3.3.7/css/bootstrap-datepicker.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap3.3.7/css/bootstrap-select.min.css'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap-table/bootstrap-table.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css'); ?>" >

        <link rel="stylesheet" href="<?php echo base_url('assets/MegaNavbar/MegaNavbar.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/MegaNavbar/navbar-inverse-dark.css'); ?>">
        <link href="<?php echo base_url('assets/MegaNavbar/assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css'); ?>" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/tables/datatables.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/jquery-ui/jquery-ui.min.css'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/enseignediffusion.css'); ?>" >

        <!-- reload grunt --><script src="//localhost:35729/livereload.js"></script><script src="//localhost:35729/livereload.js"></script><script src="//localhost:35729/livereload.js"></script><script src="//localhost:35729/livereload.js"></script>

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
                                <a href="<?php echo site_url(); ?>" class=""><img src="<?php echo base_url('assets/img/logo.png'); ?>" style="max-height: 30px;" ></a>
                            </li>
                            <li class="dropdown-grid">
                                <a href="<?php echo site_url('clients'); ?>" class=""><i class="fa fa-user-circle"></i> Clients</a>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fa fa-times-rectangle"></i> Composants & Articles<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('articles/composantsListe'); ?>"><i class="fa fa-times-rectangle-o"></i> Composants</a></li>
                                    <li><a href="<?php echo site_url('articles/articlesListe'); ?>"><i class="fa fa-square"></i> Articles</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo site_url('articles/famillesListe'); ?>"><i class="fa fa-cog fa-spin"></i> Familles</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fa fa-file"></i> Ventes<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('ventes/resetConcepteur'); ?>"><i class="fa fa-asterisk"></i> Nouvelle</a></li>
                                    <li><a href="<?php echo site_url('ventes/listeAffaires'); ?>"><i class="fa fa-list-ul"></i> Liste des Affaires</a></li>                                    
                                </ul>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fa fa-money"></i> Gestion<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('facturation/listeFactures'); ?>"><i class="fa fa-fire"></i> Liste factures</a></li>
                                    <li><a href="<?php echo site_url('ventes/listeAffaires'); ?>"><i class="fa fa-bank"></i> Réglements</a></li>                                    
                                </ul>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown-grid">
                                <?php if ($this->ion_auth->is_admin()): ?>
                                <a href="<?php echo site_url('ed/dossiers'); ?>">Dossiers</a>
                            <?php endif; ?>
                            <a href="<?php echo site_url('ed/journalier'); ?>"><i class="glyphicon glyphicon-calendar"></i> Journalier</a>
                            <?php if ($this->ion_auth->is_admin()): ?>
                                <a href="<?php echo site_url('ed/hebdomadaire'); ?>"><i class="glyphicon glyphicon-calendar"></i> Hebdomadaire</a>
                                <a href="<?php echo site_url('ed/recurrent'); ?>"><i class="glyphicon glyphicon-repeat"></i> Recurrent</a>
                            <?php endif; ?>

                            </li>                           
                            <li class="dropdown-grid">
                                <a href="<?php echo site_url('ed/logout'); ?>" style="color: orangered;">
                                    <i class="fa fa-sign-out"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>


        <?php endif;
        ?>