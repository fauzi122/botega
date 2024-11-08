var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('redeempoint');
    select2bind();
    buildTable();

    $('select[name=reward_id]').on('change', (e)=>{
        let v = $('select[name=reward_id]').val();
        wire.showPoint(v);
        console.log(v);
    });
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.redeempoint.form')[0];
    wire.on('refreshData', function(){
        $('#jd-table').DataTable().ajax.reload();
        $('#jd-table-proses').DataTable().ajax.reload();
        $('#jd-table-acc').DataTable().ajax.reload();
        $('#jd-table-tolak').DataTable().ajax.reload();
    });
});

function buildTable(){
    var mapstatus = [ 'Baru diajukan', 'Proses', 'Disetujui', 'Ditolak' ];
    var mapBadgestatus = [ 'badge-soft-primary', 'badge-soft-warning', 'badge-soft-success', 'badge-soft-danger' ];

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
            {data:'member', render:(data, type, row, meta) => {
                    return `${data}<br/><small>${row['points'] ?? '0'} pts</small>`
                }
            },
            {data:'reward'},
            {data:'point'},
            {data:'created_at', render:(data, type, row) => {
                let status = mapstatus[ row['status']];
                let jenis =  mapBadgestatus[ row['status'] ];
                return `${data}<br/><span class="badge  rounded-pill ${jenis}">${status}</span>`;
            }},
            {data:'approved_at'},

            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });

    $('#jd-table-proses').DataTable({
        dom: 'Bfrtip',
        buttons:[
            'csv', 'copy', 'excel', 'pdf', 'print',

        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax:{
            url:$('table#jd-table-proses').data('datasource'),
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
            {data:'member', render:(data, type, row, meta) => {
                    return `${data}<br/><small>${row['points'] ?? '0'} pts</small>`
                }
            },
            {data:'reward'},
            {data:'point'},
            {data:'created_at', render:(data, type, row) => {
                    let status = mapstatus[ row['status']];
                    let jenis =  mapBadgestatus[ row['status'] ];
                    return `${data}<br/><span class="badge  rounded-pill ${jenis}">${status}</span>`;
                }},
            {data:'approved_at'},

            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });

    $('#jd-table-acc').DataTable({
        dom: 'Bfrtip',
        buttons:[
           'csv', 'copy', 'excel', 'pdf', 'print',
        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax:{
            url:$('table#jd-table-acc').data('datasource'),
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
            {data:'member', render:(data, type, row, meta) => {
                    return `${data}<br/><small>${row['points'] ?? '0'} pts</small>`
                }
            },
            {data:'reward'},
            {data:'point'},
            {data:'created_at', render:(data, type, row) => {
                    let status = mapstatus[ row['status']];
                    let jenis =  mapBadgestatus[ row['status'] ];
                    return `${data}<br/><span class="badge  rounded-pill ${jenis}">${status}</span>`;
                }},
            {data:'approved_at'},

            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });

    $('#jd-table-tolak').DataTable({
        dom: 'Bfrtip',
        buttons:[
            'csv', 'copy', 'excel', 'pdf', 'print',
        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax:{
            url:$('table#jd-table-tolak').data('datasource'),
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
            {data:'member', render:(data, type, row, meta) => {
                    return `${data}<br/><small>${row['points'] ?? '0'} pts</small>`
                }
            },
            {data:'reward'},
            {data:'point'},
            {data:'created_at', render:(data, type, row) => {
                    let status = mapstatus[ row['status']];
                    let jenis =  mapBadgestatus[ row['status'] ];
                    return `${data}<br/><span class="badge  rounded-pill ${jenis}">${status}</span>`;
                }},
            {data:'approved_at'},

            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });

}

function save(){
    wire.set('user_id', $('select[name=user_id]').val(), false);
    wire.set('reward_id', $('select[name=reward_id]').val(), false);
    wire.save().then(()=>{
        let pesan = wire.get('pesan');
        if(pesan !== '' ){
            Swal.fire({
                title: 'Konfirmasi',
                text: pesan,
                type: 'warning',
                showCancelButton:true
            }).then((e)=>{
                if(e.value){
                    wire.set('confirm', true, false);
                    wire.save();
                }
            });
        }
    });
}

function editdata(id){
    $('#modalform').modal('show');
    $('form#form_data')[0].reset();

    wire.edit(id).then(()=>{
        let userid = wire.get('user_id');
        let member = wire.get('member')
        let reward_id = wire.get('reward_id');
        let reward = wire.get('reward');

        $('select[name=user_id]').html(`<option value="${userid}">${member}</option>`);
        $('select[name=user_id]').val(userid).trigger('change');
        $('select[name=reward_id]').html(`<option value="${reward_id}">${reward}</option>`);
        $('select[name=reward_id]').val(reward_id).trigger('change');

    });
}
