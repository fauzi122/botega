var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('approval');
    buildTable();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.approval.form')[0];
    wire.on('refreshData', function(){
        $('#jd-table').DataTable().ajax.reload();
    });
});

function buildTable(){
    $('#jd-table-submit').DataTable({
        dom: 'Bfrtip',
        buttons:[

             'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e,dt,node, c)=>{
                    let url =  $('#jd-table-submit').data('urlaction');
                    showConfirmHapus('jd-table-submit', url , ()=>{
                        $('table#jd-table-submit').DataTable().ajax.reload();
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
            url:$('table#jd-table-submit').data('datasource'),
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
            {data:'status'},
            {data:'created_at'},
            {data:'member'},
            {data:'reason_user'},
            { data: 'id', sortable:false, width:'100px', render:(data, type, row, meta) => {
                    return `<button class="btn btn-sm btn-info" onclick="editdata(${data})"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });

    $('#jd-table-approved').DataTable({
        dom: 'Bfrtip',
        buttons:[

            'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e,dt,node, c)=>{
                    let url =  $('#jd-table-approved').data('urlaction');
                    showConfirmHapus('jd-table-approved', url , ()=>{
                        $('table#jd-table-approved').DataTable().ajax.reload();
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
            url:$('table#jd-table-approved').data('datasource'),
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
            {data:'status'},
            {data:'created_at'},
            {data:'member'},
            {data:'reason_user'},
            { data: 'id', sortable:false, width:'100px', render:(data, type, row, meta) => {
                    return `<button class="btn btn-sm btn-info" onclick="editdata(${data})"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });

    $('#jd-table-reject').DataTable({
        dom: 'Bfrtip',
        buttons:[

            'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e,dt,node, c)=>{
                    let url =  $('#jd-table-reject').data('urlaction');
                    showConfirmHapus('jd-table-reject', url , ()=>{
                        $('table#jd-table-reject').DataTable().ajax.reload();
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
            url:$('table#jd-table-reject').data('datasource'),
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
            {data:'status'},
            {data:'created_at'},
            {data:'member'},
            {data:'reason_user'},
            { data: 'id', sortable:false, width:'100px', render:(data, type, row, meta) => {
                    return `<button class="btn btn-sm btn-info" onclick="editdata(${data})"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

function editdata(id){
    $('#modalform').modal('show');
    wire.edit(id);
}

function save(){
    wire.save().then(()=>{

    });
}
