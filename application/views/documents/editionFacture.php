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
        <tr nobr="true">
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
                <?= number_format(round($a->getFactureLigneTarif() / (1 - $a->getFactureLigneRemise() / 100), 2), 2, ',', ' '); ?>
            </td>
            <td style="text-align: center; border-bottom: 1px solid grey;">
                <?php
                if ($a->getFactureLigneRemise() > 0):
                    echo number_format($a->getFactureLigneRemise(), 0, ',', ' ') . '%';
                endif;
                ?>
            </td>
            <td style="text-align: right; border-bottom: 1px solid grey;">
                <?= number_format($a->getFactureLigneTarif(), 2, ',', ' '); ?>
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
<table style="font-size:11px; width:550px;" nobr="true">
    <tr>
        <td rowspan="2" style="width:230px; border: none; font-size:9px;">
            <br>Mode de réglement : <?= $facture->getFactureModeReglementText(); ?>
            <br>Echéance : <?=
            $facture->getFactureEcheanceTexte();
            if (in_array($facture->getFactureEcheanceId(), [2, 3])) :
                echo '<br>Soit le ' . date('d/m/y', $facture->getFactureEcheanceDate());
            endif;
            ?>
        </td>
        <td style="width:80px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total HT</td>
        <td style="width:50px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">%TVA</td>
        <td style="width:80px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total TVA</td>
        <td style="width:80px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total TTC</td>
    </tr>
    <tr>
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
    <br>En cas de retard de paiement, des pénalités équivalentes à 25% du montant dû ainsi qu'un forfait de recouvrement de 100€ seront exigibles
    <br>À défaut de paiement du prix à l'échéance convenue, Enseigne Diffusion pourra reprendre les marchandises.
    <br>La vente sera alors résolue de plein droit et les acomptes déjà versés lui resteront acquis en contrepartie de la jouissance des marchandises dont aura bénéficié l'acheteur.
    <br>Sauf indication contraire, aucun escompte pour paiement anticipé.
</span>