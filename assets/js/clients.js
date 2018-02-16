$(document).ready(function () {

    $('#tableClients').bootstrapTable({
        idField: 'clientId',
        url: chemin + 'clients/getAllClients',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 25,
        contextMenu: '#context-menu',
        onClickRow: function (row) {
            window.location.assign(chemin + 'clients/ficheClient/' + row.clientId)
        },
        columns: [
            [
                {
                    field: 'clientRaisonSociale',
                    title: 'Raison sociale',
                    sortable: true,
                    width: 150
                }, {
                    field: 'clientCp',
                    title: 'CP',
                    sortable: true,
                    width: 40
                }, {
                    field: 'clientVille',
                    title: 'Ville',
                    width: 150
                }, {
                    field: 'clientTelephone',
                    title: 'Téléphone',
                    width: 120
                }, {
                    field: 'clientContacts',
                    title: 'Contacts',
                    formatter: contactsFormatter,
                    width: 120
                }
            ]
        ]
    });

    function contactsFormatter(value) {        
        var text = '<table style="font-size: 10px;" class="table table-striped table-condensed">';
        for (i = 0; i < value.length; i++) {            
            text += '<tr><td>' + value[i].contactNom + ' ' + value[i].contactPrenom + '</td><td style="text-align: right;"> ' + value[i].contactTelephone + '</td><td style="text-align: right;">' + value[i].contactPortable + '</td></tr>';
        }
        text += '</table>';
        return text;
    }

    /* Formatage des cellules de la table */

    /* ----------------------------------- */
    /* Une partie est dans le js global */

    $('#btnModClient').on('click', function () {
        clientRAZ();
        $.post(chemin + 'clients/getClient', {clientId: $(this).attr('cible')}, function (data) {
            if (data.type == 'success') {
                $('#addClientId').val(data.client.clientId);
                $('#addClientRaisonSociale').val(data.client.clientRaisonSociale);
                $('#addClientAdresse1').val(data.client.clientAdresse1);
                $('#addClientAdresse2').val(data.client.clientAdresse2);
                $('#addClientCp').val(data.client.clientCp);
                $('#addClientVille').val(data.client.clientVille);
                $('#addClientPays').val(data.client.clientPays);
                $('#addClientTelephone').val(data.client.clientTelephone);
                $('#addClientNumTva').val(data.client.clientNumTva);
                $('#addClientEcheancePaiement option[value="' + data.client.clientEcheancePaiement + '"]').prop('selected', true);
                if (data.client.clientExoneration == '1') {
                    $('#addClientExoneration').prop('checked', true);
                } else {
                    $('#addClientExoneration').prop('checked', false);
                }
                $('#modalAddClient h4').text('Modfier le client ' + data.client.clientRaisonSociale);

                $('#btnAddClientSubmit').text('Modifier');
                $('#modalAddClient').modal('show');
            }
        }, 'json');
    });

    $('#btnDelClient').on('dblclick', function () {
        var client = $(this).attr('data-clientid');
        $.post(chemin + 'clients/delClient', {clientId: client}, function (data) {
            switch (data.type) {
                case 'success':
                    window.location.assign(chemin + 'clients');
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    });

    $('#btnAddClient').on('click', function () {
        $('#modalAddClient').modal('show');
    });

    function contactRAZ() {
        $('#addContactId').val('');
        $('#addContactNom').val('');
        $('#addContactPrenom').val('');
        $('#addContactFunction').val('');
        $('#addContactEmail').val('');
        $('#addContactTelephone').val('');
        $('#addContactPortable').val('');
        $('#modalAddContact h4').text('Ajouter un contact');
        $('#btnAddContactSubmit').text('Ajouter');
    }

    $('#btnAddContact').on('click', function () {
        contactRAZ();
        $('#modalAddContact').modal('show');
    });

    $('.btnModContact').on('click', function () {
        contactRAZ();
        $.post(chemin + 'clients/getContact', {contactId: $(this).closest('tr').attr('data-contactid')}, function (data) {
            switch (data.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
                default:
                    $('#addContactId').val(data.contact.contactId);
                    $('#addContactCivilite option[value="' + data.contact.contactCivilite + '"]').prop('selected', true);
                    $('#addContactNom').val(data.contact.contactNom);
                    $('#addContactPrenom').val(data.contact.contactPrenom);
                    $('#addContactFonction').val(data.contact.contactFonction);
                    $('#addContactTelephone').val(data.contact.contactTelephone);
                    $('#addContactPortable').val(data.contact.contactPortable);
                    $('#addContactEmail').val(data.contact.contactEmail);

                    $('#modalAddContact h4').text('Modfier le contact ' + data.contact.contactNom);

                    $('#btnAddContactSubmit').text('Modifier');
                    $('#modalAddContact').modal('show');
            }

        }, 'json');
    });

    $('#formAddContact').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/manageContacts', donnees, function (retour) {
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

    $('.btnDelContact').on('dblclick', function () {
        contactRAZ();
        $.post(chemin + 'clients/delContact', {contactId: $(this).closest('tr').attr('data-contactid')}, function (data) {
            switch (data.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
                default:
                    window.location.reload();
            }

        }, 'json');
    });

});

