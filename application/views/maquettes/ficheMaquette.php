<div class="container fond">
    <div class="row base" style="margin-top:10px;">
        <div class="col-sm-12">
            <h3>
                <a href="<?= site_url('maquettes/listeMaquettes'); ?>">
                    <i class="fa fa-backward"></i>
                </a>
                <span style="font-size:14px; font-style: italic;">Maquette pour le client</span>
                <?php
                if ($maquette->getMaquetteClientId()):
                    echo $maquette->getMaquetteClient()->getClientRaisonSociale();
                else:
                    echo $maquette->getMaquetteClientText();
                endif;
                ?>
            </h3>
            <div class="row" style="margin-top:5px;">
                <div class="col-sm-6">
                    <buttton class="btn btn-default pull-right" data-toggle="modal" data-target="#modalAddMaquette">
                        <i class="fas fa-pencil-alt"></i>
                    </buttton>
                    Date du RDV : <?= date('d/m/Y', $maquette->getMaquetteDateCreation()); ?>
                    <br>Date maximum pour la création de la maquette : <strong><?= date('d/m/Y', $maquette->getMaquetteDateLimite()); ?></strong>
                    <br><button type="button" class="btn btn-default" id="btnMaquetteAvancement"><?= $maquette->getMaquetteAvancementText(); ?></button>
                    <br>
                    <br>
                    <?php
                    for ($i = 1; $i <= $maquette->getMaquetteDifficulte(); $i++):
                        echo '<i class="fas fa-pencil-ruler" style="color: purple; font-size:20px; margin:0px 8px 5px 0px;"></i>';
                    endfor;
                    ?>
                    <br><a href="<?= $maquette->getMaquettePathFiles(); ?>" target="_blank"><?= $maquette->getMaquettePathFiles(); ?></a>
                    <hr>
                    <h4>Description</h4>
                    <?= nl2br($maquette->getMaquetteDescription()); ?>
                    <br><button class="btn btn-danger btn-xs" type="button" id="btnDelMaquette" style="margin-top:80px;">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddMaquette" data-show="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Modifier la Maquette
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include('formMaquette.php'); ?>
                </div>
            </div>
        </div>
    </div>