var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('releasenotes');
    buildTable();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.release.form')[0];
    wire.on('refreshData', function(){
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
                    $('#btnhapus').hide();
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
            { data: 'kode'},
            { data: 'judul'},
            { data: 'deskripsi'},
            { data: 'tipe'},
            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

function save(){
    wire.save().then((_)={

    });
}
function editdata(id){
    $('form#form_data')[0].reset();
    $('#modalform').modal('show');
    wire.edit(id).then((e)=>{
        let tipe = wire.get('tipe');
        $('#tipe').val(tipe).change();
    });
}
