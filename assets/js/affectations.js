$(document).ready(function () {


    function recurrentRAZ() {
        $('#addRecurrentId').val('');
        $('#addRecurrentCritere').val('');
        $('#addRecurrentCommentaire').val('');
        $('#btnDelRecurrent').hide();
    }
    $('.btnAddRecurrent').on('click', function () {
        recurrentRAZ();
        $('#modalAddRecurrent .modal-title').text('Ajouter une récurrence');
        $('#btnSubmitFormAddRecurrent').text('Ajouter');
        $('#modalAddRecurrent').modal('show');
    });
    $('#formAddRecurrent').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'affectations/addRecurrent', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });
    $('.btnModRecurrent').on('click', function () {
        recurrentRAZ();
        $.post(chemin + 'affectations/getRecurrent', {recurrentId: $(this).closest('tr').attr('data-recurrentid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#btnDelRecurrent').show();
                    $('#addRecurrentId').val(retour.recurrent.recurrentId);
                    $('#addRecurrentType').val(retour.recurrent.recurrentType);
                    $('#addRecurrentCritere').val(retour.recurrent.recurrentCritere);
                    $('#addRecurrentCommentaire').val(retour.recurrent.recurrentCommentaire);

                    $('#modalAddRecurrent .modal-title').text('Modifier cette récurrence');
                    $('#btnSubmitFormAddRecurrent').text('Modifier');
                    $('#modalAddRecurrent').modal('show');
                    break;
            }
        }, 'json');
    });
    $('#btnDelRecurrent').on('dblclick', function () {
        $.post(chemin + 'affectations/delRecurrent', {recurrentId: $('#addRecurrentId').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="glyphicon glyphicon-success"></i> Fait</strong>', message: '<br>L\'affectation est supprimée'});
                    $('tr').each(function () {
                        if ($(this).attr('data-recurrentid') == $('#addRecurrentId').val()) {
                            $(this).hide();
                        }
                    });
                    $('#modalAddRecurrent').modal('hide');
                    break;
            }
        }, 'json');
    });

});