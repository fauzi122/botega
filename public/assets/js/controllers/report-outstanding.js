var wire;
var wireDetail;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('lap.outstandfee');
    buildTable();
});

document.addEventListener('livewire:initialized', function() {
    wireDetail = Livewire.getByName('admin.fee.detailfee')[0];
});

function showDetailFee(fee_number_id){
    wireDetail.set('fee_number_id', fee_number_id);
    $('div#modalformDetail').modal('show');
}


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
            { data:'so_dt', width: '150px', render:(data, type, row, meta) => {

                return `${data}<br/><small class="badge-soft-info badge"><a href="#" onclick="showDetailFee(${row['fee_number_id']})">${row['nomor']}</a></small> `;
            }},
            { data:'trx_at', width: '150px' },
            { data:'first_name',   render:(data, type, row, meta) => {
                    return (data ?? '') + " " +  (row['first_name'] ?? '' ) + ' ' + (row['last_name'] ?? '' );
                } },
            { data:'dtnotes' },
            { data:'os', width:'100px', render:function (data, type, row, meta){
                    return formatUang(data) + `<br/>Terbayar : ${100-row['percentage']}%<br/>Belum Terbayar: ${row['percentage']}`;
                } },


        ]
    });
}
