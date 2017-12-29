$(document).ready(function () {
    
    $('#tableDossiers').DataTable({
        "paging": false
    });
    
    $('.dateSelect, #dateSelectJour').datepicker({
        format: "dd/mm/yyyy",
        todayBtn: "linked",
        language: "fr",
        keyboardNavigation: false,
        forceParse: false,
        daysOfWeekDisabled: "0,6",
        daysOfWeekHighlighted: "0,6",
        calendarWeeks: true,
        todayHighlight: true
    });

    $('.organisable').sortable({

        start: function(event, ui) {
            startPosition = ui.item.index();
        },
        stop: function(event, ui) {
            stopPosition = ui.item.index();
            if( stopPosition != startPosition) {
                $.post(chemin + 'ed/changerPosition/', {affectId: ui.item.closest('.progHebdo').attr('data-affectid'), newPosition: ui.item.index()}, function(retour){
                    switch( retour.type ){
                    case 'success':
                        $.toaster({priority: 'success', title: '<strong><i class="glyphicon glyphicon-alert"></i> Cool</strong>', message: '<br>' + 'Cette affectation est bien repositionnée.'});
                        break;
                    case 'error':
                        $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                        break;
                    }
                }, 'json');
            } else {
                $.toaster({priority: 'info', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + 'Element inchangé'});
            }
        }
    });

    $('.dateSelect').on("changeDate", function() {       
       var dateSelect = new Date( parseInt($(this).datepicker('getFormattedDate').split('/')[2]), parseInt($(this).datepicker('getFormattedDate').split('/')[1])-1 , parseInt($(this).datepicker('getFormattedDate').split('/')[0]), 0, 0, 0);
       window.location.assign( chemin + 'ed/hebdomadaire/' + dateSelect.format('W') + '/' + $(this).datepicker('getFormattedDate').split('/')[2] );
    });
    $('#dateSelectJour').on("changeDate", function() {
       var dateSelect = new Date( parseInt($(this).datepicker('getFormattedDate').split('/')[2]), parseInt($(this).datepicker('getFormattedDate').split('/')[1])-1 , parseInt($(this).datepicker('getFormattedDate').split('/')[0]), 0, 0, 0);
       window.location.assign( chemin + 'ed/journalier/' + $(this).datepicker('getFormattedDate').split('/')[2] + '-' + $(this).datepicker('getFormattedDate').split('/')[1] + '-' + $(this).datepicker('getFormattedDate').split('/')[0] );
    });

    $('.jour').on('dblclick', function(){
        affectationRAZ();
        $('#addAffectDossierId option[value="' +  $(this).attr('data-dossier') + '"]' ).prop('selected',true);
        $('#addAffectDate').val( $(this).attr('data-date') );
        $('#addAffectEquipeId option[value="' + $(this).attr('data-equipe') + '"]' ).prop('selected',true);
        $('#modalAddAffectation .modal-title').text('Ajouter une affectation');
        $('#btnSubmitFormAddAffect').text('Ajouter');
        $('#modalAddAffectation').modal('show');
    });

    $('#btnShowCalendar').on('click',function(){
        $(this).hide();
        $('#dateSelectJour').show();
    });
    $('#planning').on('dblclick', '.progJourSortie',function(e){
        console.log( $(this).attr('data-dossierid'));
        e.stopPropagation();
        e.preventDefault();
        $.post( chemin + 'ed/nextStepSortie', {dossierId: $(this).attr('data-dossierid')}, function(retour){
            switch( retour.type){
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message });
                    break;
            }
        }, 'json');
    });

    /* Déclenche l'avancement d'une affectation */
    $('#planning').on('click', '.progJour, .progJourTermine, .btnHebdoNext',function(e){
        var elem = $(this).closest('div');
        e.stopPropagation();
        $.post(chemin + 'ed/nextStep', {affectationId: $(this).attr('data-affectid')}, function(retour){
            switch( retour.type ){
                case 'success':
                    //window.location.reload();
                    elem.css('background-color', retour.backgroundColor);
                    elem.css('color', retour.fontColor);
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

    /* DOSSIERS */
    function dossierRAZ() {
        $('#addDossierId').val('');
        $('#addDossierClient').val('');
        $('#addDossierDescriptif').val('');
        $('#addDossierDateSortie').val('');
        $('#addDossierPao').prop('checked', false);
        $('#addDossierFab').prop('checked', false);
        $('#addDossierPose').prop('checked', false);
        $('#btnDelDossier').hide();
        $('#formDossierAffectPao').empty();
        $('#formDossierAffectFab').empty();
        $('#formDossierAffectPose').empty();
    }

    $('.btnAddDossier').on('click', function () {
        dossierRAZ();
        $('#modalAddDossier .modal-title').text('Ajouter un dossier');
        $('#btnSubmitFormAddDossier').text('Ajouter ce dossier');
        $('#modalAddDossier').modal('show');
    });

    $('#formAddDossier').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'ed/addDossier', donnees, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>'.retour.message});
                    break;
            }
        }, 'json');
    });

    $('.btnModDossier').on('click', function () {
        dossierRAZ();
        $.post(chemin + 'ed/getDossier', {dossierId: $(this).closest('td').attr('id')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':

                    /* Remplissage du formulaire */
                    $('#addDossierId').val(retour.dossier.dossierId);
                    $('#addDossierClient').val(retour.dossier.dossierClient);
                    $('#addDossierDescriptif').val(retour.dossier.dossierDescriptif);
                    if (retour.dossier.dossierPao == 1) {
                        $('#addDossierPao').prop('checked', true);
                    }
                    if (retour.dossier.dossierFab == 1) {
                        $('#addDossierFab').prop('checked', true);
                    }
                    if (retour.dossier.dossierDateSortie !== 0) {
                        $('#addDossierDateSortie').val(dateFromUnix(retour.dossier.dossierDateSortie));
                    }
                    if (retour.dossier.dossierPose == 1) {
                        $('#addDossierPose').prop('checked', true);
                    }

                    /* Gestion des affectations liées */
                    for (i = 0; i < retour.pao.length; i++) {
                        $('#formDossierAffectPao').prepend(
                                '<div class="progHebdo" style="padding:3px;">' +
                                dateFromUnix(retour.pao[i].affectationDate, 'short') +
                                '<div class="pull-right">' + retour.pao[i].affectationEquipe + '</div>' +
                                '</div>'
                                );
                    }
                    for (i = 0; i < retour.fab.length; i++) {
                        $('#formDossierAffectFab').prepend(
                                '<div class="progHebdo" style="padding:3px;">' +
                                dateFromUnix(retour.fab[i].affectationDate, 'short') +
                                '<div class="pull-right">' + retour.fab[i].affectationEquipe + '</div>' +
                                '</div>'
                                );
                    }
                    for (i = 0; i < retour.pose.length; i++) {
                        $('#formDossierAffectPose').prepend(
                                '<div class="progHebdo" style="padding:3px;">' +
                                dateFromUnix(retour.pose[i].affectationDate, 'short') +
                                '<div class="pull-right">' + retour.pose[i].affectationEquipe + '</div>' +
                                '</div>'
                                );
                    }

                    $('#modalAddDossier .modal-title').text('Modifier le dossier "' + retour.dossier.dossierClient + '"');
                    $('#btnSubmitFormAddDossier').text('Modifier');
                    $('#btnDelDossier').show();
                    $('#modalAddDossier').modal('show');
                    break;
            }
        }, 'json');

    });

    $('.btnCloseDossier').on('click', function () {
        $.post(chemin + 'ed/clotureDossier', {dossierId: $(this).closest('td').attr('id')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });
    $('.btnOpenDossier').on('click', function () {
        $.post(chemin + 'ed/ouvertureDossier', {dossierId: $(this).closest('td').attr('id')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });
    $('#btnDelDossier').on('dblclick', function () {
        $.post(chemin + 'ed/delDossier', {dossierId: $('#addDossierId').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="glyphicon glyphicon-success"></i> Fait</strong>', message: '<br>Le dossier est supprimé'});
                    $('#tableDossiers tr[data-dossier="' + $('#addDossierId').val() + '"]').remove();
                    $('#modalAddDossier').modal('hide');
                    break;
            }
        }, 'json');
    });
    /* AFFECTATION */
    function affectationRAZ() {
        $('#addAffectId').val('');
        $('#addAffectDossierId').val('');
        $('#addAffectDossierId').closest('.form-group').show();
        $('#addAffectType').val('');
        $('#addAffectDate').val('');
        $('#addAffectNbJour option[value="1"]').prop('selected', true);
        $('#addAffectNbJour').show();
        $('#addAffectIntervenant').val('');
        $('#addAffectCommentaire').val('');
        $('#textClient').text('');
        $('#textDescriptif').text('');
        $('#btnDelAffect').hide();
    }
    $('.btnAddAffectation').on('click', function () {
        affectationRAZ();
        $('#addAffectDossierId option[value="' + $(this).closest('tr').attr('data-dossier') + '"]').prop('selected', true);
        $('#addAffectType').val($(this).attr('data-type'));
        $('#textClient').text($(this).closest('tr').attr('data-client'));
        $('#textDescriptif').text($(this).closest('tr').attr('data-descriptif'));
        $('#modalAddAffectation .modal-title').text('Ajouter une affectation');
        $('#btnSubmitFormAddAffect').text('Ajouter');
        $('#modalAddAffectation').modal('show');
    });

    $('#formAddAffectation').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'ed/addAffectation', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });

    $('#planning, #tableDossiers').on('click', '.btnModAffect', function () {
        affectationRAZ();
        $.post(chemin + 'ed/getAffectation', {affectationId: $(this).closest('.progHebdo').attr('data-affectid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    if (retour.affectation.affectationDossierClos == 0 && retour.affectation.affectationEtat != 3) {

                        $('#btnDelAffect').show();
                        $('#addAffectId').val(retour.affectation.affectationId);
                        $('#addAffectDossierId option[value="' + retour.affectation.affectationDossierId + '"]').prop('selected', true);
                        $('#addAffectDossierId').closest('.form-group').hide();
                        $('#addAffectEquipeId').val(retour.affectation.affectationEquipeId);
                        $('#addAffectType option[value="' + retour.affectation.affectationType + '"]').prop('selected', true);
                        $('#addAffectDate').val(dateFromUnix(retour.affectation.affectationDate));
                        $('#addAffectNbJour').hide();
                        $('#addAffectIntervenant').val(retour.affectation.affectationIntervenant);
                        $('#addAffectCommentaire').val(retour.affectation.affectationCommentaire);

                        $('#textClient').text($(this).closest('tr').attr('data-client'));
                        $('#textDescriptif').text($(this).closest('tr').attr('data-descriptif'));
                        $('#modalAddAffectation .modal-title').text('Modifier cette affectation');
                        $('#btnSubmitFormAddAffect').text('Modifier');
                        $('#modalAddAffectation').modal('show');

                    } else {
                        $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + 'Impossible de modifier une affectation terminée ou appartenant à un dossier clos.'});
                    }

                    break;
            }
        }, 'json');
    });

    $('#btnDelAffect').on('dblclick', function () {

        $.post(chemin + 'ed/delAffectation', {affectationId: $('#addAffectId').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="glyphicon glyphicon-success"></i> Fait</strong>', message: '<br>L\'affectation est supprimée'});
                    $('.progHebdo').each(function () {
                        if ($(this).attr('data-affectid') == $('#addAffectId').val()) {
                            $(this).hide();
                        }
                    });
                    $('#modalAddAffectation').modal('hide');
                    break;
            }
        }, 'json');
    });

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
        $.post(chemin + 'ed/addRecurrent', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });
    $('.btnModRecurrent').on('click', function () {
        recurrentRAZ();
        $.post(chemin + 'ed/getRecurrent', {recurrentId: $(this).closest('tr').attr('data-recurrentid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#btnDelRecurrent').show();
                    $('#addRecurrentId').val(retour.recurrent.recurrentId);
                    $('#addRecurrentEquipeId').val(retour.recurrent.recurrentEquipeId);
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
        $.post(chemin + 'ed/delRecurrent', {recurrentId: $('#addRecurrentId').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
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