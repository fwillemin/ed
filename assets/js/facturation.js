$(document).ready(function () {
//
//    $('#tableFactures').bootstrapTable({        
//        onClickRow: function (row) {
//            window.location.assign(chemin + 'facturation/ficheFacture/' + row.factureId)
//        }
//    });
    
    $('#tableFactures').on('click-row.bs.table', function(row) {
        console.log();
        window.location.assign(chemin + 'facturation/ficheFacture/' + row.factureId )
    });
    
});