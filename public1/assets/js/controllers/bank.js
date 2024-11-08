var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('bank');
    buildTable();
    inputFoto('input[name=logo_path]', 'img#img-preview');
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.bank.form')[0];
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
                    $('#form_data')[0].reset();
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

        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            {data:'id', render:(data, type, row, meta) => {
                let dt = new Date();
                let tg = dt.toLocaleDateString() + dt.getHours() + dt.getMinutes();
                let url = baseurl() + '/admin/bank/' + data + '.png?r='+tg;
                return `<img src="${url}" style="width:90px; height:90px; object-fit: scale-down" />`;
            }},
            {data:'name', render:(data, type, row) => {
                    return `${row['kode_bank']} - ${data}`;
                }},
            {data:'akronim'},

            { data: 'id', sortable:false, width:'100px', render:(data, type, row, meta) => {
                    return `<button class="btn btn-sm btn-info" onclick="editdata(${data})"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

function editdata(id){
    $('#modalform').modal('show');
    $('#form_data')[0].reset();
    wire.edit(id).then(()=>{
        let dt = new Date();
        let tg = dt.toLocaleDateString() + dt.getHours() + dt.getMinutes();
        $('img#img-preview').attr('src', wire.get('logo_path') + "?r="+tg);
    });
}

async function save(){
    let file = await getBase64File('input[name=logo_path]');
    wire.set('file_base64', file);
    wire.save().then(()=>{

        $('img#img-preview').attr('src', '');
    });
}
