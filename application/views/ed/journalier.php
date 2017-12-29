<input type="hidden" id="jourActuel" value="<?php echo $jour; ?>">

<div class="row" style="margin-top : 0px; background-color: #FFF; padding: 15px 0px 15px 0px; margin: 0px; border-top : 1px solid grey;">
    <div class="col-xs-12" style="text-align: center;">

        <?php
        //recherche des jours pour le Next et le Previous
        ?>
        <div class="btn-group">
            <a href="<?php echo site_url('ed/journalier/' . date('Y-m-d', ($jour - 86400))); ?>" class="btn btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <button type="button" id="btnShowCalendar" class="btn btn btn-info">
                <?php echo $this->lang->line('cal_' . strtolower(date('l', $jour))) . ' ' . date('d', $jour) . ' ' . $this->lang->line('cal_' . strtolower(date('F', $jour))) . ' ' . date('Y', $jour) ?>
            </button>
            <div class="btn" id="dateSelectJour" data-date="<?php echo date('d-m-Y', $jour); ?>" style="display: none;">

            </div>
            <a href="<?php echo site_url('ed/journalier/' . date('Y-m-d', ($jour + 86400))); ?>" class="btn btn btn-default"><i class="glyphicon glyphicon-chevron-right"></i></a>
        </div>
        <?php if ($this->ion_auth->is_admin()): ?>
            <button class="btn btn-success btnAddDossier" type="button"><i class="glyphicon glyphicon-plus"></i> Ajouter un dossier</button>
            <?php endif;
        ?>
    </div>

</div>

<div class="row" style="margin-top : 20px; background-color: #FFF; padding: 15px 0px 15px 0px; margin:0px; border-bottom : 1px solid grey;">
    <div class="col-xs-12" style="padding-left: 30px; padding-right : 30px; min-height:">

        <div class="row">
            <div class="col-xs-12" style="border:1px solid grey; border-radius: 5px; min-height: 200px; padding:10px;">

                <div id="planning">

                    <table class="table table-bordered">
                        <thead>
                        <th style="width:10%;">Equipes</th>
                        <th>Jobs</th>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($equipes)):
                                foreach ($equipes as $e):
                                    echo '<tr><td>' . $e->getEquipeNom() . '</td><td class="jour" id="' . $e->getEquipeId() . '">';
                                    if (!empty($affectations[$e->getEquipeId()])):

                                        foreach ($affectations[$e->getEquipeId()] as $a):

                                            echo $a;

                                        endforeach;

                                    endif;

                                    echo '</td></tr>';
                                endforeach;
                            endif;
                            /* echo '<tr style="border-top: 2px dashed black;"><td class="sorties">Sorties</td><td class="sorties" id="sortiesJour"></td></tr>'; */
                            ?>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

    </div>
</div>
<?php include('forms.php'); ?>

