<input type="hidden" id="premierJour" value="<?= $premierJourSemaine; ?>">
<input type="hidden" id="dernierJour" value="<?= $dernierJourSemaine; ?>">

<div class="container-fluid">
    <div class="row" style="margin-top : 0px; background-color: #FFF; border-top: 1px solid grey; border-bottom : 1px solid grey; padding: 10px 0px 10px 0px;">
        <div class="col-xs-12 col-sm-2" style="text-align: center;">

            <?php
            //recherche des semaines et années pour le Next et le Previous
            if ($semaine > 1):
                $prevSemaine = $semaine - 1;
                $prevAnnee = $annee;
            else:
                $prevSemaine = date('W', mktime(0, 0, 0, 12, 30, $annee - 1));
                $prevAnnee = $annee - 1;
            endif;
            if ($semaine < date('W', mktime(0, 0, 0, 12, 30, $annee))):
                $nextSemaine = $semaine + 1;
                $nextAnnee = $annee;
            else:
                $nextSemaine = 1;
                $nextAnnee = $annee + 1;
            endif;
            ?>

            <div class="btn dateSelect" data-date="<?= date('d-m-Y', $premierJourSemaine); ?>" style="border: 1px solid grey;">

            </div>
            <!--
            <a href="<?= site_url('ed/hebdomadaire/' . date('W/Y')); ?>" class="btn btn btn-info"><?= 'Semaine ' . $semaine . ' - ' . $annee; ?></a>
            -->
            <br>
            <a href="<?= site_url('ed/hebdomadaire/' . $prevSemaine . '/' . $prevAnnee); ?>" class="btn btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <a href="<?= site_url('ed/hebdomadaire/' . $nextSemaine . '/' . $nextAnnee); ?>" class="btn btn btn-default"><i class="glyphicon glyphicon-chevron-right"></i></a>
            <?php if ($this->ion_auth->is_admin()): ?>
                <br><br><button class="btn btn-primary btnAddDossier" type="button" style="width: 100%;"><i class="fas fa-plus-circle"></i> Ajouter un dossier</button>
            <?php endif;
            ?>
        </div>


        <div class="col-xs-12 col-sm-10" style="padding-left: 50px; padding-right : 50px; min-height:">

            <div class="row">
                <div class="col-xs-12" style="border:1px solid grey; border-radius: 5px; min-height: 200px; padding:10px;">

                    <div id="planning">

                        <table class="table table-bordered">
                            <thead>
                            <th style="width:10%;">Postes</th>
                                <?php
                                for ($i = 0; $i < 5; $i++):
                                    $timeJourEncours = $premierJourSemaine + $i * 86400;
                                    echo '<th style="text-align: center; width:10%;">' . $this->lang->line('cal_' . strtolower(date('l', $timeJourEncours))) . ' ' . date('d', $timeJourEncours) . ' ' . $this->lang->line('cal_' . strtolower(date('F', $timeJourEncours))) . ' ' . date('Y', $timeJourEncours) . '</th>';
                                endfor;
                                ?>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($postes as $id => $poste):
                                    echo '<tr><td>' . $poste . '</td>';
                                    for ($j = 0; $j < 6; $j++):
                                        echo '<td style="width:18%;"' . ($id == 5 ? '' : ' class="jour organisable"') . ' id="' . $id . '-' . date('d', $premierJourSemaine + $j * 86400) . '" data-equipeid="' . $id . '" data-date="' . date('Y-m-d', $premierJourSemaine + $j * 86400) . '">';
                                        if (!empty($divs[$id . '-' . date('d', $premierJourSemaine + $j * 86400)])):
                                            foreach ($divs[$id . '-' . date('d', $premierJourSemaine + $j * 86400)] as $key => $value):
                                                echo $value;
                                            endforeach;
                                        endif;

                                        echo '</td>';
                                    endfor;
                                    echo '</tr>';
                                endforeach;
                                ?>

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?php include('application/views/forms.php'); ?>

