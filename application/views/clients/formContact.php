<!-- Formulaire de gestion des contacts -->
<div class="modal fade" id="modalAddContact" tabindex="-1" role="dialog" aria-labelledby="Ajouter un client" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('clients/addContact/', array('class' => 'form-horizontal', 'id' => 'formAddContact')); ?>
                <input type="hidden" name="addContactId" id="addContactId" value="" >
                <input type="hidden" name="addContactClientId" id="addContactClientId" value="<?= $client->getClientId(); ?>" >

                <div class="form-group">
                    <label for="addContactNom" class="col-sm-3 control-label">Nom</span></label>
                    <div class="col-sm-3">
                        <select name="addContactCivilite" class="form-control">
                            <option>M.</option>
                            <option>Mme.</option>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" id="addContactNom" class="form-control" name="addContactNom" value="" required >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addContactPrenom" class="col-sm-3 control-label">Prénom</label>                    
                    <div class="col-sm-8">
                        <input type="text" id="addContactPrenom" class="form-control" name="addContactPrenom" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addContactFonction" class="col-sm-3 control-label">Fonction</label>
                    <div class="col-sm-8">
                        <input type="text" id="addContactFonction"  class="form-control" name="addContactFonction" value="" placeholder="Fonction">
                    </div>
                </div>
                <div class="form-group">
                    <label for="addContactEmail" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" id="addContactEmail"  class="form-control" name="addContactEmail" value="" placeholder="Email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="addContactTelephone" class="col-sm-3 control-label">Téléphone</label>
                    <div class="col-sm-8">
                        <input type="text" id="addContactTelephone"  class="form-control" name="addContactTelephone" value="" placeholder="Fixe uniquement">
                    </div>
                </div>
                <div class="form-group">
                    <label for="addContactPortable" class="col-sm-3 control-label">Portable</label>
                    <div class="col-sm-8">
                        <input type="text" id="addContactPortable"  class="form-control" name="addContactPortable" value="" placeholder="06 ou 07">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary pull-right" type="submit" id="btnAddContactSubmit"></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>