<input type="hidden" id="avoirFactureId" value="<?= $this->session->userdata('affaireFactureId'); ?>">
<div class="container fond">
    <div class="row hidden-xs">

        <div class="col-xs-12">

            <div class="row" style="margin-top:5px;">
                <div class="col-sm-9">
                    <h2 style="color: #294982;">
                        Générer un avoir pour la facture N°<?= $facture->getFactureId(); ?>
                        <a href="<?= site_url('facturation/ficheFacture/' . $facture->getFactureId()); ?>" class="btn btn-link">
                            Retour à la fiche facture
                        </a>
                    </h2>
                    Cet avoir sera généré à la date du jour et ne sera plus modifiable une fois enregistré.
                </div>

                <div class="col-sm-3" style="text-align: right;">
                    <?php $client = $facture->getFactureClient(); ?>
                    <span style="font-weight: bold; font-size:16px;">
                        <?= $client->getClientRaisonSociale(); ?>
                    </span>
                    <?php
                    echo '<br>' . $client->getClientAdresse1();
                    echo $client->getClientAdresse2() ? '<br>' . $client->getClientAdresse2() : '';
                    echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille();
                    echo '<br>' . $client->getClientPays();
                    ?>
                </div>

            </div>
            <br>
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-12">
                    <table class="table table-condensed" style="font-size: 14px; background-color: #FFF;">
                        <thead>
                            <tr style="background-color: #04335a; color: #FFF;">
                                <th>Désignation</th>
                                <th style="text-align: center; width: 100px;">PU HT</th>
                                <th style="text-align: center;width: 80px;">Vendue</th>
                                <th style="text-align: right; width: 100px;">Quantité</th>
                                <th style="text-align: center; width: 80px;">Remise</th>
                                <th style="text-align: right; width: 120px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($this->cart->contents()):
                                foreach ($this->cart->contents() as $item):
                                    ?>
                                    <tr class="ligneAvoir" data-rowid="<?= $item['rowid']; ?>">
                                        <td style="vertical-align: middle;">
                                            <?php if ($item['id'] == 'Libre'): ?>
                                                <input type="text" class="form-control input-sm modAvoirName" value="<?= $item['name']; ?>"  placeholder="Texte d'avoir libre">
                                                <?php
                                            else :
                                                echo $item['name'];
                                            endif;
                                            ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?php if ($item['id'] == 'Libre'): ?>
                                                <input type="text" class="form-control input-sm modAvoirPrix" value="<?= $item['price']; ?>" style="text-align: right;">
                                                <?php
                                            else :
                                                echo number_format($item['options']['prixUnitaire'], 2, ',', ' ');
                                            endif;
                                            ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?php
                                            if ($item['id'] == 'Libre'):
                                                echo '-';
                                            else:
                                                echo number_format(abs($item['options']['qteVendue']), 2, ',', ' ');
                                            endif;
                                            ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input type="text" class="form-control input-sm modAvoirQte" value="<?= $item['qty']; ?>" data-maxi="<?= $item['options']['qteVendue']; ?>" style="text-align: right;">
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;"><?= number_format($item['options']['remise'], 0, ',', ' '); ?></td>
                                        <td style="text-align: right; vertical-align: middle;"><?= number_format($item['price'] * $item['qty'], 2, ',', ' '); ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>

                            <tr>
                                <td style="border-top: 1px solid grey;">
                                    <textarea name="venteCommentaire" id="venteCommentaire" class="form-control" rows="3" style="background-color:#ededfc;" placeholder="Commentaire"><?= $this->session->userdata('venteCommentaire'); ?></textarea>
                                </td>
                                <td style="border-top: 1px solid grey;"></td>
                                <td colspan="4" style="border-top: 1px solid grey;">
                                    <table class="table table-condensed" border="2">
                                        <tbody>
                                            <tr>
                                                <td>Total HT</td>
                                                <td style="text-align: right;"><?= number_format($this->cart->total() * (-1), 2, ',', ' ') . ' €'; ?></td>
                                            </tr>
                                            <?php
                                            if (!empty($this->session->userdata('venteTVA'))):
                                                foreach ($this->session->userdata('venteTVA') as $taux => $value):
                                                    ?>
                                                    <tr>
                                                        <td><?= 'TVA ' . $taux . '%'; ?></td>
                                                        <td style="text-align: right;">
                                                            <?php
                                                            if ($taux == 0):
                                                                echo $value;
                                                            else:
                                                                echo number_format($value * (-1), 2, ',', ' ') . ' €';
                                                            endif;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                            <tr style="background-color: #294982; color: #FFF;">
                                                <td>Facture HT</td>
                                                <td style="text-align: right; font-weight: bold;"><?= number_format($facture->getFactureTotalHT(), 2, ',', ' ') . ' €'; ?></td>
                                            </tr>
                                            <tr style="background-color: #294982; color: #FFF;">
                                                <td>Avoirs générés</td>
                                                <td style="text-align: right; font-weight: bold;"><?= number_format($facture->getFactureTotalAvoirs(), 2, ',', ' ') . ' €'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary" id="btnAvoirEnregistrer" style="width: 100%;">
                                        <i class="fas fa-save"></i> Générer cet avoir
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
