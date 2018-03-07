<div class="container fond">
    <div class="row hidden-xs">

        <div class="col-xs-12">
            <div class="row">
                <div class="col-sm-9">
                    <h2 style="margin: 15px 0px 0px 0px;">
                        <a href="<?= site_url('documents/editionFacture/' . $facture->getFactureId()); ?>" target="_blank">
                            <i class="fas fa-file-pdf" style="color:<?= $facture->getFactureSolde() > 0 ? 'orangered' : "green"; ?>;"></i>
                        </a> Détails de la facture <?= $facture->getFactureId(); ?></i>
                    </h2>
                </div>
                <div class="col-sm-3" style="text-align: right; font-size: 15px;">
                    <strong>Date : <?= date('d/m/Y', $facture->getFactureDate()); ?></strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <a href="<?= site_url('ventes/reloadAffaire/' . $facture->getFactureAffaireId()); ?>">Liée à l'affaire N°<?= $facture->getFactureAffaireId(); ?></a>
                    <br>
                    <?php $client = $facture->getFactureClient(); ?>
                    <span style="font-weight: bold; font-size:16px;">
                        <?= $client->getClientRaisonSociale() ?: ''; ?>
                    </span>
                    <?php
                    echo '<br>' . $client->getClientAdresse1();
                    echo $client->getClientAdresse2() ? '<br>' . $client->getClientAdresse2() : '';
                    echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille();
                    echo '<br>' . $client->getClientPays();
                    ?>

                    <button class="btn btn-link" id="sendFactureByEmail" data-factureid="<?= $facture->getFactureId(); ?>" style="display: none;">
                        <i class="fas fa-envelope"></i> Renvoyer par mail
                    </button>
                    <br>
                    <br>
                    <table class="table table-condensed table-bordered" style="width:200px;">
                        <tr>
                            <td style="background-color: lightgray;">Total HT</td>
                            <td style="text-align: right;"><?= number_format($facture->getFactureTotalHT(), 2, ',', ' ') . '€'; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color: lightgray;">Total TVA</td>
                            <td style="text-align: right;"><?= number_format($facture->getFactureTotalTVA(), 2, ',', ' ') . '€'; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color: lightgray;">Total TTC</td>
                            <td style="text-align: right;"><?= number_format($facture->getFactureTotalTTC(), 2, ',', ' ') . '€'; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color: lightgray;">Envoyée</td>
                            <td style="text-align: center;">
                                <input type="checkbox" value="1" id="factureEnvoyee" <?= $facture->getFactureEnvoyee() ? 'checked' : ''; ?>>
                            </td>
                        </tr>
                    </table>
                    Echéance de Paiement accordée :<br>
                    <select id="modFactureEcheancePaiement" class="form-control" data-factureid="<?= $facture->getFactureId(); ?>">
                        <option value="1" <?= $facture->getFactureEcheanceId() == 1 ? 'selected' : ''; ?> >A récéption de facture</option>
                        <option value="4" <?= $facture->getFactureEcheanceId() == 4 ? 'selected' : ''; ?>>A récéption de facture - Escompte 3%</option>
                        <option value="2" <?= $facture->getFactureEcheanceId() == 2 ? 'selected' : ''; ?>>A 30 jours</option>
                        <option value="3" <?= $facture->getFactureEcheanceId() == 3 ? 'selected' : ''; ?>>A 45 jours</option>
                    </select>
                    <br>Date de paiement : <?= date('d/m/y', $facture->getFactureEcheanceDate()); ?>
                </div>
                <div class = "col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-xs-7">
                            <h3 style="margin-top: 0px;">Réglements</h3>
                            <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF;  width: 400px;">
                                <tr style="background-color: #04335a; color: #FFF;">
                                    <td style=" width: 45px;"></td>
                                    <td style=" width: 60px;">Date</td>
                                    <td style=" width: 90px;">Type</td>
                                    <td style="text-align: right; width: 90px;">Montant</td>
                                    <td style="width: 95px;">Mode</td>
                                    <td style="width: 20px;"></td>
                                </tr>
                                <?php
                                if (!empty($facture->getFactureReglements())):
                                    foreach ($facture->getFactureReglements() as $r):
                                        ?>
                                        <tr data-reglementid="<?= $r->getReglementId(); ?>"
                                            data-reglementMontant="<?= $r->getReglementMontant(); ?>"
                                            data-reglementmode="<?= $r->getReglementMode(); ?>"
                                            data-reglementdate="<?= date('Y-m-d', $r->getReglementDate()); ?>"
                                            data-reglementtype="<?= $r->getReglementType(); ?>"
                                            class="ligneReglement">
                                            <td style="text-align: center;">
                                                <?php
                                                if ($r->getReglementHistorique()):
                                                    echo '<i class="fas fa-history" style="color: purple;"></i> ';
                                                endif;
                                                if ($r->getReglementSecure()):
                                                    echo '<i class="fas fa-certificate" style="color: green;"></i>';
                                                else:
                                                    echo '<i class="fas fa-exclamation-triangle" style="color: red;"></i>';
                                                endif;
                                                ?>
                                            </td>
                                            <td>
                                                <?= date('d/m/y', $r->getReglementDate()); ?>
                                            </td>
                                            <td>
                                                <?= $r->getReglementType() == 1 ? 'Acompte' : 'Solde'; ?>
                                            </td>
                                            <td style="text-align: right;"><?= number_format($r->getReglementMontant(), 2, ',', ' ') . '€'; ?></td>
                                            <td><?= $r->getReglementModeText(); ?></td>
                                            <td style="text-align: center;">
                                                <button class="btn btn-xs btn-link btnModReglement" style="margin:0px; padding:0px;"><i class="fas fa-pencil-alt" style="cursor: pointer;"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </table>
                            <strong>Solde : <span style="color:<?= $facture->getFactureSolde() > 0 ? 'orangered' : "green"; ?>;"><?= number_format($facture->getFactureSolde(), 2, ',', ' ') . '€'; ?></span></strong>
                            <?php
                            if ($facture->getFactureSolde() != 0):
                                echo '<a class="btn btn-link" href="' . site_url('facturation/forceSolde/' . $facture->getFactureId() . '/0') . '"><i class="fas fa-arrow-circle-down"></i> Forcer le solde à 0</a>';
                            else:
                                echo '<a class="btn btn-link" href="' . site_url('facturation/recalculeSolde/' . $facture->getFactureId()) . '"><i class="fas fa-question-circle"></i> Recalcul du solde</a>';
                            endif;
                            ?>
                        </div>
                        <div class="col-xs-5">
                            <div class="panel panel-default" style="border-width: 3px;">
                                <div class="panel-heading" style="text-align: center; font-weight: bold;">
                                    <i class="far fa-credit-card"></i> Ajouter ou modifier un réglement
                                </div>
                                <div class="panel-body">
                                    <?= form_open('facturation/addReglement', array('class' => 'form-horizontal', 'id' => 'formAddReglement'));
                                    ?>
                                    <input type="hidden" name="addReglementId" id="addReglementId" value="">
                                    <input type="hidden" name="addReglementAffaireId" value="<?= $facture->getFactureAffaireId(); ?>">
                                    <input type="hidden" name="addReglementClientId" value="<?= $facture->getFactureClientId(); ?>">
                                    <input type="hidden" name="addReglementFactureId" value="<?= $facture->getFactureId(); ?>">
                                    <div class="form-group" style="padding: 0px; margin: 2px;">
                                        <label class="col-sm-3">Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" style="text-align: right;" class="form-control" name="addReglementDate" id="addReglementDate" value="<?= date('Y-m-d'); ?>" required >
                                        </div>
                                    </div>
                                    <div class="form-group" style="padding: 0px; margin: 2px;">
                                        <label class="col-sm-3">Montant</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" style="text-align: right;" class="form-control" name="addReglementMontant" id="addReglementMontant" value="<?= $facture->getFactureSolde(); ?>" required >
                                                <span class="input-group-addon">€</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="padding: 0px; margin: 2px;">
                                        <label class="col-sm-3">Mode</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="addReglementMode" id="addReglementMode" >
                                                <option value="1" selected>Chèque</option>
                                                <option value="2">Virement</option>
                                                <option value="3">Espèces</option>
                                                <option value="4">Carte bancaire</option>
                                                <option value="5">Traite</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="padding: 0px; margin: 2px;">
                                        <label class="col-sm-3">Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="addReglementType" id="addReglementType" >
                                                <option value="2">Réglement</option>
                                                <option value="1">Acompte</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="padding: 0px; margin: 2px; display: none;">
                                        <label class="col-sm-3">Motif</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="" placeholder="Motif" name="addReglementMotif" id="addReglementMotif" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer" style="text-align: center;">
                                    <div class="btn-group">
                                        <button class="btn btn-primary" type="submit" id="btnAddReglementSubmit"><i class="fas fa-plus"></i> Ajouter un réglement</button>
                                        <button class="btn btn-default" type="button" id="btnAddReglementCancel"><i class="fas fa-times"></i></button>
                                    </div>
                                    <div id="loaderReglement" class="la-ball-scale-pulse form-control" style="color:orangered; border: none; display:none;">
                                        <div></div>
                                        <div></div>
                                    </div>
                                    <?= form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3>Avoirs</h3>
                    <a href="<?= site_url('avoirs/genererAvoir/' . $facture->getFactureId()); ?>" class="btn btn-link" style="position : absolute; right: 30px; top: 320px;">
                        <i class="fas fa-file-excel"></i> Générer un avoir
                    </a>
                    <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF; width: 500px;">
                        <tr style="background-color: #04335a; color: #FFF;">
                            <td style="color: lightgray; width: 20px;"></td>
                            <td>N°</td>
                            <td>Date</td>
                            <td style="text-align: right;">Montant TTC</td>
                            <td style="width: 20px;"></td>
                        <tr>

                            <?php
                            if (!empty($facture->getFactureAvoirs())):
                                foreach ($facture->getFactureAvoirs() as $a):
                                    ?>
                                <tr data-avoirid="<?= $a->getAvoirId(); ?>">
                                    <td>
                                        <?php if (file_exists('assets/Avoir ' . $a->getAvoirId() . '.pdf')): ?>
                                            <button class="btn btn-link btn-xs btnSendAvoirEmail" style="padding: 0px;">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        <?php endif;
                                        ?>
                                    </td>
                                    <td>
                                        <?= $a->getAvoirId(); ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', $a->getAvoirDate()); ?>
                                    </td>
                                    <td style="text-align: right;"><?= number_format($a->getAvoirTotalTTC(), 2, ',', ' ') . '€'; ?></td>
                                    <td>
                                        <a href="<?= site_url('documents/editionAvoir/' . $a->getAvoirId()); ?>" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>

                    </table>

                </div>
            </div>

        </div>

    </div>
</div>