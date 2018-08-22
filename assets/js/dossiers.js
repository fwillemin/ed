$(document).ready(function () {

    $('#tableDossiers').on('click', '.btnOpenDossier', function () {
        button = $(this);
        $.confirm({
            title: 'Réouvrir ce dossier ?',
            content: 'Le dossier sera réouvert et sera de nouveau dans la liste de planification',
            type: 'blue',
            theme: 'material',
            buttons: {
                confirm: {
                    btnClass: 'btn-green',
                    text: 'Ré-ouvrir',
                    action: function () {
                        $.post(chemin + 'dossiers/ouvertureDossier', {dossierId: button.closest('tr').attr('data-dossierid')}, function (retour) {
                            switch (retour.type) {
                                case 'error':
                                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                    break;
                                case 'success':
                                    button.closest('tr').remove();
                                    break;
                            }
                            delete button;
                        }, 'json');
                    }
                },
                cancel: {
                    btnClass: 'btn-red',
                    text: 'Annuler'
                }
            }
        });

    });
});