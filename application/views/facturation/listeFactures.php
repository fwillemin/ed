<div class="container fond">
    <div class="row">
        <?= form_open('facturation/criteresListeFactures', array('id' => 'formRechFactures', 'class' => 'form-inline')); ?>
        <div class="col-xs-12" style="text-align: center; background-color: #e1e6ec; padding: 5px; position: relative; top: -5px; border: 1px solid lightsteelblue; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
            <div class="input-group">
                <span class="input-group-addon">
                    Facture N°
                </span>
                <input type="text" id="rechNumFacture" value="" class="form-control" placeholder="N° facture">
            </div>
            <select id="rechEtatFactures" class="form-control">
                <option value="ALL" <?= $this->uri->segment(3) == 'ALL' || !$this->uri->segment(3) ? 'selected' : ''; ?>>Toutes</option>
                <option value="NS" <?= $this->uri->segment(3) == 'NS' ? 'selected' : ''; ?>>Non soldées</option>
                <option value="P" <?= $this->uri->segment(3) == 'P' ? 'selected' : ''; ?>>Payées</option>
            </select>
            <div class="input-group">
                <span class="input-group-addon">
                    Date comprise entre le
                </span>
                <input type="date" id="rechDateDebutFactures" value="<?= $this->session->userdata('rechFactureStart') ? date('Y-m-d', $this->session->userdata('rechFactureStart')) : date('Y-m-01'); ?>" class="form-control">
                <span class="input-group-addon">
                    et le
                </span>
                <input type="date" id="rechDateFinFactures" value="<?= $this->session->userdata('rechFactureEnd') ? date('Y-m-d', $this->session->userdata('rechFactureEnd')) : date('Y-m-t'); ?>" class="form-control">
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
            <h2>Liste des factures et avoirs</h2>
            <table id="tableFactures" style="font-size:12px;"
                   data-toggle="table"
                   data-search="true"
                   data-show-export="true"
                   >
                <thead>
                    <tr>
                        <th data-sortable="true">N°</th>
                        <th data-sortable="true">Date</th>
                        <th data-sortable="true">Client</th>
                        <th data-align="right" data-sortable="true">Montant HT</th>
                        <th data-align="right" data-sortable="true">Montant TTC</th>
                        <th data-align="right" data-sortable="true">Solde</th>
                        <th data-align="center"><i class="far fa-file-pdf"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalPeriodeHT = 0;
                    if ($factures):
                        foreach ($factures as $f):
                            $totalPeriodeHT += $f->getFactureTotalHT();
                            if ($f->getFactureSolde() == 0):
                                $backColor = 'success';
                            else:
                                $backColor = 'default';
                            endif;
                            ?>
                            <tr data-factureid="<?= $f->getFactureId(); ?>" class="<?= $backColor; ?>">
                                <td>
                                    <?= 'FA ' . $f->getFactureId(); ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', $f->getFactureDate()); ?>
                                </td>
                                <td>
                                    <?= $f->getFactureClient()->getClientRaisonSociale(); ?>
                                </td>
                                <td>
                                    <?= number_format($f->getFactureTotalHT(), 2, ',', ' '); ?>
                                </td>
                                <td>
                                    <?= number_format($f->getFactureTotalTTC(), 2, ',', ' '); ?>
                                </td>
                                <td>
                                    <?= number_format($f->getFactureSolde(), 2, ',', ' '); ?>
                                </td>
                                <td>
                                    <a href="<?= site_url('documents/editionFacture/' . $f->getFactureId()); ?>" target="_blank" class="directFacture">
                                        <i class="far fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>

                            <?php
                        endforeach;
                    endif;

                    if ($avoirs):
                        foreach ($avoirs as $a):
                            $totalPeriodeHT -= $a->getAvoirTotalHT();
                            ?>
                            <tr data-factureid="<?= $a->getAvoirFactureId(); ?>" class="warning">
                                <td>
                                    <?= 'AV ' . $a->getAvoirId(); ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', $a->getAvoirDate()); ?>
                                </td>
                                <td>
                                    <?= $a->getAvoirClient()->getClientRaisonSociale(); ?>
                                </td>
                                <td>
                                    <?= '-' . number_format($a->getAvoirTotalHT(), 2, ',', ' '); ?>
                                </td>
                                <td>
                                    <?= '-' . number_format($a->getAvoirTotalTTC(), 2, ',', ' '); ?>
                                </td>
                                <td>
                                    Sur facture N°<?= $a->getAvoirFactureId(); ?>
                                </td>
                                <td>
                                    <a href="<?= site_url('documents/editionAvoir/' . $a->getAvoirId()); ?>" target="_blank" class="directFacture">
                                        <i class="far fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>

                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
            <h4>Total période : <?= number_format($totalPeriodeHT, 2, ',', ' ') . '€ HT'; ?></h4>
        </div>
    </div>
</div>
