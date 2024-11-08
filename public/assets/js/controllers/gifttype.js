var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('gifttype');
    buildTable();
    $('.money').mask('#,###,###,###,###,###,-',{reverse:true});
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.gifttype.form')[0];
    wire.on('refresh', function(){
        $('#jd-table').DataTable().ajax.reload();
        $('form#form_Data')[0].reset();
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
        columnDefs:[
            {targets:2, className:'dt-right'}
        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },

            { data: 'name'},
            { data: 'price', width:'120px', render:(data, type, row, meta)=>{
                return formatUang(data);
            }},
            { data: 'description'},
            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button> `;
                }},

        ]
    });
}

function save(){
    wire.set('price', $('input[name=price]').cleanVal(), false);
    wire.save().then(()=>{
        $('')
    });
}

function editdata(id){
    $('#modalform').modal('show');
    wire.edit(id).then(()=>{
        let price = parseInt( wire.get('price'), 10);
        $('input[name=price]').val(price).trigger('input');
    });
}
