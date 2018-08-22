<hr>
<br><strong>Objet : </strong><?= $affaire->getAffaireObjet(); ?>
<br><br>
<table cellspacing="0" cellpadding="2" style="font-size:12px; width: 800px;">
    <tr style="background-color: #eae6e6;">
        <td style="width: 350px;">Description</td>
        <td style="text-align: center; width:50px;">Qte</td>
        <td style="width:400px;">Matériel</td>
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
                <td style="border-top: 1px solid grey; border-bottom: 1px solid grey;">
                    <table border="1" cellpadding="3">
                        <?php
                        foreach ($a->getAffaireArticleOptions() as $option):
                            if ($option->getAffaireOptionQte() > 0):
                                echo '<tr>'
                                . '<td style="width: 70px; text-align: right; font-size: 12px;">' . $option->getAffaireOptionQte() . $option->getAffaireOptionComposant()->getComposantUnite()->getUniteSymbole() . '</td>'
                                . '<td style="width: 315px; font-size: 11px;">' . $option->getAffaireOptionComposant()->getComposantDesignation() . ' :: ' . $option->getAffaireOptionOption()->getOptionNom() . '</td>'
                                . '</tr>';
                            endif;
                        endforeach;
                        ?>
                    </table>
                </td>
            </tr>
            <?php
        endif;
    endforeach;
    ?>

</table>
<br>
<br>

<table border="1" cellpadding="10">
    <tr><td style="width: 700px; color: grey;">Commentaires<br><br><br><br><br></td></tr>
</table>