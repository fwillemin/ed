<div class="container fond" style="">

    <div class="row">
        <div style="position: absolute; top:5px; right:5px; color: steelblue; font-weight: bold;" id="margeAffaire">
            <i class="fas fa-trophy"></i> <?= $this->session->userdata('margeAffaire'); ?>
        </div>
        <div class = "col-sm-4">
            <?php
            if ($this->session->userdata('pleaseSave') == '1'):
                $classSave = 'btn btn-danger blink';
            else:
                $classSave = 'btn btn-default';
            endif;
            ?>
            <h2>
                <button class="<?= $classSave; ?>" id="btnSaveAffaire" data-affaireid="<?= $this->session->userdata('affaireId'); ?>" style="width: 10%;">
                    <i class="fas fa-save" style="font-size:17px;"></i>
                </button>

                <?php
                if ($affaire):
                    echo '<span style="color: green; font-weight: bold;">Affaire N°' . $affaire->getAffaireId() . '</span>';
                else:
                    echo 'Affaire non enregistré';
                endif;
                ?>
            </h2>

        </div>
        <div class="col-sm-8">

            <?php
            $devisEtat = 'avancementDisabled';
            $commandeEtat = 'avancementDisabled';
            $factureEtat = 'avancementDisabled';

            if ($affaire):
                $devisEtat = 'avancementProchaineEtape';
                if ($affaire->getAffaireDevisId()):
                    $devisEtat = 'avancementValide';
                    $commandeEtat = 'avancementProchaineEtape';
                    if ($affaire->getAffaireCommandeId()):
                        $commandeEtat = 'avancementValide';
                        $factureEtat = 'avancementValide';
                    endif;
                endif;

            endif;
            ?>

            <div class="row" style="position:relative; background-color: #2a293f; padding: 8px 8px 6px 8px; position: relative; top: 30px; border-top-left-radius: 10px;">

                <div class="col-sm-2">
                    <label class="label" style="font-size: 15px;">PAO</label>
                    <input class="modOptionPlanif" data-option="affairePAO" type="checkbox" <?= $this->session->userdata('affairePAO') ? "checked" : ''; ?> data-toggle="toggle" data-size="mini" data-onstyle="success" data-on="<i class='fas fa-thumbs-up'></i>" data-off="-" value="1">
                </div>
                <div class="col-sm-2">
                    <label class="label" style="font-size: 15px;">Fab</label>
                    <input class="modOptionPlanif" data-option="affaireFabrication" type="checkbox" <?= $this->session->userdata('affaireFabrication') ? "checked" : ''; ?> data-toggle="toggle" data-size="mini" data-onstyle="success" data-on="<i class='fas fa-thumbs-up'></i>" data-off="-" value="1">
                </div>
                <div class="col-sm-2">
                    <label class="label" style="font-size: 15px;">Pose</label>
                    <input class="modOptionPlanif" data-option="affairePose" type="checkbox" <?= $this->session->userdata('affairePose') ? "checked" : ''; ?> data-toggle="toggle" data-size="mini" data-onstyle="success" data-on="<i class='fas fa-thumbs-up'></i>" data-off="-" value="1">
                </div>
                <div class="col-sm-3 avancement <?= $devisEtat; ?>" >
                    <?php if ($affaire && $affaire->getAffaireDevisId()): ?>
                        <a href="<?= site_url('documents/editionDevis/' . $affaire->getAffaireId()); ?>" target="_blank" style="color: inherit; cursor: pointer">
                            <i class="fas fa-file-pdf"></i> Devis N°<?= $affaire->getAffaireDevisId(); ?>
                        </a>
                        <?php
                    elseif ($affaire):
                        echo '<span id="btnGenereDevis" style="cursor: pointer">Générer le devis</span>';
                    else:
                        echo '<span>Générer le devis</span>';
                    endif;

                    if ($affaire && $affaire->getAffaireDevisDate()):
                        ?>
                        <button class="btnInvisible" style="position: absolute; top:0px; left: 25px; color: #FFF;"
                                data-toggle="popover" data-trigger="click"
                                title="<span style='color: black; font-weight: bold;'>Modifier les infos du devis</span>" data-html="true" data-placement="bottom"
                                data-content='<form id="formModDevis" method="POST" action="<?= site_url('affaires/modifierDevis'); ?>">
                                <input type="hidden" name="affaireId" value="<?= $affaire->getAffaireId(); ?>">
                                <input class="form-control" type="date" name="modDevisDate" value="<?= date('Y-m-d', $affaire->getAffaireDevisDate()); ?>" >
                                <div class="input-group"><span class="input-group-addon">Acompte %</span><input class="form-control" type="text" name="modDevisAcompte" value="<?= $affaire->getAffaireDevisTauxAcompte(); ?>" ></div>
                                <div class="input-group"><span class="input-group-addon">Envoyé</span><input type="checkbox" name="modDevisEnvoye" value="1" <?= $affaire->getAffaireDevisEnvoye() ? 'checked' : ''; ?> ></div>
                                <button class="btn btn-sm btn-primary" style="width: 100%;">Modifier</button>
                                </form>' ><i class="fas fa-cog"></i></button>

                    <?php endif; ?>
                </div>
                <div class="col-sm-3 avancement <?= $commandeEtat; ?>" >
                    <?php if ($affaire && $affaire->getAffaireCommandeId()): ?>

                        <a href="<?= site_url('documents/editionFicheAtelier/' . $affaire->getAffaireId()); ?>" target="_blank" style="color: inherit; cursor: pointer">
                            <i class="fas fa-file-pdf"></i> Fiche Atelier
                        </a>

                        <?php
                    elseif ($affaire && $affaire->getAffaireDevisId()):
                        echo '<span id="btnGenereCommande" style="cursor: pointer">Générer la commande</span>';
                    else:
                        echo 'Commande';
                    endif;

                    if ($affaire && $affaire->getAffaireCommandeDate()):
                        ?>
                        <button class="btnInvisible" style="position: absolute; top:0px; left: 23px; color: #FFF;"
                                data-toggle="popover" data-trigger="click"
                                title="<span style='color: black; font-weight: bold;'>Modifier date commande</span>" data-html="true" data-placement="bottom"
                                data-content='<form id="formModCommande" method="POST" action="<?= site_url('affaires/modifierCommande'); ?>">
                                <input type="hidden" name="affaireId" value="<?= $affaire->getAffaireId(); ?>">
                                <input class="form-control" type="date" name="modCommandeDate" value="<?= date('Y-m-d', $affaire->getAffaireCommandeDate()); ?>" >
                                <div class="input-group"><span class="input-group-addon">Certifiée</span><input type="checkbox" name="modCommandeCertifiee" value="1" <?= $affaire->getAffaireCommandeCertifiee() ? 'checked' : ''; ?> ></div>
                                <button class="btn btn-sm btn-primary" style="width: 100%;">Modifier</button>
                                </form>' ><i class="fas fa-cog"></i></button>
                            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-link" id="openDetails">Afficher les détails</button>

    <div class="row" style="background-color: #e1e9f8; border: 1px solid darkblue; border-radius: 3px; position: relative; top: -35px;" id="details">

        <button class="btnInvisible" id="closeDetails" style="color: darkblue; font-size: 18px; position: absolute; top:5px; right: 5px; cursor: pointer; z-index: 20;">
            <i class="fas fa-times"></i>
        </button>

        <div class="col-sm-8" style="border-bottom: 1px dashed darkblue; padding: 1px 10px 1px 1px;">
            <input type="text" id="affaireObjet" placeholder="Description de l'affaire" class="form-control" value="<?= $this->session->userdata('affaireObjet'); ?>" >
        </div>
        <div class="col-sm-4" style="border-bottom: 1px dashed darkblue; padding: 1px 30px 1px 1px;">
            <div class="form-group" style="margin: 0px;">
                <div class="col-sm-12 input-group">
                    <span class="input-group-addon">
                        Type d'affaire
                    </span>
                    <select id="affaireType" class="form-control">
                        <option value="1" <?php if ($this->session->userdata('affaireType') == 1) echo 'selected'; ?> >Prestation de service</option>
                        <option value="2" <?php if ($this->session->userdata('affaireType') == 2) echo 'selected'; ?> >Vente de marchandises</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-3" style="border-right: 1px solid black;  padding: 0px 8px 0px 8px;">

            <h4><button id="btnClientSearch" class="btnInvisible"><i class="fas fa-plus-square" style="color: #007fff; font-size: 14px; cursor: pointer;"></i></button> Clients associés</h4>
            <table id="tableAffaireClients" class="table table-bordered table-condensed" style="font-size: 11px; background-color: #FFF;">
                <thead>
                    <tr style="background-color: lightgoldenrodyellow;">
                        <th style="width: 30px;"></th>
                        <th>Raison Sociale</th>
                        <th style="width: 25px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->session->userdata('affaireClients') as $c):
                        ?>
                        <tr data-clientid="<?= $c->clientId; ?>" >
                            <td style="text-align: center;">
                                <?php
                                if ($c->principal == 1):
                                    echo '<i class="fas fa-shield-alt" style="color: orangered;"></i>';
                                else:
                                    echo '<button class="btnInvisible devenirPrincipal"><i class="fas fa-shield-alt" style="color: lightgrey; cursor: pointer;" title="Devenir principal"></i></button>';
                                endif;
                                ?>
                            </td>
                            <td><?= $c->clientRaisonSociale; ?></td>
                            <td>
                                <?php
                                if ($c->principal):
                                    $isDeletable = 'none';
                                else:
                                    $isDeletable = 'block';
                                endif;
                                echo '<button class="btnInvisible btnDelAffaireClient" style="color: grey; cursor: pointer; display: ' . $isDeletable . ';"><i class="fas fa-trash"></i></button>';
                                ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
            <?php
            if ($this->session->userdata('affaireRemises')):
                echo 'Remises<table class="table table-bordered table-condensed" style="font-size: 11px; background-color: #FFF;">';
                foreach ($this->session->userdata('affaireRemises') as $remise):
                    echo '<tr><td>' . $remise['remiseFamille'] . '</td><td style="text-align: center;">' . $remise['remiseTaux'] . '%</td></tr>';
                endforeach;
                echo '</table>';
            endif;
            ?>
        </div>

        <div class="col-sm-4" style="border-right: 1px solid black; padding: 0px 8px 0px 8px;">
            <h4><button class="btnInvisible" data-toggle="modal" data-target="#modalFacturation"><i class="fas fa-plus-square" style="color: #007fff; font-size: 14px;"></i></button> Factures</h4>
            <table class="table table-condensed table-bordered" style="font-size:11px; background-color: #FFF;">
                <thead style="background-color: #EFEFEF;">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th style="width: 60px;">Date</th>
                        <th style="width: 100px;">Client</th>
                        <th style="text-align: right;">Montant</th>
                        <th style="text-align: right;">Solde</th>
                        <th style="width: 20px;">...</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($affaire && !empty($affaire->getAffaireFactures())):
                        foreach ($affaire->getAffaireFactures() as $f):
                            ?>
                            <tr data-factureid="<?= $f->getFactureId(); ?>">
                                <td>
                                    <a href="<?= site_url('facturation/ficheFacture/' . $f->getFactureId()); ?>"><?= 'FA' . $f->getFactureId(); ?></a>
                                </td>
                                <td><?= $f->getFactureDate() ? date('d/m/y', $f->getFactureDate()) : ''; ?></td>
                                <td>
                                    <?php
                                    foreach ($affaire->getAffaireClients() as $c):
                                        if ($c->getClientId() == $f->getFactureClientId()):
                                            echo substr($c->getClientRaisonSociale(), 0, 10) . '.';
                                        endif;
                                    endforeach;
                                    ?>
                                </td>
                                <td style="text-align: right;"><?= number_format($f->getFactureTotalTTC(), 2, ',', ' ') . '€'; ?></td>
                                <td style="text-align: right;"><?= number_format($f->getFactureSolde(), 2, ',', ' ') . '€'; ?></td>
                                <td style="color:grey;">
                                    <?=
                                    '<a href="' . site_url('documents/editionFacture/' . $f->getFactureId()) . '" target="_blank" >'
                                    . '<i class="fas fa-print printFacture" style="cursor: pointer;"></i>'
                                    . '</a>';
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

        <div class="col-sm-5" style="padding: 0px 8px 0px 8px;">
            <h4>
                <button class="btnInvisible" id="btnAddReglement">
                    <i class="fas fa-plus-square" style="color: #007fff; font-size: 14px;"></i>
                </button>
                Réglements
            </h4>
            <table class="table table-condensed table-bordered" style="font-size: 11px; background-color: #FFF;">
                <thead style="background-color: #EFEFEF;">
                    <tr>
                        <th></th>
                        <th style="width: 60px;">Date</th>
                        <th style="width: 100px;">RS</th>
                        <th style="width:20px;"></th>
                        <th style="width: 80px;">FACT N°</th>
                        <th style="width: 70px;">Mode</th>
                        <th style="width: 80px;">Montant</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($affaire && $affaire->getAffaireReglements()):
                        foreach ($affaire->getAffaireReglements() as $r):
                            ?>
                            <tr class="ligneReglement" data-reglementid="<?= $r->getReglementId(); ?>" >
                                <td style="width: 25px;">
                                    <?= $r->getReglementSecure() ? '<i class="fas fa-shield-alt" style="color: green;"></i>' : '<i class="fas fa-exclamation-triangle" style="color: red;"></i>'; ?>
                                </td>
                                <td><?= date('d/m/y', $r->getReglementDate()); ?></td>
                                <td><?= substr($r->getReglementClient()->getClientRaisonSociale(), 0, 8) . '.'; ?></td>
                                <td>
                                    <?php echo $r->getReglementType() == 1 ? 'A' : 'R'; ?>
                                </td>
                                <td>
                                    <?= $r->getReglementFactureNum() ? 'FA ' . $r->getReglementFactureNum() : ''; ?>
                                </td>
                                <td><?= $r->getReglementModeText(); ?></td>
                                <td style="text-align: right;"><?= number_format($r->getReglementMontant(), 2, ',', ' ') . '€'; ?></td>
                                <td>
                                    <button class="btnInvisible modReglement"><i class="fas fa-pencil-alt" style="margin-right: 4px; cursor: pointer; color: grey;"></i></button>
                                    <?php if ($r->getReglementHistorique()): ?>
                                        <i class="fas fa-history" style="color: purple;"></i>
                                    <?php endif;
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

    <div class="row">

        <div class="col-sm-12" style="padding: 0px;">

            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th style="width : 40px;"></th>
                        <th>Infos</th>
                        <th style="width : 90px; text-align: center;">Qte</th>
                        <th style="width : 90px; text-align: right;">PU</th>
                        <th style="width : 90px; text-align: center;">Remise</th>
                        <th style="width : 90px; text-align: right;">Total</th>
                        <th style="width : 80px;">...</th>
                    </tr>
                </thead>
                <tbody>



                    <?php foreach ((array) $this->cart->contents() as $item): ?>
                        <tr class="ligneArticle" data-rowid="<?= $item['rowid']; ?>" style="font-weight: bold; background-color: #EFEFEF" >
                            <td colspan="2">
                                <input type="text" class="form-flat autoSaveArticle" data-param="name" name="changeArticleDesignation" value="<?= $item['name']; ?>" style="font-size: 15px; width:100%;" ><br>
                                <textarea style="font-size:12px; width: 100%; font-weight: normal;" data-param="description" rows="2" class="form-flat autoSaveArticle" name="changeArticleDescription"><?= $item['description']; ?></textarea>
                                <span style="color: grey; font-size: 10px; font-weight: normal;" class="margeArticle" >Ref Fst: <?= round($item['marge']); ?></span>
                            </td>
                            <td style="width: 80px;">
                                <input type="text" value="<?= $item['qty']; ?>" class="form-flat-center autoSaveArticle" data-param="qty" style="width: 100%;" >
                            </td>
                            <td style="position: relative;">
                                <input type="text" value="<?= $item['prixVendu']; ?>" class="form-flat-right autoSaveArticle" data-param="prixVendu" style="width: 100%;" >
                                <?php
                                if ($item['prixVendu'] != $item['articleHT']):
                                    $aff = 'block';
                                else:
                                    $aff = 'none';
                                endif;
                                echo '<div style="font-size: 10px; color: orangered; cursor:pointer; display: ' . $aff . ';" class="pull-right resetPrixDeVente" data-valeur="' . $item['articleHT'] . '">'
                                . number_format($item['articleHT'], 2, ',', ' ') . '€'
                                . '</div>';
                                ?>
                            </td>
                            <td>
                                <input type="text" value="<?= $item['remise']; ?>" class="form-flat-center autoSaveArticle" data-param="remise" style="width: 100%;" >
                            </td>
                            <td style="padding: 7px; text-align: right;">
                                <?= number_format(round($item['price'] * $item['qty'], 2), 2, ',', ' '); ?>
                            </td>
                            <td style="text-align: right; position: relative;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-caret-square-down"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="btnAddComposant"><i class="fas fa-plus-square" style="color: #007fff;"></i> Ajouter un composant</a>
                                        </li>
                                        <li>
                                            <a class="btnDupliquerArticle"><i class="fas fa-copy" style="color: green;"></i> Dupliquer cet article</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a class="delArticle"><i class="fas fa-trash" style="color: palevioletred;"></i> Supprimer cet article</a>
                                        </li>
                                    </ul>
                                </div>
                                <button class="btnInvisible showOptions"><i class="fas fa-eye-slash" style="color: orangered; position: absolute; bottom: 3px; right: 3px; font-size: 15px; cursor: pointer;"></i></button>
                            </td>
                        </tr>
                        <?php
                        /**
                         * Affichage de toutes les options composant cet item
                         */
                        foreach ((array) $item['composants'] as $c):
                            if (!isset($bgcolor) || $bgcolor == '#FFF'):
                                $bgcolor = '#FFFFDD';
                            else:
                                $bgcolor = '#FFF';
                            endif;
                            ?>

                            <tr class="ligneOption" data-rowid="<?= $item['rowid']; ?>" data-optionid="<?= $c['optionId']; ?>" style="display: none; background-color: <?= $bgcolor; ?>; font-size: 11px;">
                                <td style="background-color:<?= $c['qte'] > 0 ? 'lightgreen' : 'orange'; ?>;"></td>
                                <td>
                                    <?= $c['name'] . ' <span style="font-style: italic; color: grey; font-size: 12px;">( ' . $c['optionUnite'] . ' )</span>'; ?>
                                </td>
                                <td style="text-align: center;">
                                    <input type="text" value="<?= $c['qte']; ?>" class="form-flat-center modOptionQte input-xs" style="width: 100%;" >
                                </td>
                                <td style="text-align: right;">
                                    <?= $c['prix']; ?>
                                </td>
                                <td colspan="3">
                                    <?php
                                    if ($c['originel'] == 0):
                                        echo '<i class="fas fa-trash delComposant" style="color: grey;"></i>';
                                    endif;
                                    ?>
                                </td>
                            </tr>

                            <?php
                        endforeach;
                    endforeach;
                    ?>

                    <tr style="background-color: #a1aec7;">
                        <td style="text-align: center; color: #FFFFFF;">
                            <i class="fas fa-plus"></i>
                        </td>
                        <td colspan="6">
                            <select class="selectpicker" id="newArticle" data-width="500" data-live-search="true" data-header="Choix de l'article" title="Selectionnez un article">
                                <?php
                                if (!empty($articles)):
                                    $famille = '';
                                    echo '<optgroup label="Non classés">';
                                    foreach ($articles as $a):
                                        if ($a->getArticleFamille() && $famille != $a->getArticleFamille()->getFamilleNom()):
                                            if ($famille != ''):
                                                echo '</optgroup>';
                                            endif;
                                            echo '<optgroup label="' . $a->getArticleFamille()->getFamilleNom() . '">';
                                            $famille = $a->getArticleFamille()->getFamilleNom();
                                        endif;
                                        echo '<option value="' . $a->getArticleId() . '">' . $a->getArticleDesignation() . '</option>';
                                    endforeach;
                                    echo '</optgroup>';
                                endif;
                                ?>
                            </select>
                            <button class="btn btn-primary" id="btnAddDossierArticle">
                                <i class="fas fa-plus-circle"></i> Ajouter au dossier
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

    </div>

    <div class="row">
        <div class="col-sm-4">
            <button class="btn btn-link" id="viderPanier">
                <i class="fas fa-trash"></i> Tout supprimer
            </button>
            <?php if ($affaire): ?>
                <br>
                <a class="btn btn-link" href="<?= site_url('ventes/reloadAffaire/' . $affaire->getAffaireId()); ?>" >
                    <i class="fas fa-redo-alt"></i> Annuler toutes les modifications
                </a>
                <?php
            endif;
            if ($affaire):
                ?>
                <button class="btn btn-link" style="color: steelblue;" id="btnDupliquerAffaire" data-affaireid="<?= $affaire->getAffaireId(); ?>">
                    <i class="fas fa-copy"></i> Dupliquer cette affaire
                </button>
                <?php if ($affaire->getAffaireCloture()): ?>
                    <br><button class="btn btn-link" style="color: green;" id="btnReouvrirAffaire" data-affaireid="<?= $affaire->getAffaireId(); ?>">
                        <i class="fas fa-unlock"></i> Ré-ouvrir cette affaire
                    </button>
                <?php else: ?>
                    <br><button class="btn btn-link" style="color: red;" id="btnCloturerAffaire" data-affaireid="<?= $affaire->getAffaireId(); ?>">
                        <i class="fas fa-lock"></i> Cloturer cette affaire
                    </button>
                <?php
                endif;
            endif;
            ?>
        </div>
        <div class="col-sm-4">

        </div>
        <div class="col-sm-4">
            <div style="background-color: #a1aec7; padding: 1px 10px 1px 10px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; position:relative; top: -30px; left: 14px;">
                <h3 style="color: gold;">Totaux</h3>
                <table class="table table-bordered table-condensed" style="background-color: #FFF;">
                    <tr>
                        <td>Total HT</td>
                        <td style="text-align: right; font-weight: bold;" id="totalAffaireHT">
                            <?= number_format($this->cart->total(), 2, ',', ' '); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Total TVA</td>
                        <td style="text-align: right; font-weight: bold;" id="totalAffaireTVA">
                            <?php
                            $tva = round($this->cart->total() * 0.2, 2);
                            echo number_format($tva, 2, ',', ' ');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Total TTC</td>
                        <td style="text-align: right; font-weight: bold;" id="totalAffaireTTC">
                            <?= number_format($this->cart->total() + $tva, 2, ',', ' '); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php
    if ($affaire && !empty($affaire->getAffaireAffectations())):
        ?>
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <h4>Affectations de l'affaire</h4>
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr style="background-color: #04335a; color: #FFF;">
                            <th style="width: 80px;">Date</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 170px;">Intervenants</th>
                            <th>Commentaire</th>
                            <th style="width: 80px;">Etat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($affaire->getAffaireAffectations() as $affectation): ?>
                            <tr>
                                <td><?= date('d/m/y', $affectation->getAffectationDate()); ?></td>
                                <td><?php
                                    switch ($affectation->getAffectationType()):
                                        case 3:
                                            echo 'PAO';
                                            break;
                                        case 1:
                                            echo 'Fabrication';
                                            break;
                                        case 2:
                                            echo 'Pose';
                                            break;
                                    endswitch;
                                    ?>
                                </td>
                                <td><?= $affectation->getAffectationIntervenant(); ?></td>
                                <td><?= $affectation->getAffectationCommentaire(); ?></td>
                                <td><?php
                                    switch ($affectation->getAffectationEtat()):
                                        case 3:
                                            echo 'Terminé';
                                            break;
                                        case 1:
                                            echo 'Attente';
                                            break;
                                        case 2:
                                            echo 'En cours';
                                            break;
                                    endswitch;
                                    ?></td>
                            </tr>


                            <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Confirmation de génération de factures -->
<div class="modal fade" id="modalConfirmFacture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class="btn-group btn-sm" style="position:absolute; top:3px; right:0px;">
                    <button type="button" class="btn btn-link btn-xs" data-dismiss="modal" aria-hidden="true">
                        <i class="fas fa-close"></i>
                    </button>
                </div>
                <h4 class="modal-title">Confirmation ?</h4>
            </div>
            <div class="modal-body">
                Vous allez générer une facture définitive.
                <br><strong>Elle ne sera plus modifiable</strong>
                <br>Êtes-vous sûr de vouloir continuer ?
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-6" style="text-align: left;">
                        <button class="btn btn-default" data-dismiss="modal"><i class="fas fa-close"></i> Annuler</button>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-success" id="genereFacture"><i class="fas fa-check"></i> Générer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalAddComposantToArticle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
                <h4 class="modal-title">Ajouter un composant</h4>
            </div>
            <div class="modal-body">

                <span>
                    Vous pouvez ajouter soit un composant de votre catalogue, soit un composant libre.
                </span>
                <hr>
                <?= form_open('', array('class' => 'form-horizontal', 'id' => 'formAddComposantToArticle')); ?>
                <input type="hidden" name="addComposantRowId" id="addComposantRowId" value="">
                <div class="form-group">
                    <label for="addComposantId" class="col-sm-3">
                        Choix du composant
                    </label>
                    <div class="col-sm-5">
                        <select class="selectpicker" id="composantChoix" name="addComposantId" data-width="350" data-live-search="true" title="Selectionnez un composant">
                            <?php
                            if (!empty($composants)):
                                $famille = '';
                                echo '<optgroup label="Non classés">';
                                foreach ($composants as $c):
                                    if ($c->getComposantFamille() && $famille != $c->getComposantFamille()->getFamilleNom()):
                                        if ($famille != ''):
                                            echo '</optgroup>';
                                        endif;
                                        echo '<optgroup label="' . $c->getComposantFamille()->getFamilleNom() . '">';
                                        $famille = $c->getComposantFamille()->getFamilleNom();
                                    endif;
                                    echo '<option value="' . $c->getComposantId() . '" data-subtext="(' . $c->getComposantUnite()->getUniteNom() . ')">' . $c->getComposantDesignation() . '</option>';
                                endforeach;
                                echo '</optgroup>';
                            endif;
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="selectpicker" id="optionChoix" name="addComposantOptionId" required data-header="Sélectionnez une option" title="Sélectionnez une option"></select>

                    </div>
                </div>
                <div class="form-group">
                    <label for="addComposantQte" class="col-sm-3">
                        Quantité
                    </label>
                    <div class="col-sm-3">
                        <input type="text" name="addComposantQte" id="addComposantQte" class="form-control" value="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-plus-square"></i> Ajouter à l'article
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- client_search -->
<div class="modal fade" id="modalClientSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="btn-group btn-sm" style="position:absolute; top:5px; right:5px;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnAddClient">
                        <i class="glyphicon glyphicon-plus"></i> Ajouter un client
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
                        <i class="glyphicon glyphicon-remove"> </i>
                    </button>
                </div>
                <h4 class="modal-title"><i class="glyphicon glyphicon-search"></i> Ajouter un client</h4>

            </div>
            <div class="modal-body" id="modal_body_ft">

                <input type="text" class="form-control" id="clientSearch" placeholder="Rechercher un client" >

                <table class="table table-condensed table-bordered table-hover" id="clientSearchTable" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th>Raison sociale</th>
                            <th>Adresse</th>
                            <th>Ville</th>
                            <th>Téléphone</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include('application/views/clients/formClient.php'); ?>
<?php
if ($affaire):
    include('application/views/ventes/formReglement.php');
    include('application/views/ventes/modalFacturation.php');
endif;
?>