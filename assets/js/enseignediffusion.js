var chemin = 'http://192.168.0.1/ENSEIGNE/planning/index.php/';

function dateFromUnix(timeDate, type) {
    type = type || 'input';
    if (timeDate > 0) {
        var refactor = new Date(timeDate * 1000);
        var jour_refactor = refactor.getDate();
        if (jour_refactor < 10) {
            jour_refactor = '0' + jour_refactor;
        }
        var mois_refactor = refactor.getMonth() + 1;
        if (mois_refactor < 10) {
            mois_refactor = '0' + mois_refactor;
        }
        switch (type) {
            case 'input':
                return refactor.getFullYear() + '-' + mois_refactor + '-' + jour_refactor;

            case 'human':
                return jour_refactor + '/' + mois_refactor + '/' + refactor.getFullYear();

            case 'short':
                return jour_refactor + '/' + mois_refactor + '/' + String(refactor.getFullYear()).substr(2, 2);

        }
    } else {
        return '';
    }
}

/* Mis dans le js global car utilisé dans la partie client mais aussi dossier pour ajouter directement depuis un devis/commande */
function clientRAZ() {
    $('#addClientRaisonSociale').val('');
    $('#addClientNumTva').val('');
    $('#addClientExoneration').prop('checked', false);    
    $('#addClientAdresse1').val('');
    $('#addClientAdresse2').val('');
    $('#addClientCp').val('');
    $('#addClientVille').val('');
    $('#addClientPays').val('FRANCE');
    $('#addClientTelephone').val('');  
}

$(document).ready(function () {
    
    $('[data-toggle="popover"]').popover();

//    $('#formLogin').on('submit', function (e) {
//        e.preventDefault();
//        console.log('submit');
//
//        var donnees = $(this).serialize();
//        $.post(chemin + 'login/identification', donnees, function (retour) {
//            switch (retour.type) {
//                case 'error':
//                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
//                    break;
//                case 'success':
//                    window.location.assign(chemin + 'ed');
//                    break;
//            }
//        }, 'json');
//    });

    /* Initialisations */
    $('.tooltipped').tooltip({delay: 1500});

    $('.selectpicker').selectpicker({
        size: 8
    });


    /* Affichage de la session avec ESP+ESC */
    $(document).on('keydown', function (e) {
        if (e.keyCode == 32) {
            hold = true;
        }
    });
    $(document).on('keydown', function (e) {
        if (e.keyCode == 27 && hold === true) {
            $('#modalSession').modal('show');
        }
    });

    $('#btnAddClient').on('click', function () {
        clientRAZ();
        $('#modalAddClient h4').text('Ajouter un client');
        $('#btnAddClientSubmit').text('Ajouter');
        $('#modalAddClient').modal('show');
    });

    $('#formAddClient').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/manageClients', donnees, function (retour) {
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
    /* --------------------- */

});
