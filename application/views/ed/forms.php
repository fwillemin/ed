<?php
/**
 * Formulaires dans des Modals pour 
 * - Ajout de dossiers
 * - Ajout d'affectations
 * - Ajout reccurent
 */
?>

<div class="modal fade" id="modalAddDossier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: grey; color: orange; text-align: center; border: 1px solid orange;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('ed/addDossier', array('class' => 'form-horizontal', 'id' => 'formAddDossier')); ?>
                <input type="hidden" name="addDossierId" id="addDossierId" value="" >
                <div class="form-group">
                    <label for="addDossierClient" class="col-xs-3">Client</label>
                    <div class="col-xs-9">
                        <input type="text" name="addDossierClient" id="addDossierClient" class="form-control" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addDossierDescriptif" class="col-xs-3">Descriptif</label>
                    <div class="col-xs-9">
                        <textarea name="addDossierDescriptif" id="addDossierDescriptif" rows="3" class="form-control" ></textarea>
                    </div>
                </div>
                <div class="form-group" style="display:none;">
                    <label for="addDossierDateSortie" class="col-xs-3">Sortie</label>
                    <div class="col-xs-9 col-sm-6">
                        <input type="date" name="addDossierDateSortie" id="addDossierDateSortie" class="form-control" value="" >
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 form-group">
                        <label for="addDossierPao" class="col-xs-12 col-sm-12">
                            <input type="checkbox" name="addDossierPao" id="addDossierPao" class="" value="1" >
                            PAO
                        </label>
                        <br>
                        <div id="formDossierAffectPao" style="padding:4px;">

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 form-group">
                        <label for="addDossierFab" class="col-xs-12 col-sm-12">
                            <input type="checkbox" name="addDossierFab" id="addDossierFab" class="" value="1" >
                            Fabrication
                        </label>
                        <br>
                        <div id="formDossierAffectFab" style="padding:4px;">

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 form-group">
                        <label for="addDossierPose" class="col-xs-12 col-sm-12">
                            <input type="checkbox" name="addDossierPose" id="addDossierPose" class="" value="1" >
                            Pose
                        </label>
                        <br>
                        <div id="formDossierAffectPose" style="padding:4px;">

                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning btn" style="width: 100%;" id="btnSubmitFormAddDossier"></button>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer" style="padding:3px; text-align: center;">
                <button class="btn btn-xs btn-danger" id="btnDelDossier" type="button" style="display: none;">
                    <i class="glyphicon glyphicon-trash"></i> Supprimer ce dossier, ses affectations et tous les éléments le concernant.
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddAffectation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: grey; color: orange; text-align: center; border: 1px solid orange;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php
                if ($this->ion_auth->is_admin()):
                    echo '<strong>Client : </strong><span id="textClient"></span>';
                    echo '<br><strong>Descriptif : </strong><span id="textDescriptif"></span><hr>';

                    echo form_open('ed/addAffectation', array('class' => 'form-horizontal', 'id' => 'formAddAffectation'));
                    ?>
                    <input type="hidden" name="addAffectId" id="addAffectId" value="" >

                    <div class="form-group">
                        <label for="addAffectDossierId" class="col-xs-3">Dossier</label>
                        <div class="col-xs-9 col-sm-6">
                            <select class="form-control" name="addAffectDossierId" id="addAffectDossierId">
                                <?php
                                if (!empty($dossiers)):
                                    foreach ($dossiers as $d):
                                        if ($d->getDossierClos() == 0):
                                            echo '<option value="' . $d->getDossierId() . '">' . $d->getDossierClient() . '</option>';
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addAffectType" class="col-xs-3">Type</label>
                        <div class="col-xs-9 col-sm-6">
                            <select class="form-control" name="addAffectType" id="addAffectType">
                                <option value="3">Pao</option>
                                <option value="1">Fabrication</option>
                                <option value="2">Pose</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="addAffectDate" class="col-xs-3">Date</label>
                        <div class="col-xs-9 col-sm-5">
                            <input type="date" name="addAffectDate" id="addAffectDate" class="form-control" value="" >
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <select name="addAffectNbJour" id="addAffectNbJour" class="form-control">
                                <?php
                                for ($i = 1; $i < 6; $i++):
                                    echo '<option value="' . $i . '">' . $i . ' jours </option>';
                                endfor;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addAffectEquipeId" class="col-xs-3">Equipe</label>
                        <div class="col-xs-9 col-sm-5">
                            <select name="addAffectEquipeId" id="addAffectEquipeId" class="form-control" >
                                <?php
                                if (!empty($equipes)):
                                    foreach ($equipes as $e):
                                        echo '<option value="' . $e->getEquipeId() . '">' . $e->getEquipeNom() . '</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addAffectIntervenant" class="col-xs-3">Intervenant(s)</label>
                        <div class="col-xs-9 col-sm-9">
                            <input type="text" name="addAffectIntervenant" id="addAffectIntervenant" class="form-control" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addAffectCommentaire" class="col-xs-3">Commentaire</label>
                        <div class="col-xs-9 col-sm-9">
                            <textarea rows="3" name="addAffectCommentaire" id="addAffectCommentaire" class="form-control"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning btn" style="width: 100%;" id="btnSubmitFormAddAffect"></button>
                    <?php
                    echo form_close();
                else:
                    echo 'Espace réservé aux administrateurs';
                endif;
                ?>
            </div>
            <div class="modal-footer" style="padding:3px; text-align: center;">
                <button class="btn btn-xs btn-danger" id="btnDelAffect" type="button" style="display: none;">
                    <i class="glyphicon glyphicon-trash"></i> Supprimer cette affectation
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAddRecurrent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: grey; color: orange; text-align: center; border: 1px solid orange;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('ed/addRecurrent', array('class' => 'form-horizontal', 'id' => 'formAddRecurrent')); ?>
                <input type="hidden" name="addRecurrentId" id="addRecurrentId" value="" >

                <div class="form-group">
                    <label for="addRecurrentCritere" class="col-xs-3">Critère</label>
                    <div class="col-xs-9 col-sm-6">
                        <input type="text" name="addRecurrentCritere" id="addRecurrentCritere" class="form-control" value="" >
                        <span style="color:grey; font-size:11px;">
                            lundi - samedi = toutes les semaines le X<br>
                            1 à 31 = tous les X du mois<br>
                            jj-mm = à date tous les ans
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addRecurrentEquipeId" class="col-xs-3">Equipe</label>
                    <div class="col-xs-9 col-sm-5">
                        <select name="addRecurrentEquipeId" id="addRecurrentEquipeId" class="form-control" >
                            <?php
                            if (!empty($equipes)):
                                foreach ($equipes as $e):
                                    echo '<option value="' . $e->getEquipeId() . '">' . $e->getEquipeNom() . '</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addRecurrentCommentaire" class="col-xs-3">Commentaire</label>
                    <div class="col-xs-9 col-sm-9">
                        <textarea rows="3" name="addRecurrentCommentaire" id="addRecurrentCommentaire" class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning btn" style="width: 100%;" id="btnSubmitFormAddRecurrent"></button>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer" style="padding:3px; text-align: center;">
                <button class="btn btn-xs btn-danger" id="btnDelRecurrent" type="button" style="display: none;">
                    <i class="glyphicon glyphicon-trash"></i> Supprimer cette récurrence
                </button>
            </div>
        </div>
    </div>
</div>
