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
        <tr nobr="true">
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
<table style="font-size:11px; width:550px;" nobr="true">
    <tr nobr="true">
        <td style="width:200px; border: none;"></td>
        <td style="width:100px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total HT</td>
        <td style="width:50px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">%TVA</td>
        <td style="width:80px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total TVA</td>
        <td style="width:100px; text-align:right; background-color: lightgrey; font-weight: bold; border: 1px solid black; border: 1px solid black;">Total TTC</td>
    </tr>
    <tr nobr="true">
        <td></td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($avoir->getAvoirTotalHT(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right; border: 1px solid black;">20.00</td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($avoir->getAvoirTotalTVA(), 2, ',', ' ') . '€'; ?></td>
        <td style="text-align:right; border: 1px solid black;"><?= number_format($avoir->getAvoirTotalTTC(), 2, ',', ' ') . '€'; ?></td>
    </tr>
</table>
<br>
<br>
<?php
if ($avoir->getAvoirCommentaire()):
    echo '<span style="font-size: 11px;"><strong>Commentaire : </strong>' . $avoir->getAvoirCommentaire() . '</span>';
endif;
?>