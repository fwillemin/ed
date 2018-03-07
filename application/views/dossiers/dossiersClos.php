<div class="container fond">

    <div class="row" style="margin-top : 0px;">
        <div class="col-xs-12">
            <h2>Dossiers clôturés</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <table class="table table-bordered" id="tableDossiers" style="width:100%; font-size:12px;"
                   data-toggle="table"
                   data-search="true"
                   data-pagination="true"
                   data-page-size="50">
                <thead>
                    <tr>
                        <th data-width="25" data-align="center"></th>
                        <th data-sortable="true">Client</th>
                        <th data-align="center" data-width="160">Pao</th>
                        <th data-align="center" data-width="160">Fabrication</th>
                        <th data-align="center" data-width="160">Pose</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (!empty($dossiers)):
                        foreach ($dossiers as $d):
                            ?>
                            <tr style="position:relative;" data-dossierid="<?= $d->getDossierId(); ?>" data-client="<?= $d->getDossierClient(); ?>"  data-objet="<?= $d->getDossierDescriptif(); ?>">

                                <td style="text-align: center; font-size:13px; color:grey;">

                                    <button class="btnInvisible btnOpenDossier" style="padding-right: 2px; cursor: pointer">
                                        <i class="fas fa-lock" style="color:orangered;"></i>
                                    </button>

                                </td>
                                <td>
                                    <span style="font-size:15px; font-weight: bold;"><?= $d->getDossierClient(); ?></span>
                                    <br><?= $d->getDossierDescriptif(); ?>
                                </td>

                                <td <?php if ($d->getDossierPao() == 0) echo 'class="hachures"'; ?> >
                                    <?php
                                    $nbPao = 0;
                                    if (!empty($d->getDossierAffectations())):
                                        foreach ($d->getDossierAffectations() as $a):
                                            if ($a->getAffectationType() == 3) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>; position:relative;">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>">
                                                        <?php
                                                        echo date('d/m/y', $a->getAffectationDate()) . ' - <strong>' . $a->getAffectationEquipe() . '</strong>';
                                                        ?>
                                                    </div>
                                                    <div class="intervenant">
                                                        <?= $a->getAffectationIntervenant() ?: '-'; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $nbPao++;
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </td>
                                <td <?php if ($d->getDossierFab() == 0) echo 'class="hachures"'; ?> >
                                    <?php
                                    $nbFab = 0;
                                    if (!empty($d->getDossierAffectations())):
                                        foreach ($d->getDossierAffectations() as $a):
                                            if ($a->getAffectationType() == 1) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>; position:relative;">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>">
                                                        <?= date('d/m/y', $a->getAffectationDate()) . ' - <strong>' . $a->getAffectationEquipe() . '</strong>'; ?>
                                                    </div>
                                                    <div class="intervenant">
                                                        <?= $a->getAffectationIntervenant() ?: '-'; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $nbFab++;
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </td>
                                <td <?php if ($d->getDossierPose() == 0) echo 'class="hachures"'; ?> >
                                    <?php
                                    $nbPose = 0;
                                    if (!empty($d->getDossierAffectations())):
                                        foreach ($d->getDossierAffectations() as $a):
                                            if ($a->getAffectationType() == 2) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>">
                                                        <?php
                                                        echo date('d/m/y', $a->getAffectationDate()) . ' - <strong>' . $a->getAffectationEquipe() . '</strong>';
                                                        ?>
                                                    </div>
                                                    <div class="intervenant">
                                                        <?= $a->getAffectationIntervenant() ?: '-'; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $nbPose++;
                                            endif;
                                        endforeach;
                                    endif;
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
<?php include('application/views/forms.php'); ?>