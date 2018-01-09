<br>
<br>
<table>
    <tr style="font-size:12px;">
        <td style="text-align: right; width:250px; ">
        </td>
        <td style="text-align: right; width:300px; ">

            <table style="width:270px;" cellspacing="0" border="1" cellpadding="2">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold; height: 20px; font-size:15px;">
                        DEVIS
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 90px;">N° devis</td>
                    <td style="width: 90px;">Date</td>
                    <td style="width: 90px;">Code Affaire</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px;"><?= $affaire->getAffaireDevisId(); ?></td>
                    <td style=" height: 20px;"><?= date('d/m/Y', $affaire->getAffaireDevisDate()); ?></td>
                    <td style=" height: 20px;"><?= $affaire->getAffaireId(); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-size:13px;">
                        <?php
                        echo '<br><br>' . $client->getClientRaisonSociale() . '<span style="color: #FFF;">____</span>'
                        . '<br>' . $client->getClientAdresse1() . '<span style="color: #FFF;">____</span>';
                        if ($client->getClientAdresse2()):
                            echo '<br>' . $client->getClientAdresse2() . '<span style="color: #FFF;">____</span>';
                        endif;
                        echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille() . '<span style="color: #FFF;">____</span>'
                        . '<br>' . $client->getClientPays() . '<span style="color: #FFF;">____</span>';
                        ?>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<br>
<br>
<br><strong>Date de validité : </strong><?= date('d/m/Y', $affaire->getAffaireDevisDate() + 2592000); ?>
<br><strong>Objet : </strong><?= $affaire->getAffaireObjet(); ?>
<br><br>
<table class="table table-bordered" cellspacing="0" cellpadding="2" style="font-size:10px; width: 540px;">
    <tr style="background-color: #eae6e6;">
        <td style="width: 270px;">Description</td>
        <td style="text-align: center; width:30px;">Qte</td>
        <td style="text-align: right; width:60px;">PU HT</td>
        <td style="text-align: center; width:40px;"></td>
        <td style="text-align: right; width:60px;">PU Net</td>
        <td style="text-align: right; width:70px;">Total HT</td>
    </tr>

    <?php
    foreach ($articles as $a):
        if ($a->getAffaireArticleQte() > 0):
            ?>
            <tr>
                <td style="border-bottom: 1px solid grey;">
                    <span style="font-weight: bold; font-size: 10px;">
                        <?= $a->getAffaireArticleDesignation(); ?>
                    </span>
                    <br>
                    <span style="font-size: 9px;">
                        <?= $a->getAffaireArticleDescription(); ?>
                    </span>
                </td>
                <td style="text-align: center; border-bottom: 1px solid grey;">
                    <?= number_format($a->getAffaireArticleQte(), 0, ',', ' '); ?>
                </td>
                <td style="text-align: right; border-bottom: 1px solid grey;">
                    <?= number_format($a->getAffaireArticleTarif(), 2, ',', ' '); ?>
                </td>
                <td style="text-align: center; border-bottom: 1px solid grey;">
                    <?php
                    if ($a->getAffaireArticleRemise() > 0):
                        echo number_format($a->getAffaireArticleRemise(), 0, ',', ' ') . '%';
                    endif;
                    ?>
                </td>
                <td style="text-align: right; border-bottom: 1px solid grey;">
                    <?= number_format($a->getAffaireArticlePU(), 2, ',', ' '); ?>
                </td>
                <td style="text-align: right; border-bottom: 1px solid grey;">
                    <?= number_format($a->getAffaireArticleTotalHT(), 2, ',', ' '); ?>
                </td>

            </tr>
            <?php
        endif;
    endforeach;
    ?>

</table>

<br>
<br>
<table style="font-size:11px; width:550px;" border="1">
    <tr>
        <td style="width:50px; text-align:right;">%TVA</td>
        <td style="width:90px; text-align:right;">Base HT</td>
        <td style="width:80px; text-align:right;">Montant TVA</td>
        <td style="width:30px; border: none;"></td>
        <td style="width:100px; text-align:right; background-color: lightgrey; font-weight: bold;">Total HT</td>
        <td style="width:80px; text-align:right; background-color: lightgrey; font-weight: bold;">Total TVA</td>
        <td style="width:100px; text-align:right; background-color: lightgrey; font-weight: bold;">Total TTC</td>
    </tr>
    <tr>
        <td style="text-align:right;">20.00</td>
        <td style="text-align:right;"><?= number_format($affaire->getAffaireTotalHT(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right;"><?= number_format($affaire->getAffaireTotalTVA(), 2, ',', ' ') . '€'; ?></td>
        <td></td>
        <td style="text-align:right;"><?= number_format($affaire->getAffaireTotalHT(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right;"><?= number_format($affaire->getAffaireTotalTVA(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right;"><?= number_format($affaire->getAffaireTotalTTC(), 2, ',', ' ') . '€'; ?></td>
    </tr>
</table>
<br>
<br>
<br>
<br>
<span style="font-size:10px;">
    Cachet, date et signature précédés de "Bon pour accord"
</span>