$(document).ready(function () {

    $('.modAvoirQte').on('change', function(){
        if( parseFloat( $(this).val()) < 0 ){
            $(this).val( 0 );
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'La quantité doit être supérieure à zero'});
        } else if(parseFloat( $(this).val()) > $(this).attr('data-maxi')) {
            $(this).val( $(this).attr('data-maxi'));
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'La quantité doit être inférieure à la quantité facturée'});
        }       
        
        $.post(chemin + 'avoirs/modAvoirQte', {rowId: $(this).closest('tr').attr('data-rowid'), qte: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
        
    });    
    
    $('.modAvoirName').on('change', function(){
        $.post(chemin + 'avoirs/modAvoirName', {rowId: $(this).closest('tr').attr('data-rowid'), name: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });
    
    $('.modAvoirPrix').on('change', function(){        
        $.post(chemin + 'avoirs/modAvoirPrix', {rowId: $(this).closest('tr').attr('data-rowid'), prix: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });   
    $('#avoirCommentaire').on('change', function(){        
        $.post(chemin + 'avoirs/modAvoirCommentaire', {commentaire: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });   
    
    $('#btnAvoirEnregistrer').on('click', function(){
        $.post(chemin + 'avoirs/addAvoir', {}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.open(chemin + 'documents/editionAvoir/' + retour.avoirId);
                    window.location.assign(chemin + 'facturation/ficheFacture/' + $('#avoirFactureId').val());
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });  

    $('.btnSendAvoirEmail').on('dblclick', function () {
        $.post(chemin + 'factures/sendAvoirByEmail/', {avoirId: $(this).closest('tr').attr('data-avoirid')}, function (retour) {
            if (retour.type == 'success') {
                $.toaster({priority: 'success', title: '<strong><i class="far fa-thumbs-up"></i> OK</strong>', message: '<br>' + 'Avoir envoyé'});
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });
    

});

