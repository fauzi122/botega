var wire;

document.addEventListener('DOMContentLoaded', function(){
    buildTableur();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.user-rekening.form')[0];
    wire.on('refreshData', function(){
        $('#jd-tableur').DataTable().ajax.reload();
    });
});

function buildTableur(){
    $('#jd-tableur').DataTable({
        dom: 'Bfrtip',
        buttons:[

            {
                text:'<i class="mdi mdi-plus-circle"></i> Tambah',
                action: (e,dt,node, c)=>{
                    $('#modalform').modal('show');
                    wire.newForm();
                },
                className: 'btn-success'
            }, 'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e,dt,node, c)=>{
                    let url =  $('#jd-table').data('urlaction');
                    showConfirmHapus('jd-table', url , ()=>{
                        $('table#jd-tableur').DataTable().ajax.reload();
                    });
                },
                className: 'btn-danger'
            },

        ],
        initComplete: function (settings, json) {
            $("table#jd-tableur .dt-button").addClass("btn btn-sm btn-primary");
            $("table#jd-tableur .dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax:{
            url:$('table#jd-tableur').data('datasource'),
            method: 'GET'
        },
        order: [[1, 'asc']],
        columnDefs:[

        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            {data:'bank'},
            {data:'no_rekening'},

            { data: 'id', sortable:false, width:'100px', render:(data, type, row, meta) => {
                    return `<button class="btn btn-sm btn-info" onclick="editdataur(${data})"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

function editdataur(id){
    $('#modalformur').modal('show');
    wire.edit(id);
}
