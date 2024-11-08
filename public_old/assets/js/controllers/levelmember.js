var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('levelmember');
    buildTable();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.levelmember.form')[0];
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
            {targets:5, className:'dt-right'}
        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            {data:'level_name'},
            {data:'level', width: '80px'},
            {data:'kategori'},
            {data:'description'},
            {data:'limit_transaction', render:(data, type, row, meta) => {
                    return data <= -1 ? 'Tidak terbatas' : formatUang(data);
                }},
            { data: 'publish', width:'80px', render:(data, type, row, meta) => {
                    let checked = row['publish'] === 1 ?'checked' : '';
                    return `<input onchange="toggleaktif('${row['id']}')" type="checkbox" id="switch_${row['id']}" switch="none" ${checked} />
                            <label for="switch_${row['id']}" data-on-label="Ya" data-off-label="Tidak"></label>`;
                }},
            { data: 'id', sortable:false, width:'80px', render:(data, type, row, meta) => {
                    return `<button class="btn btn-sm btn-info" onclick="editdata(${data})"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

function toggleaktif(id){
    wire.toggleAktif(id);
}

function editdata(id){
    $('#modalform').modal('show');
    wire.edit(id);
}
