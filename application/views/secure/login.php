<div class="row" style="margin-top:180px;">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4" style="border:1px solid grey; border-radius:10px; padding:15px;  background-color: #000; text-align: center; color: #FFF;">
        <img src="<?php echo base_url('assets/img/logo.png'); ?>" style="height:50px;" >
        <hr>
        <?php echo form_open('secure/tryLogin', array('class' => 'form-horizontal', 'id' => 'formLogin')); ?>
        <div class="form-group">
            <label for="loginId" class="col-xs-4">Identifiants</label>
            <div class="col-xs-6">
                <input type="text" name="login" id="login" value="" placeholder="Identifiant" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="loginPass" class="col-xs-4">Mot de passe</label>
            <div class="col-xs-6">
                <input type="password" name="pass" id="pass" value="" placeholder="Mot de passe" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="glyphicon glyphicon-log-in"></i> Connexion
        </button>
        <?php echo form_close(); ?>
    </div>
</div>