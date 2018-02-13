<div class="container fond">
    <div class="row">
        <div class="col-sm-12">
            <h1>
                <a href="<?= site_url('articles/articlesListe'); ?>">
                    <i class="fa fa-backward"></i>
                </a>
                <?= $article->getArticleDesignation(); ?>
            </h1>
            <div style="position: absolute; top:5px; right: 5px;">
                <button class="btn btn-default" data-toggle="modal" data-target="#modalAddArticle">
                    <i class="fa fa-pencil"></i> Modifier l'article
                </button>
                <br>
                <button class="btn btn-xs btn-link tooltipped pull-left" title="Double-click" data-placement="left" id="btnCopyArticle">
                    <i class="fa fa-copy"></i> Dupliquer
                </button>
                <button class="btn btn-xs btn-link tooltipped pull-right" title="Double-click" data-placement="left" id="btnDelArticle">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <span>
                Famille : <strong><?php
                    if ($article->getArticleFamilleId()):
                        echo $article->getArticleFamille()->getFamilleNom();
                    endif;
                    ?>
                </strong><br>
                <?= nl2br($article->getArticleDescription()); ?>
            </span>
        </div>
        <div class="col-sm-4">
            <div class="cadreInfo">
                Marge : <strong><?= number_format($article->getArticleHT() - $article->getArticleAchatHT(), 2, ',', ' ') . '€'; ?></strong>
                <?php
                if ($article->getArticleAchatHT() > 0):
                    $tauxMarge = round(($article->getArticleHT() / $article->getArticleAchatHT() - 1) * 100, 2);
                    if ($tauxMarge > 0):
                        $tauxColor = 'green';
                    else:
                        $tauxColor = 'orangered';
                    endif;
                    echo '<span class="pull-right" style="color: ' . $tauxColor . ';">' . $tauxMarge . '%</span>';
                endif;
                ?>
            </div>
        </div>
    </div>
    <div class="row" style="background-color: #f4f4f4; margin: 20px 0px 10px 0px; border-bottom: 1px solid grey; border-top: 1px solid grey;">
        <div class="col-sm-12" style="padding: 10px;">

            <span style="font-size : 15px; font-weight: bold; color: #007fff;">Ajouter un composant à l'article</span><br>
            <?php echo form_open('article/manageCompositions', array('class' => 'form-inline', 'id' => 'formAddComposition')); ?>
            <input type="hidden" name="addCompositionId" id="addCompositionId" value="" >
            <input type="hidden" name="addCompositionArticleId" id="addCompositionArticleId" value="<?= $article->getArticleId(); ?>" >
            <select class="selectpicker show-tick" name="addCompositionComposantId" id="composantChoix" data-width="500" data-live-search="true" data-header="Choix du composant" title="Selectionnez un composant">
                <?php
                if (!empty($composants)):
                    $famille = '';
                    echo '<optgroup label="Non classés">';
                    foreach ($composants as $c):
                        if ($c->getComposantFamille() && $famille != $c->getComposantFamille()->getFamilleNom()):
                            if ($famille != ''):
                                echo '</optgroup>';
                            endif;
                            echo '<optgroup label="' . $c->getComposantFamille()->getFamilleNom() . '">';
                            $famille = $c->getComposantFamille()->getFamilleNom();
                        endif;
                        echo '<option value="' . $c->getComposantId() . '" data-subtext="(' . $c->getComposantUnite()->getUniteSymbole() . ')">' . $c->getComposantDesignation() . '</option>';
                    endforeach;
                    echo '</optgroup>';
                endif;
                ?>
            </select>
            <select class="selectpicker" name="addCompositionOptionId[]" id="optionChoix" style="width: 200px;" required data-header="Sélectionnez une option" title="Sélectionnez une option" multiple data-actions-box="true"></select>
            <div class="input-group">
                <input type="text" class="form-control" name="addCompositionQte" id="addCompositionQte" value="0" placeholder="Quantité" required style="text-align: right; width: 80px;">
                <span class="input-group-addon" id="uniteComposant"></span>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Ajouter ce composant</button>
            <?php echo form_close(); ?>

        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <span style="font-size : 15px; font-weight: bold; color: #007fff;">Composition de l'article</span><br>
            <table class="table table-condensed table-bordered" id="tableArticleComposition">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th>Composant</th>
                        <th>Option</th>
                        <th style="width: 120px;">Qte</th>
                        <th style="width: 100px; text-align: right;">PU</th>
                        <th style="width: 150px; text-align: right;">Total</th>
                        <th style="width: 30px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($article->getArticleCompositions())):
                        foreach ($article->getArticleCompositions() as $c):
                            ?>
                            <tr data-compositionid="<?= $c->getCompositionId(); ?>">
                                <td>
                                    <a href="<?= site_url('articles/ficheComposant/' . $c->getCompositionComposant()->getComposantId()); ?>">
                                        <?= $c->getCompositionComposant()->getComposantDesignation(); ?>
                                    </a>
                                </td>
                                <td><?= $c->getCompositionOption()->getOptionNom(); ?></td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="modCompositionQte form-control" value="<?= $c->getCompositionQte(); ?>" style="text-align: right;" >
                                        <span class="input-group-addon" style="min-width: 40px;">
                                            <?= $c->getCompositionComposant()->getComposantUnite()->getUniteSymbole(); ?>
                                        </span>
                                    </div>
                                </td>
                                <td style="text-align: right;"><?= number_format($c->getCompositionOption()->getOptionHT(), 2, ',', ' ') . '€'; ?></td>
                                <td style="text-align: right;"><?= number_format(round($c->getCompositionOption()->getOptionHT() * $c->getCompositionQte(), 2), 2, ',', ' ') . '€'; ?></td>
                                <td style="text-align: center;">
                                    <button class="btnInvisible delComposition tooltipped" data-placement="left" data-title="Double-click">
                                        <i class="fa fa-trash" style="color: grey;"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                    <tr style="font-size:15px; font-weight: bold; text-align: right;">
                        <td colspan="4" >
                            Total Article
                        </td>
                        <td style="text-align: right;" id="totalArticle">
                            <?= number_format($article->getArticleHT(), 2, ',', ' ') . '€'; ?>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


</div>
<?php include('formArticle.php'); ?>