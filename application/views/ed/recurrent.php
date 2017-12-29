<div class="container">

    <div class="row" style="margin-top : 0px;">
        <div class="col-xs-12" style="text-align: center;">
            <h2>Les récurrents</h2>
            <button class="btn btn-success btnAddRecurrent" type="button" style="width:100%;"><i class="glyphicon glyphicon-plus"></i> Ajouter un récurrent</button>
            <br>
            <br>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <table class="table table-bordered" id="tableRecurrents" style="width:100%; font-size:12px;">
                <thead>
                    <th>Critère</th>
                    <th>Equipe</th>
                    <th>Commentaire</th>
                    <th style="width:40px;"></th>
                </thead>
                <tbody>

                    <?php
                    if( !empty($recurrents) ):
                    foreach ($recurrents as $r): ?>
                        <tr style="position:relative;" data-recurrentid="<?php echo $r->getRecurrentId(); ?>" >
                            <td><?php echo $r->getRecurrentCritere(); ?></td>
                            <td><?php echo $r->getRecurrentEquipe(); ?></td>
                            <td><?php echo $r->getRecurrentCommentaire(); ?></td>
                            <td class="recurrentActions" style="text-align: center; font-size:13px;">
                                <i class="glyphicon glyphicon-pencil btnModRecurrent" style="padding-right: 10px; cursor: pointer"></i>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    endif; ?>

                </tbody>
            </table>



        </div>
    </div>

</div>
<?php include('forms.php'); ?>