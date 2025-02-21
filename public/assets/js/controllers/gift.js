var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('gift');
    select2bind();
    buildTable();
    $('.money').mask('#,###,###,###,###,###,-', {reverse:true});

    $('select[name=user_id]').on('change', (e)=>{
        let v = $('select[name=user_id]').val();
        wire.set('user_id', v, false);
        gift();
    });
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.gift.form')[0];
    wire.on('refresh', function(){
        $('#jd-table').DataTable().ajax.reload();
    });

    wire.on('dapatharga', ()=>{
        let price = parseInt( wire.get('price') );
        $('input[name=price]').val(price).trigger('input');
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
            {data:'first_name', render:(data, type, row, meta) => {
                return `${data} ${row['last_name']}`;
            }},
            { data:'gift'},
            { data:'sent_at'},
            { data:'received_at'},
            { data: 'pengelola'},
            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

function gift() {
    wire.giftType().then(() => {
        
    });
}

function save(){
    wire.set('price', $('input[name=price]').cleanVal(),false );
    wire.save().then(()=>{

    });
}

function editdata(id){
    $('#modalform').modal('show');
    $('#form_data')[0].reset();
    wire.edit(id).then(()=>{
        let userid = wire.get('user_id');
        let nama = wire.get('member');
        let price = parseInt( wire.get('price') );

        $('select[name=user_id]').html(`<option value="${userid}">${nama}</option>`)
        $('select[name=user_id]').val(userid).trigger('change');
        $('input[name=price]').val(price).trigger('input');
    });
}
