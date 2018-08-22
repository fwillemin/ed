<div class="container fond">

    <div class="row" style="margin-top : 0px;">
        <div class="col-xs-12 col-sm-9">
            <h2>Planification des affaires et dossiers</h2>
        </div>
        <div class="col-xs-12 col-sm-3">
            <button class="btn btn-primary btnAddDossier" type="button" style="width:100%;"><i class="fas fa-plus"></i> Ajouter un dossier</button>
            <a href="<?= site_url('dossiers/dossiersClos'); ?>" class="btn btn-link pull-right">Dossiers clos</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <table class="table table-bordered" id="tableDossiers" style="width:100%; font-size:12px;"
                   data-toggle="table"
                   data-search="true">
                <thead>
                    <tr>
                        <th data-width="50"></th>
                        <th data-sortable="true">Client</th>
                        <th data-align="center" data-width="160">Pao</th>
                        <th data-align="center" data-width="160">Fabrication</th>
                        <th data-align="center" data-width="160">Pose</th>
                        <th data-align="center" data-width="30"><i class="fas fa-exclamation-triangle"></i></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (!empty($dossiers)):
                        echo '<tr><td colspan="6"><div style="width: 100%; background-color: steelblue; color: #FFF; padding : 10px;font-size: 18px; font-weight: bold;">Dossiers - Planifications hors affaires</div></td></tr>';
                        foreach ($dossiers as $d):
                            $aJourDePlanification = true;
                            ?>
                            <tr style="position:relative;" data-dossierid="<?= $d->getDossierId(); ?>" data-source="planif" data-client="<?= $d->getDossierClient(); ?>" data-objet="<?= $d->getDossierDescriptif(); ?>">

                                <td style="text-align: center; font-size:13px; color:grey;">

                                    <button class="btnInvisible btnModDossier" style="margin-right: 2px; cursor: pointer">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <button class="btnInvisible btnCloseDossier" style="cursor: pointer">
                                        <i class="fas fa-unlock" style="color:green;"></i>
                                    </button>

                                </td>
                                <td>
                                    <span style="font-size:15px; font-weight: bold;"><?= $d->getDossierClient(); ?></span>
                                    <br><?= $d->getDossierDescriptif(); ?>
                                </td>

                                <td>
                                    <?php
                                    $nbPao = 0;
                                    if (!empty($d->getDossierAffectations())):
                                        foreach ($d->getDossierAffectations() as $a):
                                            if ($a->getAffectationType() == 3) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>; position:relative;">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>" data-client="<?= $d->getDossierClient(); ?>" data-objet="<?= $d->getDossierDescriptif(); ?>">
                                                        <button type="button" class="btn btn-link btn-xs btnPlanifNext" style="position:absolute; top:0px; right:0px; color: turquoise;" data-affectid="<?= $a->getAffectationId(); ?>"><i class="fas fa-redo-alt"></i></button>
                                                        <?= date('d/m/y', $a->getAffectationDate()); ?>
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

                                    if ($d->getDossierPao()):
                                        if ($nbPao == 0):
                                            $aJourDePlanification = false;
                                        endif;
                                        ?>
                                        <button class="btn btn-sm btn-link btnAddAffectation" data-type="3" style="font-size:15px; color: #9999ff; padding:2px; text-align: center; width:100%;"><i class="fas fa-plus-circle"></i></button>
                                    <?php endif;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $nbFab = 0;
                                    if (!empty($d->getDossierAffectations())):
                                        foreach ($d->getDossierAffectations() as $a):
                                            if ($a->getAffectationType() == 1) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>; position:relative;">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>" data-client="<?= $d->getDossierClient(); ?>" data-objet="<?= $d->getDossierDescriptif(); ?>">
                                                        <button type="button" class="btn btn-link btn-xs btnPlanifNext" style="position:absolute; top:0px; right:0px; color: turquoise;" data-affectid="<?= $a->getAffectationId(); ?>"><i class="fas fa-redo-alt"></i></button>
                                                        <?= date('d/m/y', $a->getAffectationDate()); ?>
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

                                    if ($d->getDossierFab()):
                                        if ($nbFab == 0):
                                            $aJourDePlanification = false;
                                        endif;
                                        ?>
                                        <button class="btn btn-sm btn-link btnAddAffectation" data-type="1" style="font-size:15px; color: #9999ff; padding:2px; text-align: center; width:100%;"><i class="fas fa-plus-circle"></i></button>
                                    <?php endif;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $nbPose = 0;
                                    if (!empty($d->getDossierAffectations())):
                                        foreach ($d->getDossierAffectations() as $a):
                                            if ($a->getAffectationType() == 2) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>; position:relative;" data-client="<?= $d->getDossierClient(); ?>" data-objet="<?= $d->getDossierDescriptif(); ?>">
                                                        <button type="button" class="btn btn-link btn-xs btnPlanifNext" style="position:absolute; top:0px; right:0px; color: turquoise;" data-affectid="<?= $a->getAffectationId(); ?>"><i class="fas fa-redo-alt"></i></button>
                                                        <?= date('d/m/y', $a->getAffectationDate()); ?>
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

                                    if ($d->getDossierPose()):
                                        if ($nbPose == 0):
                                            $aJourDePlanification = false;
                                        endif;
                                        ?>
                                        <button class="btn btn-sm btn-link btnAddAffectation" data-type="2" style="font-size:15px; color: #9999ff; padding:2px; text-align: center; width:100%;"><i class="fas fa-plus-circle"></i></button>
                                    <?php endif;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (!$aJourDePlanification):
                                        echo '<i class="fas fa-exclamation-triangle" style="color: orangered; font-size:20px;"></i>';
                                    endif;
                                    ?>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>

                    <?php
                    if (!empty($affaires)):
                        echo '<tr><td colspan="6"><div style="width: 100%; background-color: steelblue; color: #FFF; padding : 10px;font-size: 18px; font-weight: bold;">Affaires en cours</div></td></tr>';
                        foreach ($affaires as $affaire):
                            $aJourDePlanification = true;
                            ?>
                            <tr style="position:relative;" data-source="planif" data-affaireid="<?= $affaire->getAffaireId(); ?>" data-client="<?= $affaire->getAffaireClients()[0]->getClientRaisonSociale(); ?>" data-objet="<?= $affaire->getAffaireObjet(); ?>">

                                <td style="text-align: center; font-size:13px; color:grey;">
                                    <a href="<?= site_url('ventes/reloadAffaire/' . $affaire->getAffaireId()); ?>">
                                        <i class="fas fa-file"></i>
                                    </a>
                                </td>
                                <td>
                                    <input type="checkbox" class="editionFicheAtelier" value="1" <?php if ($affaire->getAffaireFicheAtelierEditee()) echo 'checked'; ?> >
                                    <span style="font-weight: bold; font-size:15px;"><?= $affaire->getAffaireClients()[0]->getClientRaisonSociale(); ?></span>
                                    <br><?=
                                    $affaire->getAffaireObjet();
                                    if ($affaire->getAffaireCommandeCertifiee()):
                                        echo '<img src="' . base_url('assets/img/certifieNB.jpg') . '" style="height: 40px;" class="pull-right">';
                                    endif;
                                    ?>
                                </td>
                                <td <?php if ($affaire->getAffairePao() == 0) echo 'class="hachures"'; ?> >
                                    <?php
                                    $nbPao = 0;
                                    if (!empty($affaire->getAffaireAffectations())):
                                        foreach ($affaire->getAffaireAffectations() as $a):
                                            if ($a->getAffectationType() == 3) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>; position:relative;">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>" data-client="<?= $affaire->getAffaireClients()[0]->getClientRaisonSociale(); ?>"  data-objet="<?= $affaire->getAffaireObjet(); ?>">
                                                        <button type="button" class="btn btn-link btn-xs btnPlanifNext" style="position:absolute; top:0px; right:0px; color: turquoise;" data-affectid="<?= $a->getAffectationId(); ?>"><i class="fas fa-redo-alt"></i></button>
                                                        <?= date('d/m/y', $a->getAffectationDate()); ?>
                                                    </div>
                                                    <div class="intervenant">
                                                        <?= $a->getAffectationIntervenant(); ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $nbPao++;
                                            endif;
                                        endforeach;
                                    endif;

                                    if ($affaire->getAffairePao()):
                                        if ($nbPao == 0):
                                            $aJourDePlanification = false;
                                        endif;
                                        ?>
                                        <button class="btn btn-sm btn-link btnAddAffectation" data-type="3" style="font-size:15px; color:#9999ff; padding:2px; text-align: center; width:100%;"><i class="fas fa-plus-circle"></i></button>
                                    <?php endif;
                                    ?>
                                </td>
                                <td <?php if ($affaire->getAffaireFabrication() == 0) echo 'class="hachures"'; ?> >
                                    <?php
                                    $nbFab = 0;
                                    if (!empty($affaire->getAffaireAffectations())):
                                        foreach ($affaire->getAffaireAffectations() as $a):
                                            if ($a->getAffectationType() == 1) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>; position:relative;">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>" data-client="<?= $affaire->getAffaireClients()[0]->getClientRaisonSociale(); ?>"  data-objet="<?= $affaire->getAffaireObjet(); ?>">
                                                        <button type="button" class="btn btn-link btn-xs btnPlanifNext" style="position:absolute; top:0px; right:0px; color: turquoise;" data-affectid="<?= $a->getAffectationId(); ?>"><i class="fas fa-redo-alt"></i></button>
                                                        <?= date('d/m/y', $a->getAffectationDate()); ?>
                                                    </div>
                                                    <div class="intervenant">
                                                        <?= $a->getAffectationIntervenant(); ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $nbFab++;
                                            endif;
                                        endforeach;
                                    endif;

                                    if ($affaire->getAffaireFabrication()):
                                        if ($nbFab == 0):
                                            $aJourDePlanification = false;
                                        endif;
                                        ?>
                                        <button class="btn btn-sm btn-link btnAddAffectation" data-type="1" style="font-size:15px; color:#9999ff; padding:2px; text-align: center; width:100%;"><i class="fas fa-plus-circle"></i></button>
                                    <?php endif;
                                    ?>
                                </td>
                                <td <?php if ($affaire->getAffairePose() == 0) echo 'class="hachures"'; ?> >
                                    <?php
                                    $nbPose = 0;
                                    if (!empty($affaire->getAffaireAffectations())):
                                        foreach ($affaire->getAffaireAffectations() as $a):
                                            if ($a->getAffectationType() == 2) :
                                                ?>
                                                <div class="progHebdo" data-affectid="<?= $a->getAffectationId(); ?>" style="background-color: <?= $a->getAffectationCouleur(); ?>">
                                                    <div class="btnModAffect" style="width:100%; padding: 7px; color: <?= $a->getAffectationFontColor(); ?>; position:relative;" data-client="<?= $affaire->getAffaireClients()[0]->getClientRaisonSociale(); ?>"  data-objet="<?= $affaire->getAffaireObjet(); ?>">
                                                        <button type="button" class="btn btn-link btn-xs btnPlanifNext" style="position:absolute; top:0px; right:0px; color: turquoise;" data-affectid="<?= $a->getAffectationId(); ?>"><i class="fas fa-redo-alt"></i></button>
                                                        <?= date('d/m/y', $a->getAffectationDate()); ?>
                                                    </div>
                                                    <div class="intervenant">
                                                        <?= $a->getAffectationIntervenant(); ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $nbPose++;
                                            endif;
                                        endforeach;
                                    endif;

                                    if ($affaire->getAffairePose()):
                                        if ($nbPose == 0):
                                            $aJourDePlanification = false;
                                        endif;
                                        ?>
                                        <button class="btn btn-sm btn-link btnAddAffectation" data-type="2" style="font-size:15px; color:#9999ff; padding:2px; text-align: center; width:100%;"><i class="fas fa-plus-circle"></i></button>
                                        <?php endif;
                                        ?>
                                </td>
                                <td>
                                    <?php
                                    if (!$aJourDePlanification):
                                        echo '<i class="fas fa-exclamation-triangle" style="color: orangered; font-size:20px;"></i>';
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