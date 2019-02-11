<br><strong>Objet : </strong><?= $affaire->getAffaireObjet(); ?>
<br><br>
<table class="table table-bordered" cellspacing="0" cellpadding="2" style="font-size:10px; width: 520px;">
    <tr style="background-color: #eae6e6;">
        <td style="width: 480px;">Description</td>
        <td style="text-align: center; width:40px;">Qte</td>
    </tr>

    <?php
    foreach ($articles as $a):
        if ($a->getAffaireArticleQte() > 0):
            ?>
            <tr nobr="true">
                <td style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: 1px solid grey;">
                    <span style="font-weight: bold; font-size: 10px;">
                        <?= $a->getAffaireArticleDesignation(); ?>
                    </span>
                    <span style="font-size: 9px;">
                        <?= '<br>' . nl2br($a->getAffaireArticleDescription()); ?>
                    </span>
                </td>
                <td style="text-align: center; border-top: 1px solid grey; border-bottom: 1px solid grey;">
                    <?= number_format($a->getAffaireArticleQte(), 0, ',', ' '); ?>
                </td>
            </tr>
            <?php
        endif;
    endforeach;
    ?>

</table>

<br>
<br>
<table style="font-size:11px; width:500px;" nobr="true">
    <tr>
        <td style="width:260px; font-size:10px;">
            Observations ou réserves du client

        </td>
        <td style="width:230px; text-align:right; font-size:10px;">
            Cachet, date et signature<br>précédés de "Bon pour réception de chantier"
        </td>
    </tr>
</table>