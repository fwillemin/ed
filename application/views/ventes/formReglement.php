<!-- Formulaire de gestion des réglements -->
<div class="modal fade" id="modalAddReglement" tabindex="-1" role="dialog" aria-labelledby="Ajouter un client" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" id="avertissementModReglement">
                    <strong>Attention !!</strong><br>Modifier un réglement enregistre un historique.<br> Veillez à utiliser le bouton "Annuler" si vous ne modifiez rien.
                </div>
                <?php echo form_open('facturation/addReglement/', array('class' => 'form-horizontal', 'id' => 'formAddReglement')); ?>
                <input type="hidden" name="addReglementSourceId" id="addReglementSourceId" value="" >
                <input type="hidden" name="addReglementAffaireId" id="addReglementAffaireId" value="<?= $affaire->getAffaireId(); ?>" >
                <div class="form-group">
                    <label for="addReglementDate" class="col-sm-4 control-label">Date</label>
                    <div class="col-sm-5">
                        <input type="date" name="addReglementDate" id="addReglementDate" value="<?= date('Y-m-d'); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="addReglementClientId" class="col-sm-4 control-label">Client</label>
                    <div class="col-sm-7">
                        <select name="addReglementClientId" id="addReglementClientId" class="form-control" title="Choisir un client" required >
                            <option value="0">-</option>
                            <?php
                            foreach ((array) $this->session->userdata('affaireClients') as $c):
                                echo '<option value="' . $c->clientId . '">' . $c->clientRaisonSociale . '</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addReglementFactureId" class="col-sm-4 control-label">Facture réglée</label>
                    <div class="col-sm-5">
                        <select name="addReglementFactureId" id="addReglementFactureId" class="form-control">
                            <option value="0">-</option>
                            <?php
                            if ($affaire && !empty($affaire->getAffaireFactures())):
                                foreach ($affaire->getAffaireFactures() as $f):
                                    if ($f->getFactureSolde() > 0):
                                        echo '<option value="' . $f->getFactureId() . '" data-factureclientid="' . $f->getFactureClientId() . '" class="reglementChoixFacture">'
                                        . 'FA' . $f->getFactureId() . '| Solde : ' . number_format($f->getFactureSolde(), 2, ',', ' ') . '€'
                                        . '</option>';
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addReglementMontant" class="col-sm-4 control-label">Montant</label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" name="addReglementMontant" id="addReglementMontant" class="form-control" required >
                            <span class="input-group-addon">€</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addReglementType" class="col-sm-4 control-label">Type</label>
                    <div class="col-sm-5">
                        <select name="addReglementType" id="addReglementType" class="form-control">
                            <option value="1">Acompte</option>
                            <option value="2" selected>Réglement</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addReglementMode" class="col-sm-4 control-label">Mode de réglement</label>
                    <div class="col-sm-5">
                        <select name="addReglementMode" id="addReglementMode" class="form-control" required >
                            <option value="1" selected>Chèque</option>
                            <option value="2">Virement</option>
                            <option value="3">Espèces</option>
                            <option value="4">Carte bancaire</option>
                            <option value="5">Traite</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary pull-right" type="submit" id="btnAddReglementSubmit">
                    <i class="fa fa-money"></i> Enregistrer le reglement
                </button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>