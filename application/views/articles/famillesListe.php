<div class="container">
    <div class="row" style="background-color: #FFF;">
        <div class="col-sm-6 col-sm-offset-2">
            <h3>Gestion des familles de composants</h3>
            <table class="table table-bordered table-condensed" id="tableFamilles" style="font-size:12px;">
                <thead>
                    <tr>
                        <th colspan="2" style="width: 300px;">Famille</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if( !empty($familles) ):
                        foreach ( $familles as $f ): ?>
                    <tr data-familleid="<?= $f->getFamilleId(); ?>">
                        <td colspan="2">
                            <input type="text" class="form-control setFamille" value="<?= $f->getFamilleNom(); ?>" >
                        </td>
                        <td style="text-align: right;">
                            <button class="btn btn-link delFamille tooltipped" data-placement="left" title="Double-click" style="color: grey;">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php   
                        endforeach;
                    endif; ?>
                    
                    <?php echo form_open('articles/manageFamilles', array('class' => 'form-inline', 'id' => 'formAddFamille')); ?>                                     
                    <tr style="background-color: #f5f5f5;">
                        <td style="width : 30px; vertical-align: middle;">
                            <button type="button" class="btn btn-link btn-xs" id="btnEraseFormFamille" >
                                <i class="fa fa-times-rectangle-o"></i>
                            </button>
                        </td>
                        <td>
                            <input type="text" name="addFamilleNom" id="addFamilleNom" value="" placeholder="Famille" class="form-control input-sm">
                        </td>                        
                        <td style="width: 80px;">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-arrow-circle-right"></i> Ajouter
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

           
        </div>
        <div class="col-sm-2">
            <div class="alert alert-info" style="margin-top: 55px;">
                <strong>Note :</strong><br>
                La suppression d'une famille entraîne le non classement de tous les composants qui lui appartenaient.
            </div>
        </div>
    </div>
</div>