
<?= form_open('maquettes/addMaquette/', array('class' => 'form-horizontal', 'id' => 'formAddMaquette')); ?>
<input type="hidden" name="addMaquetteId" id="addMaquetteId" value="<?= !empty($maquette) ? $maquette->getMaquetteId() : ''; ?>" >
<div class="form-group">
    <label for="addMaquetteDateCreation" class="col-sm-3 control-label">Date du RDV</label>
    <div class="col-sm-5">
        <input type="date" name="addMaquetteDateCreation" id="addMaquetteDateCreation" value="<?= !empty($maquette) ? date('Y-m-d', $maquette->getMaquetteDateCreation()) : date('Y-m-d'); ?>" class="form-control" required>
    </div>
</div>

<div class="form-group">
    <label for="addMaquetteClientText" class="col-sm-3 control-label">Client</label>
    <div class="col-sm-7">
        <input type="text" name="addMaquetteClientText" id="addMaquetteClientText" value="<?= !empty($maquette) ? $maquette->getMaquetteClientText() : ''; ?>" class="form-control">
        <select name="addMaquetteClientId" id="addMaquetteClientId" class="selectpicker" data-live-search="true" data-width="400" required >
            <option value="0">-</option>
            <?php
            if (!empty($clients)):
                foreach ($clients as $client):
                    $select = false;
                    if (!empty($maquette) && $maquette->getMaquetteClientId() && $client->getClientId() == $maquette->getMaquetteClientId()):
                        $select = true;
                    endif;
                    echo '<option value="' . $client->getClientId() . '" ' . ($select ? 'selected' : '') . '>' . $client->getClientRaisonSociale() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label for="addMaquetteDateLimite" class="col-sm-3 control-label">Date limite</label>
    <div class="col-sm-5">
        <input type="date" name="addMaquetteDateLimite" id="addMaquetteDateLimite" value="<?= !empty($maquette) ? date('Y-m-d', $maquette->getMaquetteDateLimite()) : ''; ?>" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label for="addMaquetteDescription" class="col-sm-3 control-label">Description</label>
    <div class="col-sm-9">
        <textarea class="form-control" name="addMaquetteDescription" id="addMaquetteDescription" rows="5"><?= !empty($maquette) ? $maquette->getMaquetteDescription() : ''; ?></textarea>
    </div>
</div>
<div class="form-group">
    <label for="addMaquetteDifficulte" class="col-sm-3 control-label">Difficulté</label>
    <div class="col-sm-5">
        <input type="range" class="custom-range" min="1" max="3" id="addMaquetteDifficulte" name="addMaquetteDifficulte" value="<?= !empty($maquette) ? $maquette->getMaquetteDifficulte() : 1; ?>">
    </div>
</div>
<div class="form-group">
    <label for="addMaquettePathFiles" class="col-sm-3 control-label">Emplacement fichiers</label>
    <div class="col-sm-9">
        <input type="text" name="addMaquettePathFiles" id="addMaquettePathFiles" class="form-control" value="<?= !empty($maquette) ? $maquette->getMaquettePathFiles() : ''; ?>">
    </div>
</div>
<div class="form-group">
    <div class="col-xs-12" style="text-align: center;">
        <button class="btn btn-primary" type="submit" id="btnAddMaquetteSubmit">
            <i class="fa fa-save"></i> Enregistrer la maquette
        </button>
    </div>
</div>
<?= form_close(); ?>
