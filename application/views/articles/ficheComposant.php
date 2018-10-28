<div class="container fond">
    <div class="row">
        <div class="col-sm-12">
            <h1>
                <a href="<?= site_url('articles/composantsListe'); ?>">
                    <i class="fa fa-backward"></i>
                </a>
                <?= $composant->getComposantDesignation(); ?>
            </h1>
            <div style="position: absolute; top:5px; right: 5px;">
                <button class="btn btn-default"  data-toggle="modal" data-target="#modalAddComposant">
                    <i class="fa fa-pencil"></i> Modifier le composant
                </button>
                <?php
                $etat = '';
                if (!empty($composant->getComposantCompositions())) {
                    $etat = 'disabled';
                }
                ?>
                <br>
                <button class="btn btn-xs btn-link tooltipped pull-left" title="Double-click" data-placement="left" id="btnCopyComposant">
                    <i class="fa fa-copy"></i> Dupliquer
                </button>
                <button class="btn btn-xs btn-link tooltipped pull-right" <?= $etat; ?> title="Double-click" data-placement="left" id="btnDelComposant">
                    Supprimer
                </button>

            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-12">
            <h4>Options du composant</h4>
            <span class="pull-right" style="font-size:10px; color:grey;">
                (Prix Catalogue - Remise) x Coefficient = Prix de vente
            </span>
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr style="background-color: #d7dce2;">
                        <th colspan="2" style="width: 60px;">Réf</th>
                        <th>Option</th>
                        <th style="width: 100px; text-align: right;">Catalogue</th>
                        <th style="width: 100px; text-align: right;">Remise</th>
                        <th style="width: 100px; text-align: right;">Achat Net</th>
                        <th style="width: 80px; text-align: right;">Coeff</th>
                        <th style="width: 100px; text-align: right;">Vente</th>
                        <th style="width: 40px; text-align: center;"><i class="fa fa-check-circle"></i></th>
                        <th style="width: 40px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($composant->getComposantOptions())):
                        foreach ($composant->getComposantOptions() as $o):
                            $pic = $trash = '';
                            if ($o->getOptionActive() == 1):
                                $pic = '<i class="fa fa-check-circle" style="color: green;"></i>';
                            endif;

                            if ($o->getOptionNbUse() == 0):
                                $trash = '<button class="btnInvisible delOption tooltipped" data-placement="left" data-title="Double-click"><i class="fa fa-trash" style="color: grey;"></i></button>';
                            endif;

                            echo '<tr class="ligneOption" data-optionid="' . $o->getOptionId() . '">'
                            . '<td colspan="2">' . $o->getOptionReference() . '</td>'
                            . '<td>' . $o->getOptionNom() . '</td>'
                            . '<td style="text-align: right;">' . number_format($o->getOptionPrixCatalogue(), 2, '.', ' ') . '</td>'
                            . '<td style="text-align: right; border-right: 2px solid grey; position:relative;">'
                            . $o->getOptionRemise() . '%<i class="fa fa-caret-right" style="position: absolute; top:6px; right: -9px; font-size:20px; color: grey;"></i>'
                            . '</td>'
                            . '<td style="text-align: right;">' . number_format($o->getOptionPrixAchat(), 2, '.', ' ') . '</td>'
                            . '<td style="text-align: right;">' . $o->getOptionCoefficient() . '</td>'
                            . '<td style="text-align: right;">' . number_format($o->getOptionHT(), 2, '.', ' ') . '</td>'
                            . '<td style="text-align: center;" data-optionactive="' . $o->getOptionActive() . '">' . $pic . '</td>'
                            . '<td style="text-align: center;">' . $trash . '</td>'
                            . '</tr>';
                        endforeach;
                    endif;
                    ?>

                    <!-- Nouvelle option -->
                    <?php echo form_open('articles/manageOptions', array('class' => 'form-inline', 'id' => 'formAddOption')); ?>
                <input type="hidden" name="addOptionId" id="addOptionId" value="">
                <input type="hidden" name="addOptionComposantId" id="addOptionComposantId" value="<?= $composant->getComposantId(); ?>">
                <tr style="background-color: #f5f5f5;">
                    <td style="width : 30px; vertical-align: middle;">
                        <button type="button" class="btn btn-link btn-xs" id="btnEraseFormOption">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                    <td style="width: 80px;">
                        <input type="text" name="addOptionReference" id="addOptionReference" value="" placeholder="Réf" class="form-control input-sm">
                    </td>
                    <td>
                        <input type="text" name="addOptionNom" id="addOptionNom" value="" placeholder="Option" class="form-control input-sm">
                    </td>
                    <td>
                        <input style="text-align: right;" type="text" name="addOptionPrixCatalogue" id="addOptionPrixCatalogue" value="" placeholder="Catalogue" class="form-control input-sm">
                    </td>
                    <td style="text-align: right; border-right: 2px solid grey; position:relative;">
                        <input style="text-align: right;" type="text" name="addOptionRemise" id="addOptionRemise" value="0" placeholder="Remise" class="form-control input-sm">
                        <i class="fa fa-caret-right" style="position: absolute; top: 11px; right: -9px; font-size:20px; color: grey;"></i>
                    </td>
                    <td>
                        <input style="text-align: right;" type="text" name="addOptionPrixAchatNet" id="addOptionPrixAchatNet" value="0" placeholder="Net" class="form-control input-sm">
                    </td>
                    <td>
                        <input style="text-align: right;" type="text" name="addOptionCoefficient" id="addOptionCoefficient" value="2.5" placeholder="Coefficient" class="form-control input-sm">
                    </td>
                    <td>
                        <input style="text-align: right;" type="text" id="addOptionVente" value="" placeholder="Vente HT" class="form-control input-sm" disabled >
                    </td>
                    <td style="text-align: center; color: #cc84ae">
                        <input type="checkbox" name="addOptionActive" id="addOptionActive" style="vertical-align: middle;" checked >
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-circle-right"></i>
                        </button>
                    </td>
                </tr>
                <?php echo form_close(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10">
            <span style="font-size : 15px; font-weight: bold; color: #007fff;">Articles utilisant ce composant</span><br>
            <table class="table table-condensed table-bordered" style="font-size:11px;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="width: 40px; text-align: center;">ID</th>
                        <th style="width: 350px;">Article</th>
                        <th style="width: 70px; text-align: right;">Achat</th>
                        <th style="width: 70px; text-align: right;">Vente</th>
                        <th style="width: 70px; text-align: right;">Marge</th>
                        <th style="width: 70px; text-align: right;">Taux</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($articles)):
                        foreach ($articles as $a):
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <a href="<?= site_url('articles/ficheArticle/' . $a->getArticleId()); ?>">
                                        <?= $a->getArticleId(); ?>
                                    </a>
                                </td>
                                <td><?= $a->getArticleDesignation(); ?></td>
                                <td style="text-align: right;"><?= number_format($a->getArticleAchatHT(), 2, ',', ' ') . '€'; ?></td>
                                <td style="text-align: right;"><?= number_format($a->getArticleHT(), 2, ',', ' ') . '€'; ?></td>
                                <td style="text-align: right;">
                                    <?= number_format($a->getArticleHT() - $a->getArticleAchatHT(), 2, ',', ' ') . '€'; ?>
                                </td>
                                <td style="text-align: right;">
                                    <?php
                                    if ($a->getArticleAchatHT() > 0):
                                        $tauxMarge = round(($a->getArticleHT() / $a->getArticleAchatHT() - 1) * 100, 2);
                                        if ($tauxMarge > 0):
                                            $tauxColor = 'green';
                                        else:
                                            $tauxColor = 'orangered';
                                        endif;
                                    else:
                                        $tauxColor = 'grey';
                                        $tauxMarge = 'NC';
                                    endif;
                                    echo '<span class="pull-right" style="color: ' . $tauxColor . ';">' . $tauxMarge . '%</span>';
                                    ?>
                                </td>

                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>

        </div>
    </div>


</div>
<?php include('formComposant.php'); ?>