<div class="container fond">
    <div class="row">
        <?= form_open('facturation/criteresListeReglements', array('id' => 'formRechReglements', 'class' => 'form-inline')); ?>
        <div class="col-xs-12" style="text-align: center; background-color: #e1e6ec; padding: 5px; position: relative; top: -5px; border: 1px solid lightsteelblue; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
            <div class="input-group">
                <span class="input-group-addon">
                    Date comprise entre le
                </span>
                <input type="date" id="rechDateDebutReglements" value="<?= $this->session->userdata('rechReglementStart') ? date('Y-m-d', $this->session->userdata('rechReglementStart')) : date('Y-m-01'); ?>" class="form-control">
                <span class="input-group-addon">
                    et le
                </span>
                <input type="date" id="rechDateFinReglements" value="<?= $this->session->userdata('rechReglementEnd') ? date('Y-m-d', $this->session->userdata('rechReglementEnd')) : date('Y-m-t'); ?>" class="form-control">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> Rechercher
                </button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
    <div class="row" style="background-color: #FFF;">
        <div class="col-xs-12">
            <h2>Liste des réglements</h2>
            <table id="tableReglements" style="font-size:12px;"
                   data-toggle="table"
                   data-search="true"
                   >
                <thead>
                    <tr>
                        <th data-sortable="true">N°</th>
                        <th data-sortable="true">Date</th>
                        <th data-sortable="true">Client</th>
                        <th data-sortable="true">Type</th>
                        <th data-sortable="true">Mode</th>
                        <th data-align="right" data-sortable="true">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalReglements = 0;
                    if ($reglements):
                        foreach ($reglements as $r):
                            $totalReglements += $r->getReglementMontant();
                            ?>
                            <tr>
                                <td>
                                    <?= $r->getReglementId(); ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', $r->getReglementDate()); ?>
                                </td>
                                <td>
                                    <?= $r->getReglementClient()->getClientRaisonSociale(); ?>
                                </td>
                                <td>
                                    <?= $r->getReglementType() == 1 ? 'Acompte' : 'Solde'; ?>
                                </td>
                                <td>
                                    <?= $r->getReglementModeText(); ?>
                                </td>
                                <td>
                                    <?= number_format($r->getReglementMontant(), 2, ',', ' '); ?>
                                </td>
                            </tr>

                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
            <h4>Total période : <?= number_format($totalReglements, 2, ',', ' ') . '€'; ?></h4>
        </div>
    </div>
</div>
