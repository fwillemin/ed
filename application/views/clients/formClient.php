<!-- Gestion d'un client ------------------------------------------------------------------------------------------------------ -->
<div class="modal fade" id="modalAddClient" tabindex="-1" role="dialog" aria-labelledby="Ajouter un client" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('clients/addClient/', array('class' => 'form-horizontal', 'id' => 'formAddClient')); ?>
                <input type="hidden" name="addClientId" id="addClientId" value="" >
                
                <div class="form-group">
                    <label for="addClientRaisonSociale" class="col-sm-3 control-label">Raison sociale<span class="asterix">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientRaisonSociale" class="form-control" name="addClientRaisonSociale" value="" required >
                    </div>
                </div>
                <div class="form-group">                    
                    <label for="addClientExoneration" class="col-sm-3 control-label">Exonération de TVA</label>
                    <div class="col-sm-2">
                        <input type="checkbox" id="addClientExoneration" class="" name="addClientExoneration" value="1" >
                    </div>
                    <label for="addClientNumTva" class="col-sm-3 control-label">N° de TVA Intracom</label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientNumTva" class="form-control" name="addClientNumTva" value="" >
                    </div>
                </div>
<!--                <div class="form-group">
                    <label for="addClientNom" class="col-sm-3 control-label">Nom</span></label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientNom" class="form-control" name="addClientNom" value="" >
                    </div>                
                    <label for="addClientPrenom" class="col-sm-1 control-label">Prénom</label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientPrenom" class="form-control" name="addClientPrenom" value="" >
                    </div>
                </div>-->
                <div class="form-group">
                    <label for="addClientAdresse1" class="col-sm-3 control-label">Adresse</label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientAdresse1" class="form-control" name="addClientAdresse1" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientAdresse2" class="col-sm-3 control-label">Complement d'adresse</label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientAdresse2" class="form-control" name="addClientAdresse2" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="addClientCp">Code postal<span class="asterix">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="addClientCp" id="addClientCp" placeholder="Code postal" required value="" >
                    </div>
                    <label class="col-sm-1 control-label" for="addClientVille">Ville<span class="asterix">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="addClientVille" id="addClientVille" placeholder="Ville" required value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientPays" class="col-sm-3 control-label">Pays</label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientPays" class="form-control" name="addClientPays" value="FRANCE" required >
                    </div>
                </div>                
                <div class="form-group">
                    <label for="addClientTelephone" class="col-sm-3 control-label">Téléphone</label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientTelephone"  class="form-control" name="addClientTelephone" value="" placeholder="Fixe uniquement">
                    </div>
                </div>              
            </div>
            <div class="modal-footer">                
                <button class="btn btn-warning pull-right" type="submit" id="btnAddClientSubmit"></button>             
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>