<div class="container fond">
    <div class="row base" style="margin-top:10px;">
        <div class="col-sm-12">
            <h3>
                <a href="<?= site_url('clients/liste'); ?>">
                    <i class="fa fa-backward"></i>
                </a>
                <?php
                echo $client->getClientRaisonSociale();
                ?>
            </h3>
            <div class="row" style="margin-top:5px;">
                <div class="col-sm-4">
                    <address>
                        <?php
                        echo $client->getClientAdresse1();
                        if ($client->getClientAdresse2()):
                            echo '<br>' . $client->getClientAdresse2();
                        endif;
                        echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille() . '<br>'
                        . '<i class="glyphicon glyphicon-phone-alt"></i> ' . $client->getClientTelephone();
                        ?>
                    </address>

                    <br>Délai de paiement<br><strong><?= $client->getClientEcheancePaiementTexte(); ?></strong>

                    <br><br>Exonération TVA : <strong><?= $client->getClientExoneration() ? 'Oui' : 'Non'; ?></strong>
                    <br>Num TVA : <strong><?= $client->getClientNumTVA(); ?></strong>


                </div>
                <div class="col-sm-2" style="text-align: left;">
                    <button class="btn btn-default tooltipOk" data-placement="left" title="Modifier le client" id="btnModClient" cible="<?php echo $client->getClientId(); ?>">
                        <i class="glyphicon glyphicon-pencil"></i> Modifier
                    </button>
                    <br>
                    <button <?php if (!empty($bdcs) && count($bdcs) > 0) echo 'disabled'; ?>  class="btn btn-link btn-xs tooltipOk" data-placement="bottom" title="Double-click" id="btnDelClient" data-clientid="<?php echo $client->getClientId(); ?>">
                        <i class="glyphicon glyphicon-erase"></i> Supprimer
                    </button>
                </div>
                <div class="col-sm-6" style="border-left: 4px solid grey;">
                    <h4>Contacts <button type="button" class="btn btn-sm btn-link" id="btnAddContact" ><i class="fa fa-plus-circle"></i> Ajouter</button></h4>
                    <table class="table table-bordered table-condensed" style="font-size:11px;">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($client->getClientContacts()):
                                foreach ($client->getClientContacts() as $c):
                                    ?>
                                    <tr data-contactid="<?= $c->getContactId(); ?>" >
                                        <td>
                                            <?= $c->getContactNom() . ' ' . $c->getContactPrenom() . '<br><em>' . $c->getContactFonction() . '</em>'; ?>
                                        </td>
                                        <td>
                                            <?= $c->getContactTelephone() . '<br>' . $c->getContactPortable(); ?>
                                        </td>
                                        <td>
                                            <?= '<a href="mailto:' . $c->getContactEmail() . '">' . $c->getContactEmail() . '</a>'; ?>
                                        </td>
                                        <td>
                                            <i class="fa fa-pencil btnModContact" style="cursor: pointer; color: grey;"></i>
                                            <br><i class="fa fa-trash btnDelContact" style="cursor: pointer; color: lightgrey;"></i>
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
            <hr>
            <div class="row" style="margin-top:10px;">
                <div class="col-xs-12 col-sm-7">
                    <h3>Historique des affaires</h3>
                    <table class="table table-condensed table-bordered"style="font-size: 13px; background-color: #FFF;">
                        <thead>
                            <tr style="background-color: #04335a; color: #FFF;">
                                <th>ID</th>
                                <th>Date</th>
                                <th>Affaire</th>
                                <th style="text-align: right;">Total HT</th>
                                <th>Avancement</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($client->getClientAffaires()):
                                foreach ($client->getClientAffaires() as $affaire):
                                    ?>
                                    <tr>
                                        <td><?= $affaire->getAffaireId(); ?></td>
                                        <td><?= date('d/m/Y', $affaire->getAffaireDate()); ?></td>
                                        <td><?= $affaire->getAffaireObjet(); ?></td>
                                        <td style="text-align: right;"><?= number_format($affaire->getAffaireTotalHT(), 2, ',', ' ') . '€'; ?></td>
                                        <td>
                                            <?php
                                            if ($affaire->getAffaireCloture()):
                                                echo 'Clôturée';
                                            elseif ($affaire->getAffaireCommandeId()):
                                                echo '<span style="color: green;">En cours</span>';
                                            elseif ($affaire->getAffaireDevisId()):
                                                echo '<span style="color: orange;">Devis envoyé le ' . date('d/m/y', $affaire->getAffaireDevisDate()) . '</span>';
                                            else:
                                                echo '<span style="color: steelblue;">Conception</span>';
                                            endif;
                                            ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="<?= site_url('ventes/reloadAffaire/' . $affaire->getAffaireId()); ?>">
                                                <i class="fas fa-link"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-12 col-sm-5">
                    <h3>Liste des factures et avoirs</h3>
                    <table class="table table-condensed table-bordered"style="font-size: 13px; background-color: #FFF;">
                        <thead>
                            <tr style="background-color: #04335a; color: #FFF;">
                                <th>ID</th>
                                <th>Date</th>
                                <th style="text-align: right;">Total HT</th>
                                <th style="text-align: right;">Total TTC</th>
                                <th style="text-align: right;">Solde</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($client->getClientFactures()):
                                foreach ($client->getClientFactures() as $facture):
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?= site_url('facturation/ficheFacture/' . $facture->getFactureId()); ?>"><?= $facture->getFactureId(); ?></a>
                                        </td>
                                        <td><?= date('d/m/y', $facture->getFactureDate()); ?></td>
                                        <td style="text-align: right;"><?= number_format($facture->getFactureTotalHT(), 2, ',', ' '); ?></td>
                                        <td style="text-align: right;"><?= number_format($facture->getFactureTotalTTC(), 2, ',', ' '); ?></td>
                                        <td style="text-align: right;"><?= number_format($facture->getFactureSolde(), 2, ',', ' '); ?></td>
                                        <td style="text-align: center;">
                                            <a href="<?= site_url('documents/editionFacture/' . $facture->getFactureId()); ?>" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            if ($client->getClientAvoirs()):
                                foreach ($client->getClientAvoirs() as $avoir):
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?= site_url('facturation/ficheFacture/' . $avoir->getAvoirFactureId()); ?>"><?= 'AV ' . $avoir->getAvoirId(); ?></a>
                                        </td>
                                        <td><?= date('d/m/y', $avoir->getAvoirDate()); ?></td>
                                        <td style="text-align: right;"><?= number_format($avoir->getAvoirTotalHT(), 2, ',', ' '); ?></td>
                                        <td style="text-align: right;"><?= number_format($avoir->getAvoirTotalTTC(), 2, ',', ' '); ?></td>
                                        <td style="text-align: right;">sur la facture N°<?= $avoir->getAvoirFactureId(); ?></td>
                                        <td style="text-align: center;">
                                            <a href="<?= site_url('documents/editionAvoir/' . $avoir->getAvoirId()); ?>" target="_blank"><i class="fas fa-file-pdf"></i></a>
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
    </div>
    <?php include('formClient.php');
    ?>
    <?php include('formContact.php'); ?>
</div>