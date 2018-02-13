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
                        FACTURE N°<?= $facture->getFactureId(); ?>
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 90px; border: 1px solid black;">N° Facture</td>
                    <td style="width: 90px; border: 1px solid black;">Date</td>
                    <td style="width: 90px; border: 1px solid black;">Réglement</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px; border: 1px solid black;"><?= $facture->getFactureId(); ?></td>
                    <td style=" height: 20px; border: 1px solid black;"><?= date('d/m/Y', $facture->getFactureDate()); ?></td>
                    <td style=" height: 20px; border: 1px solid black;"><?= $facture->getFactureModeReglementText(); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-size:12px;">
<?php
$client = $facture->getFactureClient();
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
<br><strong>Objet : </strong><?= $facture->getFactureObjet(); ?>
<br><br>
<table class="table table-bordered" cellspacing="0" cellpadding="2" style="font-size:10px; width: 540px; border: 1px solid black;">
    <tr style="background-color: lightgrey;">
        <td style="width: 270px;">Description</td>
        <td style="text-align: center; width:30px;">Qte</td>
        <td style="text-align: right; width:60px;">PU HT</td>
        <td style="text-align: center; width:40px;"></td>
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
                <span style="font-size: 9px;">
                    <?= '<br>' . nl2br($a->getFactureLigneDescription()); ?>
                </span>
            </td>
            <td style="text-align: center; border-bottom: 1px solid grey;">
                <?= number_format($a->getFactureLigneQte(), 2, ',', ' '); ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?= number_format($a->getFactureLigneTarif(), 2, ',', ' '); ?>
            </td>
            <td style="text-align: center; border-bottom: 1px solid grey;">
                <?php
                if ($a->getFactureLigneRemise() > 0):
                    echo number_format($a->getFactureLigneRemise(), 0, ',', ' ') . '%';
                endif;
                ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?= number_format(round($a->getFactureLigneTarif() * ( 100 + $a->getFactureLigneRemise() ) / 100, 2), 2, ',', ' '); ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?php
                echo number_format($a->getFactureLigneTotalHT(), 2, ',', ' ');
                if ($a->getFactureLigneQuota() < 100):
                    echo '<br><span style="font-weight: bold;">QuotePart<br>' . $a->getFactureLigneQuota() . '%</span>';
                endif;
                ?>
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
        <td style="text-align:right; border: 1px solid black;"><?= number_format($facture->getFactureTotalHT(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right; border: 1px solid black;">20.00</td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($facture->getFactureTotalTVA(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($facture->getFactureTotalTTC(), 2, ',', ' ') . '€'; ?></td>
    </tr>
</table>
<br>
<br>
<br>
<span style="font-size:9px; font-weight: bold;">
    <br>Enseigne Diffusion se réserve la propriété des marchandises désignées sur ce document, jusqu'au paiement intégral de leur prix en principal et intérêts.
    <br>À défaut de paiement du prix à l'échéance convenue, Enseigne Diffusion pourra reprendre les marchandises.
    <br>La vente sera alors résolue de plein droit et les acomptes déjà versés lui resteront acquis en contrepartie de la jouissance des marchandises dont aura bénéficié l'acheteur.
</span>