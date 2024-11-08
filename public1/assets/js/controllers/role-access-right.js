var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('hakakses');
    buildTable();
    select2bind();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.roleaccessright.form')[0];
    wire.on('refreshData', function(){
        $('#jd-table').DataTable().ajax.reload();
    });
});

function buildTable(){
    $('#jd-table').DataTable({
        dom: 'Bfrtlip',
        lengthMenu: [[10,20,50,100, -1], [10,20,50,100,"All"]],
        buttons:[

            {
                text:'<i class="mdi mdi-plus-circle"></i> Tambah',
                action: (e,dt,node, c)=>{
                    wire.newForm().then(()=>{
                        $('#modalform').modal('show');
                    })
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
            {data:'access_rights'},
            {data:'module'},
            {data:'grant', render:(data, type, row, meta) => {
                    let checked = data === 1 ? 'checked' : '';

                    return ` <input onchange="toggleGrant(${row['id']})" ${checked} type="checkbox" id="switch_${row['id']}" switch="none">
                            <label for="switch_${row['id']}" data-on-label="Ya" data-off-label="Tidak"></label>`;
            }},
            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button> `;
                }},

        ]
    });
}

function toggleGrant(id){
    wire.toggleGrant(id).then(()=>{

    });
}

function save(){
    wire.set('grant', $('input#grant').is(':checked'), false);
    wire.set('access_right_id', $('select[name=access_right_id]').val(), false);

    wire.save().then(()=>{

    });
}

function editdata(id){
    $('#modalform').modal('show');
    wire.edit(id).then(()=>{
        let access_right_id = wire.get('access_right_id');
        let access = wire.get('access');
        let module = wire.get('module');
        let grant = wire.get('grant');

        $('select[name=access_right_id]').html(`<option value="${access_right_id}">${access} (${module})</option>`);
        $('input#grant').prop('checked', grant === 1)

    });
}
