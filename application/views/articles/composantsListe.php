<div class="container">
    <div class="row" style="background-color: #FFF;">
        <div class="col-xs-12">

            <div class="btn-group">
                <button type="button" class="btn btn-primary" id="btnAddComposant"  data-toggle="modal" data-target="#modalAddComposant">
                    <i class="fa fa-plus"></i> Ajouter un composant
                </button>
                <a class="btn btn-default" href="<?= site_url('articles/famillesListe'); ?>" target="_blank">
                    Gérer les familles
                </a>
            </div>

            <table id="tableComposants" style="font-size:12px;">

            </table>

            <ul id="context-menu" class="dropdown-menu" style="font-size:12px; border-top:1px solid grey;">
                <li data-item="fiche" style="cursor:pointer;"><a style="color: #ef8d1c;"><i class="glyphicon glyphicon-user"></i> Fiche Composant</a></li>                
            </ul>
        </div>
    </div>
</div>
<?php include('formComposant.php'); ?>