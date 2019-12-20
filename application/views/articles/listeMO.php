<div class="container fond">
    <div class="row">
        <?= form_open('articles/criteresListeMO', array('id' => 'formRechMO', 'class' => 'form-inline')); ?>
        <div class="col-xs-12" style="text-align: center; background-color: #e1e6ec; padding: 5px; position: relative; top: -5px; border: 1px solid lightsteelblue; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
            <div class="input-group">
                <span class="input-group-addon">
                    Date comprise entre le
                </span>
                <input type="date" id="rechDateDebutMO" value="<?= $this->session->userdata('rechMOStart') ? date('Y-m-d', $this->session->userdata('rechMOStart')) : date('Y-m-01'); ?>" class="form-control">
                <span class="input-group-addon">
                    et le
                </span>
                <input type="date" id="rechDateFinMO" value="<?= $this->session->userdata('rechMOEnd') ? date('Y-m-d', $this->session->userdata('rechMOEnd')) : date('Y-m-t'); ?>" class="form-control">
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
            <h2>Liste de la Main d'oeuvre commandées</h2>
            <table id="tableMO" style="font-size:12px;"
                   data-toggle="table"
                   data-search="true"
                   data-show-export="true"
                   data-export-datatype="all"
                   data-export-types ="['excel']"
                   >
                <thead>
                    <tr>
                        <th data-sortable="true">Affaire</th>
                        <th data-sortable="true">Clients</th>
                        <th data-sortable="false">Objet</th>
                        <th data-sortable="true">PAO</th>
                        <th data-sortable="true">ATELIER</th>
                        <th data-sortable="true">POSE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalHeuresPAO = $totalHeuresAtelier = $totalHeuresPose = 0;
                    if ($affaires):
                        foreach ($affaires as $affaire):
                            $heuresPAO = $heuresAtelier = $heuresPose = 0;
                            if (!empty($affaire->getAffaireMOs())):
                                foreach ($affaire->getAffaireMOs() as $mo):
                                    switch ($mo->getAffaireOptionOptionId()):
                                        case 158:
                                        case 342:
                                        case 343:
                                        case 344:
                                        case 345:
                                        case 346:
                                        case 350:
                                        case 351:
                                        case 352:
                                            $heuresAtelier += $mo->getAffaireOptionQte();
                                            break;
                                        case 159:
                                        case 419:
                                            $heuresPose += $mo->getAffaireOptionQte();
                                            break;
                                        case 160:
                                            $heuresPAO += $mo->getAffaireOptionQte();
                                            break;
                                    endswitch;
                                endforeach;
                            endif;
                            $totalHeuresAtelier += $heuresAtelier;
                            $totalHeuresPAO += $heuresPAO;
                            $totalHeuresPose += $heuresPose;
                            ?>
                            <tr>
                                <td>
                                    <a target="_blank" href="<?= site_url('ventes/reloadAffaire/' . $affaire->getAffaireId()); ?>"><?= $affaire->getAffaireId(); ?></a>
                                </td>
                                <td>
                                    <?php
                                    foreach ($affaire->getAffaireClients() as $client): echo '<i class="fa fa-arrow-right"></i> ' . $client->getClientRaisonSociale() . '<br>';
                                    endforeach;
                                    ?>
                                </td>
                                <td>
                                    <?= $affaire->getAffaireObjet(); ?>
                                </td>
                                <td class="text-center">
                                    <?= $heuresPAO; ?>
                                </td>
                                <td class="text-center">
                                    <?= $heuresAtelier; ?>
                                </td>
                                <td class="text-center">
                                    <?= $heuresPose; ?>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                    <tr>
                        <td colspan="3" class="text-right"><b>Totaux</b></td>
                        <td class="text-center"><b><?= $totalHeuresPAO; ?></b></td>
                        <td class="text-center"><b><?= $totalHeuresAtelier; ?></b></td>
                        <td class="text-center"><b><?= $totalHeuresPose; ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
