<?php
/**
 *  Gestion de la facturation d'une affaire
 */
?>
<div class="modal fade" id="modalFacturation" tabindex="-1" role="dialog" aria-labelledby="Facturer une affaire" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"><strong>Facturation de l'affaire <?= $affaire->getAffaireId(); ?></strong></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('facturation/addFacture/', array('class' => 'form-horizontal', 'id' => 'formAddFacture')); ?>
                <input type="hidden" name="addFactureAffaireId" id="addFactureAffaireId" value="<?= $affaire->getAffaireId(); ?>" >
                <div class="form-group">
                    <label for="addFactureClientId" class="col-sm-3 control-label">Client</label>
                    <div class="col-sm-7">
                        <select name="addFactureClientId" id="addFactureClientId" class="selectpicker" title="Choisir un client" required >
                            <?php
                            foreach ((array) $this->session->userdata('affaireClients') as $c):
                                echo '<option value="' . $c->clientId . '" data-echeance="' . $c->clientEcheancePaiement . '">' . $c->clientRaisonSociale . '</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addFactureMode" class="col-sm-3 control-label">Mode de réglement</label>
                    <div class="col-sm-5">
                        <select name="addFactureMode" id="addFactureMode" class="selectpicker" required >
                            <option value="1" selected>Chèque</option>
                            <option value="2">Virement</option>
                            <option value="3">Espèces</option>
                            <option value="4">Carte bancaire</option>
                            <option value="5">Traite</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addFactureEcheancePaiement" class="col-sm-3 control-label">Echeance</label>
                    <div class="col-sm-4">
                        <select name="addFactureEcheancePaiement" id="addFactureEcheancePaiement" class="form-control">
                            <option value="1">A récéption de facture</option>
                            <option value="4">A récéption de facture - Escompte 3%</option>
                            <option value="2">A 30 jours</option>
                            <option value="3">A 45 jours</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addFactureObjet" class="col-sm-3 control-label">Objet</label>
                    <div class="col-sm-9">
                        <input type="text" name="addFactureObjet" id="addFactureObjet" value="<?= $this->session->userdata('affaireObjet'); ?>" class="form-control" >
                    </div>
                </div>
                <hr>
                <table class="table table-striped table-bordered" style="font-size:12px;" >
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th style="text-align: right;">Unitaire</th>
                            <th style="text-align: center;">Qte</th>
                            <th style="text-align: right;">Total</th>
                            <th style="width: 100px;"><i class="fa fa-calculator"></i></th>
                            <th style="width: 150px;">Quota</th>
                            <th style="width: 100px;">Restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($this->cart->contents())):
                            foreach ($this->cart->contents() as $item):
                                ?>
                                <tr data-rowid="<?= $item['rowid']; ?>">
                                    <td>
                                        <span style="font-weight: bold;"><?= $item['name']; ?></span>
                                    </td>
                                    <td style="text-align: right;">
                                        <?= number_format($item['price'], 2, ',', ' '); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?= number_format($item['qty'], 2, ',', ' '); ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?= number_format($item['subtotal'], 2, ',', ' '); ?>
                                    </td>
                                    <td>
                                        <select class="form-control input-sm calcQuota" data-subtotal="<?= $item['subtotal']; ?>" >
                                            <option value="100" selected >100%</option>
                                            <option value="0">0%</option>
                                            <option value="10">10%</option>
                                            <option value="20">20%</option>
                                            <option value="25">25%</option>
                                            <option value="33.33">33.33%</option>
                                            <option value="40">40%</option>
                                            <option value="50">50%</option>
                                            <option value="60">60%</option>
                                            <option value="66.66">66.66%</option>
                                            <option value="75">75%</option>
                                            <option value="80">80%</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="col-xs-12 input-group">
                                            <input type="text" class="form-control input-sm" value="<?= $item['subtotal']; ?>"  style="text-align: right; color:green; font-weight: bold;">
                                            <span class="input-group-addon">
                                                €
                                            </span>
                                        </div>
                                    </td>
                                    <td style="text-align: right;">
                                        <?= number_format($item['resteAFacturer'], 2, ',', ' '); ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>



            <div class="modal-footer">
                <button class="btn btn-primary pull-right" type="submit" id="btnAddFactureSubmit">
                    <i class="fa fa-file"></i> Enregistrer la facture (définitif)
                </button>
                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>