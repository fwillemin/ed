<br>
<br>
<table>
    <tr style="font-size:12px;">
        <td style="text-align: right; width:250px; ">
        </td>
        <td style="text-align: right; width:300px; ">

            <table style="width:300px;" cellspacing="0" border="1" cellpadding="2">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold; height: 20px; font-size:15px;">
                        FACTURE PROVISOIRE
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 100px;">N° Facture</td>
                    <td style="width: 100px;">Date</td>
                    <td style="width: 100px;">Réglement</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px;">-</td>
                    <td style=" height: 20px;">-</td>
                    <td style=" height: 20px;"><?= $facture->getFactureModeReglementText(); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-size:13px;">
                        <?php
                        $client = $facture->getFactureClient();
                        echo '<br><br>' . $client->getClientRaisonSociale()
                        . '<br>' . $client->getClientAdresse1();
                        if ($client->getClientAdresse2()):
                            echo '<br>' . $client->getClientAdresse2();
                        endif;
                        echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille()
                        . '<br>' . $client->getClientPays();
                        ?>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<br>
<br>
<br><strong>Objet : </strong><?= $facture->getFactureObjet(); ?>
<br><br>
<table class="table table-bordered" cellspacing="0" cellpadding="2" style="font-size:10px; width: 540px; border: 1px solid black;">
    <tr style="background-color: lightgrey;">
        <td style="width: 270px;">Description</td>
        <td style="text-align: center; width:30px;">Qte</td>
        <td style="text-align: right; width:60px;">PU HT</td>
        <td style="text-align: center; width:40px;">Remise</td>
        <td style="text-align: right; width:60px;">PU Net</td>
        <td style="text-align: right; width:70px;">Total HT</td>
    </tr>

    <?php
    foreach ($facture->getFactureLignes() as $a):
        ?>
        <tr>
            <td style="border-bottom: 1px solid grey;">
                <span style="font-weight: bold; font-size: 10px;">
                    <?= $a->getFactureLigneDesignation(); ?>
                </span>
                <br>
                <span style="font-size: 9px;">
                    <?= $a->getFactureLigneDescription(); ?>
                </span>
            </td>
            <td style="text-align: center; border-bottom: 1px solid grey;">
                <?php echo number_format($a->getFactureLigneQte(), 0, ',', ' '); ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?php echo number_format($a->getFactureLigneTarif(), 2, ',', ' '); ?>
            </td>
            <td style="text-align: center; border-bottom: 1px solid grey;">
                <?php echo number_format($a->getFactureLigneRemise(), 0, ',', ' ') . '%'; ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?php echo number_format( round($a->getFactureLigneTarif() * (100 - $a->getFactureLigneRemise()) /100, 2), 2, ',', ' '); ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?php echo number_format($a->getFactureLigneTotalHT(), 2, ',', ' '); ?>
            </td>

        </tr>
        <?php
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
        <td style="text-align:right;"><?php echo number_format($facture->getFactureTotalHT(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right;"><?php echo number_format($facture->getFactureTotalTVA(), 2, ',', ' ') . '€'; ?></td>
        <td></td>
        <td style="text-align:right;"><?php echo number_format($facture->getFactureTotalHT(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right;"><?php echo number_format($facture->getFactureTotalTVA(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right;"><?php echo number_format($facture->getFactureTotalTTC(), 2, ',', ' ') . '€'; ?></td>
    </tr>
</table>
<br>
<br>
<br>
<br>
<span style="font-size:15px; font-weight: bold;">
    !! FACTURE PROVISOIRE !!
</span>