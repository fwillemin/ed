$(document).ready(function () {

    $('#tableAffaires').bootstrapTable({
        idField: 'affaireId',
        url: chemin + 'affaires/getAllAffaires',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 100,
        contextMenu: '#context-menu',
        onClickRow: function (row) {
            window.location.assign(chemin + 'ventes/reloadAffaire/' + row.affaireId)
        },
        columns: [
            [
                {
                    field: 'affaireId',
                    title: 'ID',
                    sortable: true,
                    width: 40
                }, {
                    field: 'affaireDate',
                    title: 'Date',
                    sortable: true,
                    formatter: dateFormatter,
                    width: 80
                }, {
                    field: 'client',
                    title: 'Client',
                    sortable: true,
                    width: 200
                }, {
                    field: 'affaireObjet',
                    title: 'Objet',
                    sortable: false,
                    width: 350
                }, {
                    field: 'avancement',
                    title: 'Avancement',
                    sortable: true,
                    width: 150
                }, {
                    field: 'affaireTotalHT',
                    title: 'TotalHT',
                    align: 'right',
                    width: 80
                }, {
                    field: 'totalEnFacture',
                    title: 'Facturé',
                    align: 'right',
                    width: 80
                }
            ]
        ]
    });

    function dateFormatter(value) {
        return dateFromUnix(value, 'human');
    }

    function pleaseSave() {
        $('#btnSaveAffaire').attr('class', 'btn btn-danger blink');
    }

    $('#openDetails').on('click', function () {
        $('#details').show();
    });
    $('#closeDetails').on('click', function () {
        $('#details').hide();
    });

    /* Sélection du client */
    $('#btnClientSearch').on('click', function () {        
        $('#modalClientSearch').modal('show');
    });

    $('#clientSearch').on('keyup', function () {
        if ($(this).val()) {

            $(this).val($(this).val().toUpperCase());
            /* Recherche des clients correspondants */
            $.post(chemin + 'clients/clientSearch', {clientSearch: $(this).val()}, function (data) {

                switch (data.type) {
                    case 'success':
                        $('#clientSearchTable .clientSearchLigne').remove();
                        for (var i = 0; i < data.clients.length; i++) {
                            $('#clientSearchTable').append('<tr class="clientSearchLigne" id="' + data.clients[i].clientId + '"><td>' + data.clients[i].clientRaisonSociale + '</td><td>' + data.clients[i].clientAdresse1 + '</td><td>' + data.clients[i].clientVille + '</td><td>' + data.clients[i].clientTelephone + '</td></tr>');
                        }
                        break;
                    case 'error':
                        $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                }
            }, 'json');
        }
    });

    $('#clientSearchTable').on('click', '.clientSearchLigne', function () {
        $('#venteClientId').val($(this).attr('id'));
        /* recherche des informations pour remplir la div du client selectionné */
        clientSelect($(this).attr('id'));
    });

    function clientSelect(clientId) {
        $.post(chemin + 'ventes/venteAddClient', {clientId: clientId}, function (data) {
            switch (data.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    }

    $('.devenirPrincipal').on('click', function () {
        var elem = $(this);
        $.post(chemin + 'ventes/devenirClientPrincipal', {clientId: $(this).closest('tr').attr('data-clientid')}, function (retour) {
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

    $('.btnDelAffaireClient').on('dblclick', function () {
        var ligne = $(this).closest('tr');
        $.post(chemin + 'ventes/deleteAffaireClient', {clientId: $(this).closest('tr').attr('data-clientid')}, function (retour) {
            switch (retour.type) {
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="far fa-thumbs-up"></i> OK</strong>', message: '<br>Client supprimé.'});
                    ligne.remove();
                    pleaseSave();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

    /* Mise à jour des articles dans la vente */
    function majParametreArticle(rowid, param, valeur, ligneArticle, showReset) {

        $.post(chemin + 'ventes/autoSaveArticle', {rowId: rowid, param: param, valeur: valeur}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    ligneArticle.children('td').eq(0).find('.margeArticle').text('Ref Fst: ' + retour.margeArticle);
                    $('#margeAffaire').html('<i class="fas fa-trophy"></i> ' + retour.margeAffaire);
                    ligneArticle.children('td').eq(4).text(retour.newPrice);
                    if (showReset) {
                        ligneArticle.children('td').eq(2).find('.resetPrixDeVente').show();
                    }
                    majTotauxAffaire(retour.totaux);
                    $.toaster({priority: 'success', title: '<strong><i class="fa fa-hand-peace-o"></i> OK</strong>', message: '<br>Modification validée'});
                    pleaseSave();
            }
        }, 'json');

    }
    ;

    /**
     * Mise à jour des totaux de l'affaire dans le concepteur    
     */
    function majTotauxAffaire(totaux) {
        $('#totalAffaireHT').html(totaux.affaireTotalHT);
        $('#totalAffaireTVA').html(totaux.affaireTotalTVA);
        $('#totalAffaireTTC').html(totaux.affaireTotalTTC);
    }

    $('.autoSaveArticle').on('change', function () {
        var ligneArticle = $(this).closest('tr');
        if ($(this).attr('data-param') == 'prixVendu') {
            var showReset = true;
        } else {
            var showReset = false;
        }
        majParametreArticle($(this).closest('tr').attr('data-rowid'), $(this).attr('data-param'), $(this).val(), ligneArticle, showReset);
    });

    $('.resetPrixDeVente').on('dblclick', function () {
        var ligneArticle = $(this).closest('tr');
        majParametreArticle($(this).closest('tr').attr('data-rowid'), "prixVendu", $(this).attr('data-valeur'), ligneArticle, false);
        $(this).closest('tr').children('td').eq(2).children('.autoSaveArticle').val($(this).attr('data-valeur'));
        $(this).hide();
    });

    $('#affaireType').on('change', function () {
        $.post(chemin + 'ventes/modAffaireType', {affaireType: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    $.toaster({priority: 'success', title: '<strong><i class="fa fa-hand-peace-o"></i> OK</strong>', message: '<br>Modification validée'});
                    pleaseSave();
            }
        }, 'json');
    });

    $('#affaireObjet').on('change', function () {
        $.post(chemin + 'ventes/modAffaireObjet', {affaireObjet: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    $.toaster({priority: 'success', title: '<strong><i class="fa fa-hand-peace-o"></i> OK</strong>', message: '<br>Modification validée'});
                    pleaseSave();
            }
        }, 'json');
    });

    $('.showOptions').on('click', function () {
        rowid = $(this).closest('tr').attr('data-rowid');
        $('.ligneOption[data-rowid="' + rowid + '"]').toggle();
    });

    /**
     * Action à la selection d'un nouvel article dans l'interface de conception du dossier
     */
    $('#btnAddDossierArticle').on('click', function () {
        $.post(chemin + 'ventes/addVenteArticle', {articleId: $('#newArticle').val()}, function (retour) {
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

    $('#viderPanier').on('dblclick', function () {
        $.post(chemin + 'ventes/delPanier').done(
                function () {
                    window.location.reload();
                }
        );
    });

    $('.delArticle').on('dblclick', function () {
        var rowid = $(this).closest('tr').attr('data-rowid');
        $.post(chemin + 'ventes/delArticle', {rowId: rowid}).done(
                function () {
                    $('tr[data-rowid="' + rowid + '"]').remove();
                    pleaseSave();
                }
        )
    });
    $('.delComposant').on('dblclick', function () {
        var optionId = $(this).closest('tr').attr('data-optionid');
        $.post(chemin + 'ventes/delComposant', {optionId: optionId, rowId: $(this).closest('tr').attr('data-rowid')}).done(
                function () {
                    window.location.reload();
                }
        )
    });

    $('.btnAddComposant').on('click', function () {
        $('#addComposantRowId').val($(this).closest('tr').attr('data-rowid'));
        $('#modalAddComposantToArticle').modal('show');
    });

    /* Composition de l'article */
    $('#composantChoix').on('change', function () {
        $.post(chemin + 'articles/getOptions', {composantId: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#optionChoix option').remove();
                    for (i = 0; i < retour.options.length; i++) {
                        $('#optionChoix').append('<option value="' + retour.options[i].optionId + '">' + retour.options[i].optionNom + ' (' + retour.options[i].optionHT + '€)</option>');
                    }
                    $('#optionChoix').selectpicker('refresh');
                    break;
            }
        }, 'json');
    });

    $('#formAddComposantToArticle').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'ventes/addComposantToArticle', donnees, function (retour) {
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

    $('.modOptionQte').on('change', function () {
        ligneOption = $(this).closest('tr');
        $.post(chemin + 'ventes/changeOptionQte', {rowid: ligneOption.attr('data-rowid'), optionId: ligneOption.attr('data-optionid'), qte: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>Prix mis à jour'});
                    ligneArticle = $('tr[data-rowid="' + ligneOption.attr('data-rowid') + '"]');
                    ligneArticle.children('td').eq(2).find('input').val(retour.prixVendu);
                    console.log(retour.prixBase);
                    ligneArticle.children('td').eq(0).find('.margeArticle').text('Ref Fst: ' + retour.margeArticle);
                    ligneArticle.children('td').eq(2).find('.resetPrixDeVente').text(retour.prixBase + '€');
                    ligneArticle.children('td').eq(2).find('.resetPrixDeVente').attr('data-valeur', retour.prixBase);
                    ligneArticle.children('td').eq(4).text(retour.prixTotal);                    
                    $('#margeAffaire').html('<i class="fas fa-trophy"></i> ' + retour.margeAffaire);
                    majTotauxAffaire(retour.totaux);
                    pleaseSave();
                    break;
            }
        }, 'json');
    });

    $('#btnSaveAffaire').on('click', function () {
        $.post(chemin + 'affaires/addAffaire', {}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    window.location.assign(chemin + 'ventes/concepteur/' + retour.affaireId);
            }
        }, 'json');
    });

    $('#btnGenereDevis').on('click', function () {
        $.post(chemin + 'affaires/genererDevis', {affaireId: $('#btnSaveAffaire').attr('data-affaireid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    window.location.reload();
            }
        }, 'json');
    });

    $('#btnGenereCommande').on('click', function () {
        $.post(chemin + 'affaires/genererCommande', {affaireId: $('#btnSaveAffaire').attr('data-affaireid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    window.location.reload();
            }
        }, 'json');
    });

    function reglementRAZ() {

        $('#avertissementModReglement').hide();
        $('#addReglementSourceId').val('');
        $('#addReglementMontant').val('');
        $('#addReglementClientId option[value="0"]').prop('selected', true);
        $('#addReglementFactureId option[value="0"]').prop('selected', true);
        $('#addReglementFactureId option').prop('disabled', true);

    }

    $('#btnAddReglement').on('click', function () {
        reglementRAZ();
        $('#modalAddReglement .modal-title').text('Ajouter un réglement');
        $('#modalAddReglement').modal('show');
    });

    $('#addReglementClientId').on('change', function () {
        $('#addReglementFactureId option[value="0"]').prop('selected', true);
        $('.reglementChoixFacture').prop('disabled', true);
        $('.reglementChoixFacture').each(function () {
            if ($(this).attr('data-factureclientid') == $('#addReglementClientId').val()) {
                $(this).prop('disabled', false);
            }
        });
    });
    
    $('#formAddReglement').on('submit', function (e) {        
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'facturation/addReglement', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    window.location.reload();
            }
        }, 'json');

    });

    $('.modReglement').on('click', function () {
        $.post(chemin + 'facturation/getReglement', {reglementId: $(this).closest('tr').attr('data-reglementid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                default:
                    $('#modalAddReglement .modal-title').text('Modifier le réglement');
                    $('#avertissementModReglement').show();
                    $('#addReglementId').val(retour.reglement.reglementId);
                    $('#addReglementDate').val(dateFromUnix(retour.reglement.reglementDate, 'input'));
                    $('#addReglementMontant').val(retour.reglement.reglementMontant);
                    $('#addReglementClientId option[value="' + retour.reglement.reglementClientId + '"]').prop('selected', true);
                    $('#addReglementType option[value="' + retour.reglement.reglementType + '"]').prop('selected', true);
                    $('#addReglementMode option[value="' + retour.reglement.reglementMode + '"]').prop('selected', true);
                    $('.reglementChoixFacture').prop('disabled', true);
                    $('.reglementChoixFacture').each(function () {
                        if ($(this).attr('data-factureclientid') == retour.reglement.reglementClientId) {
                            $(this).prop('disabled', false);
                        }
                        if( $(this).val() == retour.reglement.reglementFactureId ){
                            $(this).prop('selected', true);
                        }
                    });
                    $('#modalAddReglement').modal('show');
            }
        }, 'json');
    });

    /**
     * Faturation
     */
    $('.calcQuota').on('change', function () {
        $(this).closest('tr').children('td').eq(5).find('input').val(
                Math.round($(this).attr('data-subtotal') * $(this).val()) / 100
                );
    });
    
    $('#addFactureClientId').on('change', function(){
        $('#addFactureEcheancePaiement option[value="' + $('#addFactureClientId option:selected').attr('data-echeance') + '"]').prop('selected', true);
    });
    
    $('#formAddFacture').on('submit', function (e) {
        e.preventDefault();

        var lignes = new Array();
        $('.calcQuota').each(function () {
            lignes.push([$(this).closest('tr').attr('data-rowid'), $(this).val()]);
        }
        ).promise().done(
                function () {                    
                    $.post(chemin + 'facturation/addFacture', {addFactureAffaireId: $('#addFactureAffaireId').val(), addFactureClientId: $('#addFactureClientId').val(), addFactureMode: $('#addFactureMode').val(), addFactureObjet: $('#addFactureObjet').val(), addFactureEcheancePaiement: $('#addFactureEcheancePaiement').val(), addFactureLignes: lignes}, function (data) {
                        switch (data.type) {
                            case 'success':
                                window.location.reload();
                                break;
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                                break;
                        }
                    }, 'json');
                }
        )
    });

    $('.btnGenereFacture').on('click', function () {
        $('#genereFacture').attr('data-factureid', $(this).closest('tr').attr('data-factureid'));
        $('#modalConfirmFacture').modal('show');
    });

    $('#genereFacture').on('click', function () {
        $.post(chemin + 'facturation/genereFacture', {factureId: $(this).attr('data-factureId')}, function (retour) {
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

    /* Planification */
    $('.modOptionPlanif').on('change', function(){
        console.log('...');
        var etat = 0;
        if( $(this).prop('checked') === true ){
            etat = 1;
        }
        $.post( chemin + 'ventes/modOptionPlanification', {option: $(this).attr('data-option'), valeur: etat}, function(retour){
            switch (retour.type) {
                case 'success':                    
                    $.toaster({priority: 'success', title: '<strong><i class="fa fa-hand-peace-o"></i> OK</strong>', message: '<br>' + 'Option modifiée'});
                    pleaseSave();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json' );
    });

    $('#btnDupliquerAffaire').confirm({
        title: 'Etes-vous sûr(e) ?',
        content: 'Vous êtes sur le point de dupliquer une affaire.',
        type: 'blue',
        theme: 'material',
        buttons: {            
            confirm: {
                btnClass: 'btn-green',
                text: 'Dupliquer',
                action : function () {
                    window.location.assign(chemin + 'affaires/dupliquerAffaire/' + $('#btnDupliquerAffaire').attr('data-affaireid'));
                }
                
            },
            cancel: {
                btnClass: 'btn-red',
                text: 'Annuler'                
            }
        }
    });
    $('#btnCloturerAffaire').confirm({
        title: 'Clôturer ?',
        content: 'Voulez-vous clôturer cette affaire ?<br>Elle sera comptée comme :<br><strong>Terminée</strong> si une commande a été générée<br><strong>Perdue</strong> si seul un devis a été crée.',
        type: 'blue',
        theme: 'material',
        buttons: {            
            confirm: {
                btnClass: 'btn-green',
                text: 'Clôturer',
                action : function () {
                    window.location.assign(chemin + 'affaires/cloturerAffaire/' + $('#btnDupliquerAffaire').attr('data-affaireid'));
                }
                
            },
            cancel: {
                btnClass: 'btn-red',
                text: 'Annuler'                
            }
        }
    });
    $('#btnReouvrirAffaire').confirm({
        title: 'Reprendre cette affaire ?',
        content: 'Voulez-vous reprendre cette affaire comme "NON CLOTUREE" ?',
        type: 'blue',
        theme: 'material',
        buttons: {            
            confirm: {
                btnClass: 'btn-green',
                text: 'Reprendre',
                action : function () {
                    window.location.assign(chemin + 'affaires/cloturerAffaire/' + $('#btnDupliquerAffaire').attr('data-affaireid'));
                }
                
            },
            cancel: {
                btnClass: 'btn-red',
                text: 'Annuler'                
            }
        }
    });
});