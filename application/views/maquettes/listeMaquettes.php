<div class="container fond">
    <div class="row" style="background-color: #FFF;">
        <div class="col-xs-12">

            <a href="<?= site_url('maquettes/listeMaquettes/ajouter'); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nouvelle maquette
            </a>

            <table id="tableMaquettes" style="font-size:12px;">

            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="modalAddMaquette" data-show="<?= $this->uri->segment(3) == 'ajouter' ? 'true' : 'false'; ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Ajouter une maquette
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

