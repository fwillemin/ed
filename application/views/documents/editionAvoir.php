<!--<br>
<br>
<table>
    <tr style="font-size:12px;">
        <td style="text-align: right; width:283px; ">
        </td>
        <td style="text-align: right; width:300px; ">

            <table style="width:270px;" cellspacing="0" cellpadding="2">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold; height: 20px; font-size:15px; border: 1px solid black;">
                        AVOIR N°<?= $avoir->getAvoirId(); ?>
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 90px; border: 1px solid black;">N° Avoir</td>
                    <td style="width: 90px; border: 1px solid black;">Date</td>
                    <td style="width: 90px; border: 1px solid black;">Facture liée</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px; border: 1px solid black;"><?= $avoir->getAvoirId(); ?></td>
                    <td style=" height: 20px; border: 1px solid black;"><?= date('d/m/Y', $avoir->getAvoirDate()); ?></td>
                    <td style=" height: 20px; border: 1px solid black;"><?= $avoir->getAvoirFactureId(); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-size:12px;">
<?php
$client = $avoir->getAvoirClient();
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
</table>-->
<br><strong>Objet : </strong>Avoir sur la facture N°<?= $avoir->getAvoirFactureId(); ?>
<br><br>
<table class="table table-bordered" cellspacing="0" cellpadding="2" style="font-size:10px; width: 540px; border: 1px solid black;">
    <tr style="background-color: lightgrey;">
        <td style="width: 320px;">Description</td>
        <td style="text-align: center; width:50px;">Qte</td>
        <td style="text-align: right; width:80px;">PU HT</td>
        <td style="text-align: right; width:80px;">Total HT</td>
    </tr>

    <?php
    foreach ($avoir->getAvoirLignes() as $a):
        ?>
        <tr>
            <td style="border-bottom: 1px solid grey;">
                <span style="font-weight: bold; font-size: 10px;">
                    <?= $a->getAvoirLigneDesignation(); ?>
                </span>
                <span style="font-size: 9px;">
                    <?= '<br>' . nl2br($a->getAvoirLigneDescription()); ?>
                </span>
            </td>
            <td style="text-align: center; border-bottom: 1px solid grey;">
                <?= number_format($a->getAvoirLigneQte(), 0, ',', ' '); ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?= number_format($a->getAvoirLignePrixUnitaire(), 2, ',', ' '); ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?= number_format($a->getAvoirLigneTotalHT(), 2, ',', ' '); ?>
            </td>

        </tr>
        <?php
    endforeach;
    ?>

</table>

<br>
<br>
<table style="font-size:11px; width:550px;">
    <tr>
        <td style="width:200px; border: none;"></td>
        <td style="width:100px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total HT</td>
        <td style="width:50px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">%TVA</td>
        <td style="width:80px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total TVA</td>
        <td style="width:100px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total TTC</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($avoir->getAvoirTotalHT(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right; border: 1px solid black;">20.00</td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($avoir->getAvoirTotalTVA(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($avoir->getAvoirTotalTTC(), 2, ',', ' ') . '€'; ?></td>
    </tr>
</table>
<br>
<br>
<br>
