<!-- Gestion d'un client ------------------------------------------------------------------------------------------------------ -->
<div class="modal fade" id="modalAddComposant" tabindex="-1" role="dialog" aria-labelledby="Ajouter un composant" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color: white;"> </i></button>
                <h4 class="modal-title">
                    <?php
                    if (isset($composant)):
                        echo 'Modifier le composant : "<strong>' . $composant->getComposantDesignation() . '</strong>"';
                    else:
                        echo 'Ajouter un nouveau composant';
                    endif;
                    ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('artiles/addComposant/', array('class' => 'form-horizontal', 'id' => 'formAddComposant')); ?>
                <input type="hidden" name="addComposantId" id="addComposantId" value="<?= isset($composant) ? $composant->getComposantId() : ''; ?>" >
                <div class="form-group">
                    <label for="addComposantFamilleId" class="col-sm-2 control-label">Famille</label>
                    <div class="col-sm-9">
                        <select name="addComposantFamilleId" id="addComposantFamilleId" class="selectpicker" title="Selectionnez une famille" >
                            <?php
                            if (!empty($familles)):
                                foreach ($familles as $f):
                                    $etat = '';
                                    if (isset($composant) && $composant->getComposantFamilleId() == $f->getFamilleId()):
                                        $etat = 'selected';
                                    endif;
                                    echo '<option value="' . $f->getFamilleId() . '" ' . $etat . ' >' . $f->getFamilleNom() . '</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addComposantDesignation" class="col-sm-2 control-label">Designation</label>
                    <div class="col-sm-9">
                        <input type="text" id="addComposantDesignation" class="form-control" name="addComposantDesignation" value="<?= isset($composant) ? $composant->getComposantDesignation() : ''; ?>" placeholder="Désignation courte">
                    </div>
                </div>
                <div class="form-group">                    
                    <label for="addComposantUniteId" class="col-sm-2 control-label">Unite</label>
                    <div class="col-sm-4">
                        <select name="addComposantUniteId" id="addComposantUniteId" class="form-control">
                            <?php
                            if (!empty($unites)):
                                foreach ($unites as $u):
                                    $etat = '';
                                    if (isset($composant) && $composant->getComposantUniteId() == $u->getUniteId()):
                                        $etat = 'selected';
                                    endif;
                                    echo '<option value="' . $u->getUniteId() . '" ' . $etat . ' >' . $u->getUniteNom() . '</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>

                <?php
                //if (!isset($composant)):
                ?>
<!--                    <div id="addOption">
                        <hr>
                        Vous pouvez ajouter immédiatement le prix de Base ou une première option. (facultatif)<br><br>
                        <div class="form-group">
                            <label for="addOptionNom" class="col-sm-2 control-label">Option</label>
                            <div class="col-sm-6">
                                <input type="text" id="addOptionNom" class="form-control" name="addOptionNom" value="" placeholder="Option">
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="addOptionAchat" class="col-sm-2 control-label">Prix Achat</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" id="addOptionAchat" class="form-control" name="addOptionAchat" value="" placeholder="Prix achat HT">
                                    <span class="input-group-addon"> € </span>
                                </div>
                            </div>
                            <label for="addOptionCoefficient" class="col-sm-2 control-label">Coefficient</label>
                            <div class="col-sm-3">
                                <input type="text" id="addOptionCoefficient" class="form-control" name="addOptionCoefficient" value="2.5" placeholder="Coefficient de vente">
                            </div>
                        </div>

                    </div>-->
                <?php
                //endif;
                ?>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary pull-right" type="submit" id="btnAddComposantSubmit">
                    <?php
                    if (isset($composant)):
                        echo '<i class="fa fa-pencil"></i> Modifier le composant';
                    else:
                        echo '<i class="fa fa-plus"></i> Ajouter';
                    endif;
                    ?>
                </button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>