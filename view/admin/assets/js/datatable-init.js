function datatable_init(table_name = '#datatable', haveButtons = 0) {
    var datatable_ptbr = {
        "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "<small>Mostrando de _START_ até _END_ de _TOTAL_ registros</small>",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "MENU resultados por página",
        "sLengthMenu": "",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "Nenhum registro encontrado",
        "sSearch": "Pesquisar",
        "oPaginate": {
            "sNext": "Próx.",
            "sPrevious": "Ant.",
            "sFirst": "Primeiro",
            "sLast": "Último"
        },
        "oAria": {
            "sSortAscending": ": Ordenar colunas de forma ascendente",
            "sSortDescending": ": Ordenar colunas de forma descendente"
        }
    };

    if ($.fn.DataTable.isDataTable(table_name)) {
        $(table_name).DataTable().destroy();
    }

    if($(table_name).length > 0) {
        
        setTimeout(function () {       
            if(haveButtons == 0)      {
                dtable = $(table_name).DataTable({
                    language: datatable_ptbr,
                    retrieve: true,
                    responsive: true,
                    dom: 'Bfrtip',
                    order: [],
                    "displayLength": 10
                });
            } else {
                dtable = $(table_name).DataTable({
                    language: datatable_ptbr,
                    retrieve: true,
                    responsive: true,
                    dom: 'Bfrtip',
                    order: [],
                    buttons: [
                        {
                            extend: 'excel',
                        },
                        {
                            extend: 'pdf',
                            messageBottom: null
                        },
                    ],
                    "displayLength": 10
                });
            }
           
        }, 500);
    }
}