$(document).ready(function () {
//
//    $('#tableFactures').bootstrapTable({        
//        onClickRow: function (row) {
//            window.location.assign(chemin + 'facturation/ficheFacture/' + row.factureId)
//        }
//    });
    
    $('#tableFactures').on('click', 'tbody tr', function() {        
        window.location.assign(chemin + 'facturation/ficheFacture/' + $(this).attr('data-factureid') )
    });
    
    $('#sendFactureByEmail').on('dblclick', function () {
        $.post(chemin + 'facturation/sendFactureByEmail/', {factureId: $(this).attr('data-factureid')}, function (retour) {
            if (retour.type == 'success') {
                $.toaster({priority: 'success', title: '<strong><i class="far fa-thumbs-up"></i> OK</strong>', message: '<br>' + 'Facture envoyée'});
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });
    
    $('#formAddReglement').on('submit', function (e) {
        e.preventDefault();
        $('#btnAddReglementSubmit').hide();
        $('#loaderReglement').show();
        var donnees = $(this).serialize();
        $.post(chemin + 'facturation/addReglement', donnees, function (data) {
            switch (data.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $('#btnAddReglementSubmit').show();
                    $('#loaderReglement').hide();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    });
    
    function reglementRAZ() {        
        $('#addReglementId').val('');
        $('#addReglementMontant').val('');
        $('#addReglementMotif').val('');
        $('#addReglementMotif').hide();        
        $('#btnAddReglementSubmit').attr('class', 'btn btn-primary');
        $('#btnAddReglementSubmit').html('<i class="fas fa-plus"></i> Ajouter un réglement');        
    }
    
    $('#btnAddReglementCancel').on('click', function(){
        reglementRAZ()
        $('.ligneReglement').css('background-color', '#FFF');
    });
    
    $('.btnModReglement').on('click', function(){
        $(this).closest('tr').css('background-color', 'yellow');
        reglementRAZ();
        $('#addReglementMotif').closest('.form-group').show();              
        $('#addReglementDate').val($(this).closest('tr').attr('data-reglementdate'));              
        $('#btnAddReglementSubmit').html('<i class="fas fa-pencil-alt"></i> Modifier');
        $('#btnAddReglementSubmit').attr('class', 'btn btn-danger');
        $('#addReglementId').val( $(this).closest('tr').attr('data-reglementid'));
        $('#addReglementMontant').val( $(this).closest('tr').attr('data-reglementmontant') );
        $('#addReglementMode option[value="' + $(this).closest('tr').attr('data-reglementmode') + '"]').prop('selected', true);
        $('#addReglementType option[value="' + $(this).closest('tr').attr('data-reglementtype') + '"]').prop('selected', true);    
    });
    
    $('#formRechFactures').on('submit', function(e){
        e.preventDefault();
        window.location.assign(chemin + 'facturation/criteresListeFactures/' + $('#rechEtatFactures').val() + '/' + $('#rechDateDebutFactures').val() + '/' + $('#rechDateFinFactures').val() + '/' + $('#rechNumFacture').val() );
    });
    $('#formRechReglements').on('submit', function(e){
        e.preventDefault();
        window.location.assign(chemin + 'facturation/criteresListeReglements/' + $('#rechDateDebutReglements').val() + '/' + $('#rechDateFinReglements').val() );
    });
    $('.directFacture').on('click', function(e){
        e.stopPropagation();
    });
    
    /* Modifie le statut de facture envoyée dans la fiche facture en cochant 
     * ou non la case correspondante
     */
    $('#factureEnvoyee').on('change', function(){
        if( $(this).prop('checked') === true ){
            var etat = 1;            
        } else {
            var etat = 0;
        }
        $.post(chemin + 'facturation/setFactureEnvoyee', {factureId: $('#sendFactureByEmail').attr('data-factureid'), etat: etat}, function(retour){
            switch (retour.type) {
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="far fa-thumbs-up"></i> OK</strong>', message: '<br>' + 'C\'est noté !'});
                    break;
                case 'error':                    
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });
});