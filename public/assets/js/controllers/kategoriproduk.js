var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('kategoriproduk');
    buildTable();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.kategoriproduk.form')[0];
    wire.on('refresh', function(){
        $('#jd-table').DataTable().ajax.reload();
    });
});

function buildTable(){
    $('#jd-table').DataTable({
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

            { data: 'category', render:(data, type, row, meta) => {
                    return `${data ?? '-'}`;
                }},
            { data: 'descriptions'},
            { data:'id', render:(data, type, row, meta)=>{
                return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${data}')"><i class="mdi mdi-pencil"></i> Edit</button> `;
            }}
        ]
    });
}

function editdata(id){
    $('#modalform').modal('show');
    wire.edit(id);
}
