var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('penjualan');
    buildTable();
});


document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.penjualan.formtarikdata')[0];

    wire.on('refresh', function(){
        // $('#jd-table').DataTable().ajax.reload();
        $('#modalform').modal('hide');
    });


});

function buildTable(){
    $('#jd-table').DataTable({
        dom: 'Bfrtip',
        buttons:[

            // {
            //     text:'<i class="mdi mdi-refresh"></i> Sync',
            //     action: (e,dt,node, c)=>{
            //         let url = baseURL('/penjualan/resync');
            //         $('#jd-table_wrapper button[tabindex=0]').prop('disabled', 'disabled');
            //         $('#jd-table_processing').css('display','block');
            //         $.post(url, {
            //             '_token': csrf_token()
            //         }).done(function(e){
            //             $('table#jd-table').DataTable().ajax.reload();
            //             $('#jd-table_wrapper button[tabindex=0]').prop('disabled', '');
            //         }).error(function(e){
            //             $('#jd-table_wrapper button[tabindex=0]').prop('disabled', '');
            //         });
            //     },
            //     className: 'btn-success'
            // },
        'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="mdi mdi-refresh"></i> Refresh',
                action: (e,dt,node, c)=>{
                    $('#modalform').modal('show');
                },
                className: 'btn-info'
            },

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
            { data:'nomor_so' },
            { data:'trx_at' },
            { data:'member', render:(data, type, row, meta) => {
                return (data ?? '') + ' ' + (row['last_name'] ?? '' );
                } },
            { data:'status' },
            { data:'total', render:function (data, type, row, meta){
                    return formatUang(data);
                } },
            { data:'id', render:(data, type, row, meta) => {
                    let url = $('meta[name=baseurl]').attr('content') + '/admin/penjualan/detail/' + data;
                    return `<a href="${url}"><i class="mdi mdi-details"></i> Lihat Rincian</a>`;
                } },

        ]
    });
}

function proses(){
    wire.proses();
}
