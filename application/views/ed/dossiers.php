<div class="container">

    <div class="row" style="margin-top : 0px;">
        <div class="col-xs-12" style="text-align: center;">
            <h2>Les dossiers</h2>
            <button class="btn btn-success btnAddDossier" type="button" style="width:100%;"><i class="glyphicon glyphicon-plus"></i> Ajouter un dossier</button>
            <a href="<?php echo site_url('ed/dossiers/1'); ?>" class="btn btn-link pull-right">Dossiers clos</a>
            <br>
            <br>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <table class="table table-bordered" id="tableDossiers" style="width:100%; font-size:12px;">
                <thead>
                    <th style="width:10px;"></th>
                    <th>Client</th>
                    <th>Description</th>
                    <th style="width:160px;">Pao</th>
                    <th style="width:160px;">Fabrication</th>
                    <th style="width:160px;">Pose</th>
                </thead>
                <tbody>

                    <?php
                    if( !empty($dossiers) ):
                    foreach ($dossiers as $d): ?>
                        <tr style="position:relative; <?php if($d->getDossierClos() == 1) echo 'background-color: #e3e4e5; opacity:0.5;'; ?>" data-dossier="<?php echo $d->getDossierId(); ?>" data-client="<?php echo $d->getDossierClient(); ?>"  data-descriptif="<?php echo $d->getDossierDescriptif(); ?>">

                            <td id="<?php echo $d->getDossierId(); ?>" style="text-align: center; font-size:13px; color:grey;">
                                
                                <i class="glyphicon glyphicon-pencil btnModDossier" style="padding-right: 2px; cursor: pointer"></i>                                  
                                <?php
                                if( $d->getDossierClos() == 1 ): ?>
                                    <br><i class="glyphicon glyphicon-unchecked btnOpenDossier" style="padding-right: 10px; cursor: pointer"></i>
                                <?php
                                else: ?>
                                    <br><i class="glyphicon glyphicon-check btnCloseDossier" style="padding-right: 10px; cursor: pointer"></i>
                                <?php
                                endif; ?>
                                
                                <?php
                                if( $d->getDossierClos() == 1):
                                    echo '<i class="glyphicon glyphicon-ok" style="color:green"></i>';
                                endif; ?>
                            </td>
                            <td><strong><?php echo $d->getDossierClient(); ?></strong></td>
                            <td><?php echo $d->getDossierDescriptif(); ?></td>
                            <td <?php if( $d->getDossierPao() == 0) echo 'class="hachures"'; ?> >
                                <?php
                                $nbPao = 0;
                                if( !empty($d->getDossierAffectations()) ):
                                    foreach ( $d->getDossierAffectations() as $a):
                                        if( $a->getAffectationType() == 3 ) : ?>
                                            <div class="progHebdo" data-affectid="<?php echo $a->getAffectationId(); ?>" style="background-color: <?php echo $a->getAffectationCouleur(); ?>; position:relative;">
                                                <div class="btnModAffect" style="width:100%; padding: 7px;">
                                                    <?php
                                                    echo date('d/m/y',$a->getAffectationDate()) . ' - <strong>' . $a->getAffectationEquipe() .'</strong>';
                                                    ?>                                                    
                                                </div>   
                                                <div class="intervenant">
                                                    <?php echo $a->getAffectationIntervenant(); ?>
                                                </div>                                    
                                            </div>
                                            <?php
                                            $nbPao++;
                                        endif;
                                    endforeach;
                                endif;
                                if( $d->getDossierPao() == 1 && $nbPao == 0 ):
                                    echo '<center><i class="glyphicon glyphicon-alert" style="color:orangered; font-size:20px;"></i></center>';                                    
                                endif;

                                if( $d->getDossierClos() == 0): ?>
                                    <button class="btn btn-xs btn-link btnAddAffectation" data-type="3" style="font-size:10px; color:#9999ff; padding:2px; text-align: center; width:100%;"><i class="glyphicon glyphicon-plus"></i></button>
                                <?php
                                endif; ?>
                            </td>
                            <td <?php if( $d->getDossierFab() == 0) echo 'class="hachures"'; ?> >
                                <?php
                                $nbFab = 0;
                                if( !empty($d->getDossierAffectations()) ):
                                    foreach ( $d->getDossierAffectations() as $a):
                                        if( $a->getAffectationType() == 1 ) : ?>
                                            <div class="progHebdo" data-affectid="<?php echo $a->getAffectationId(); ?>" style="background-color: <?php echo $a->getAffectationCouleur(); ?>; position:relative;">
                                                <div class="btnModAffect" style="width:100%; padding: 7px;">
                                                    <?php
                                                    echo date('d/m/y',$a->getAffectationDate()) . ' - <strong>' . $a->getAffectationEquipe() .'</strong>';
                                                    ?>                                                    
                                                </div>   
                                                <div class="intervenant">
                                                    <?php echo $a->getAffectationIntervenant(); ?>
                                                </div>                                    
                                            </div>
                                            <?php
                                            $nbFab++;
                                        endif;
                                    endforeach;
                                endif;
                                if( $d->getDossierFab() == 1 && $nbFab == 0 ):
                                    echo '<center><i class="glyphicon glyphicon-alert" style="color:orangered; font-size:20px;"></i></center>';
                                endif;
                                

                                if( $d->getDossierClos() == 0): ?>
                                    <button class="btn btn-xs btn-link btnAddAffectation" data-type="1" style="font-size:10px; color:#9999ff; padding:2px; text-align: center; width:100%;"><i class="glyphicon glyphicon-plus"></i></button>
                                <?php
                                endif; ?>
                            </td>
                            <td <?php if( $d->getDossierPose() == 0) echo 'class="hachures"'; ?> >
                                <?php
                                $nbPose = 0;
                                if( !empty($d->getDossierAffectations()) ):
                                    foreach ( $d->getDossierAffectations() as $a):
                                        if( $a->getAffectationType() == 2 ) : ?>
                                            <div class="progHebdo" data-affectid="<?php echo $a->getAffectationId(); ?>" style="background-color: <?php echo $a->getAffectationCouleur(); ?>">
                                                <div class="btnModAffect" style="width:100%; padding: 7px;">
                                                    <?php
                                                    echo date('d/m/y',$a->getAffectationDate()) . ' - <strong>' . $a->getAffectationEquipe() .'</strong>';
                                                    ?>                                                    
                                                </div>   
                                                <div class="intervenant">
                                                    <?php echo $a->getAffectationIntervenant(); ?>
                                                </div>
                                            </div>
                                            <?php
                                            $nbPose++;
                                        endif;
                                    endforeach;
                                endif;
                                if( $d->getDossierPose() == 1 && $nbPose == 0 ):
                                    echo '<center><i class="glyphicon glyphicon-alert" style="color:orangered; font-size:20px;"></i></center>';
                                endif;
                                
                                if( $d->getDossierClos() == 0): ?>
                                    <button class="btn btn-xs btn-link btnAddAffectation" data-type="2" style="font-size:10px; color:#9999ff; padding:2px; text-align: center; width:100%;"><i class="glyphicon glyphicon-plus"></i></button>
                                <?php
                                endif; ?>
                            </td>

                        </tr>
                    <?php
                    endforeach;
                    endif; ?>

                </tbody>
            </table>



        </div>
    </div>

</div>
<?php include('forms.php'); ?>