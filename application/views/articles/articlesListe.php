<div class="container fond">
    <div class="row" style="background-color: #FFF;">
        <div class="col-xs-12">

            <button type="button" class="btn btn-primary" id="btnAddArticle"  data-toggle="modal" data-target="#modalAddArticle">
                <i class="fa fa-plus"></i> Ajouter un article
            </button>


            <table id="tableArticles" style="font-size:12px;">

            </table>

            <ul id="context-menu" class="dropdown-menu" style="font-size:12px; border-top:1px solid grey;">
                <li data-item="fiche" style="cursor:pointer;"><a style="color: #ef8d1c;"><i class="glyphicon glyphicon-user"></i> Fiche Article</a></li>
            </ul>
        </div>
    </div>
</div>
<?php include('formArticle.php'); ?>