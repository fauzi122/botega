var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('lap.outstandfee');
    buildTable();
});

function buildTable(){
    $('#jd-table').DataTable({
        dom: 'Bfrtip',
        buttons:[

            'csv', 'copy', 'excel', 'pdf', 'print',

        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax:{
            url:$('table#jd-table').data('datasource'),
            method: 'GET'
        },

        order: [[2, 'desc']],
        columnDefs: [
            {'targets':5, 'className':'dt-right'}
        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    // return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                    return   (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            { data:'so_dt', render:(data, type, row, meta) => {
                return `${data}<br/><small>${row['nomor']}</small>`;
            }},
            { data:'trx_at' },
            { data:'member', render:(data, type, row, meta) => {
                    return (data ?? '') + " " +  (row['first_name'] ?? '' ) + ' ' + (row['last_name'] ?? '' );
                } },
            { data:'dtnotes' },
            { data:'os', render:function (data, type, row, meta){
                    return formatUang(data);
                } },


        ]
    });
}
