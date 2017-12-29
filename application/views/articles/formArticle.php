<!-- Gestion d'un article ------------------------------------------------------------------------------------------------------ -->
<div class="modal fade" id="modalAddArticle" tabindex="-1" role="dialog" aria-labelledby="Ajouter un article" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:white;"> </i></button>
                <h4 class="modal-title">
                    <?php
                    if (isset($article)):
                        echo 'Modifier l\'article : "<strong>' . $article->getArticleDesignation() . '</strong>"';
                    else:
                        echo 'Ajouter un nouvel article';
                    endif;
                    ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('articles/manageArticle/', array('class' => 'form-horizontal', 'id' => 'formAddArticle')); ?>
                <input type="hidden" name="addArticleId" id="addArticleId" value="<?= isset($article) ? $article->getArticleId() : ''; ?>" >
                <div class="form-group">
                    <label for="addArticleFamilleId" class="col-sm-2 control-label">Famille</label>
                    <div class="col-sm-9">
                        <select name="addArticleFamilleId" id="addArticleFamilleId" class="selectpicker" title="Selectionnez une famille" >
                            <option value="0" <?php if( isset($article) && !$article->getArticleFamilleId() ) echo 'selected'; ?>>NON CLASSE</option>
                            <?php
                            if (!empty($familles)):
                                foreach ($familles as $f):
                                    $etat = '';
                                    if (isset($article) && $article->getArticleFamilleId() == $f->getFamilleId()):
                                        $etat = 'selected';
                                    endif;
                                    echo '<option value="' . $f->getFamilleId() . '" ' . $etat . ' >' . $f->getFamilleNom() . '</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addArticleDesignation" class="col-sm-2 control-label">Designation</label>
                    <div class="col-sm-9">
                        <input type="text" id="addArticleDesignation" class="form-control" name="addArticleDesignation" value="<?= isset($article) ? $article->getArticleDesignation() : ''; ?>" placeholder="Désignation courte">
                    </div>
                </div>
                <div class="form-group">
                    <label for="addArticleDescription" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="addArticleDescription" id="addArticleDescription" rows="4" class="form-control"><?= isset($article) ? $article->getArticleDescription() : ''; ?></textarea>
                    </div>
                </div>                

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary pull-right" type="submit" id="btnAddArticleSubmit">
                    <?php
                    if (isset($article)):
                        echo '<i class="fa fa-pencil"></i> Modifier l\'article';
                    else:
                        echo '<i class="fa fa-plus"></i> Ajouter';
                    endif;
                    ?>
                </button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>