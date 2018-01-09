<div class="container">
    <div class="row" style="background-color: #FFF;">
        <div class="col-xs-12">

            <table id="tableFactures" style="font-size:12px;"
                   data-toggle="table"
                   data-search="true"
                   >
                <thead>
                    <tr>
                        <th data-sortable="true" data-filed="factureId">N°</th>
                        <th data-sortable="true">Date</th>
                        <th data-sortable="true">Client</th>
                        <th data-align="right">Montant</th>
                        <th data-align="right"  data-sortable="true">Solde</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($factures)):
                        foreach ($factures as $f):
                            ?>

                            <tr data-factureid="<?= $f->factureId; ?>">
                                <td>
                                    <?= $f->factureNum; ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', $f->factureDate); ?>
                                </td>
                                <td>
                                    <?= $f->factureClient; ?>
                                </td>
                                <td>
                                    <?= number_format($f->factureTotalTTC, 2,',',' '); ?>
                                </td>
                                <td>
                                    <?= number_format( $f->factureSolde,2,',',' '); ?>
                                </td>
                            </tr>

                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>

            </table>

        </div>
    </div>
</div>
