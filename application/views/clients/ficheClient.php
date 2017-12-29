<div class="container">
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

                    <strong>Gestion de la TVA</strong>
                    <br>Exonération : <?= $client->getClientExoneration() ? 'Oui' : 'Non'; ?>
                    <br>Num TVA : <?= $client->getClientNumTVA(); ?>
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
                            <?php foreach( (array) $client->getClientContacts() as $c ): ?>
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
                            endforeach; ?>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="row" style="margin-top:10px;">
                <div class="col-xs-12">
                    <h2>Historique des affaires</h2>
                    <table class="table table-condensed table-bordered table-striped" style="font-size:11px;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Total HT</th>
                                <th>Avancement</th>     
                                <th>Facture</th>
                                <th>Visualisation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($client->getClientAffaires())):
                                foreach ($client->getClientAffaires() as $c):
                                    ?>
                                    <tr>
                                        <td><?= $c['affaireId']; ?></td>
                                        <td><?= date('d/m/Y', $c['affaireCreation']); ?></td>
                                        <td><?= number_format($c['affaireTotalHT'], 2, ',', ' ') . '€'; ?></td>
                                        <td>
                                            <?php
                                            switch ($c['affaireAvancement']):
                                                case 0:
                                                    echo '<span style="color: orangered;">Non payée</span>';
                                                    break;
                                                case 1:
                                                    echo '<span style="color: #000;">En cours de préparation</span>';
                                                    break;
                                                case 2:
                                                    if ($c['affaireLivraisonType'] == 3):
                                                        echo '<span style="color: blue;">Attente de retrait client</span>';
                                                    else:
                                                        echo '<span style="color: blue;">Prête à expédier</span>';
                                                    endif;
                                                    break;
                                                case 3:
                                                    if ($c['affaireLivraisonType'] == 3):
                                                        echo '<span style="color: grey;">Retirée';
                                                    else:
                                                        echo '<span style="color: grey;">Expédiée';
                                                    endif;
                                                    echo ' le ' . date('d/m/Y', $c['affaireExpeditionDate']) . '</span>';
                                                    break;
                                            endswitch;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($c['affaireFacture'] > 0):
                                                echo '<a href="' . site_url('affaires/facture/' . $c['affaireId']) . '" target="_blank"><i class="fa fa-file-pdf-o" style="color: red;"></i></a>';
                                            endif;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo '<a href="' . site_url('affaires/visualisation/' . $c['affaireId']) . '"><i class="fa fa-bullseye"></i></a>';
                                            ?>
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
    <?php include('formClient.php'); ?>
    <?php include('formContact.php'); ?>
</div>