var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('kategori');
    buildTable();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.kategori.form')[0];
    wire.on('refreshData', function(){
        $('form#form_data')[0].reset();
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
            {data:'category'},
            {data:'publish', render:(data, type, row, meta) => {
                let chk = data === 1 ? 'checked' : '';
                return `<input onchange="toggleaktif('${row['id']}')" type="checkbox" id="switch_${row['id']}" switch="none" ${chk}>
                            <label for="switch_${row['id']}" data-on-label="Ya" data-off-label="Tidak"></label>`
            }},
            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

function toggleaktif(id){
    wire.togglePublish(id);
}

function save(){
    wire.set('publish', $('input[name=publish]').is(":checked"));
    wire.save().then(()=>{

    });
}

function editdata(id){
    $('#modalform').modal('show');

    $('form#form_data')[0].reset();
    wire.edit(id).then(()=>{
        let c = wire.get('publish') === 1;
        $('input[name=publish]').prop('checked', c);
    });
}
