var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('logaktifitas');
    buildTable();

});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.logs.form')[0];
    wire.on('refresh', function(){
        $('#jd-table').DataTable().ajax.reload();
    });


});

function buildTable(){
    $('#jd-table').DataTable({
        dom: 'Bfrtip',
        buttons:[
            'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e,dt,node, c)=>{
                    let url =  $('#jd-table').data('urlaction');
                    showConfirmHapus('jd-table', url , ()=>{
                        $('table#jd-table').DataTable().ajax.reload();
                    });
                },
                className: 'btn-danger'
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
        order: [[1, 'asc']],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            { data:'first_name', render:(data, type, row, meta) => {
                return data == null ? '-' :  `${data ?? ''} ${row['last_name'] ?? ''} (${row['id_no'] ?? ''})<br/><small>${row['user_type'] ?? ''}</small>`;
            }},
            { data:'admin_first_name', render:(data, type, row, meta) => {
                    return data == null ? '-' :  `${data ?? ''} ${row['admin_last_name'] ?? ''}`;
                }},
            { data: 'actions', render:(data, type, row) => {
                return `${data}<br/><small onclick="showModal(${row['id']})" class="badge badge-soft-primary">${row['payload']}</small>`
            }},
            { data: 'created_at'}


        ]
    });
}

function showModal(id){
    $('#modalform').modal('show');
    wire.loadData(id);
}
