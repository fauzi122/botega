var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('paymentmade');
    select2bind();
    buildTable();
    $('.money').mask('#,###,###,###,###,###,-', {reverse:true});

    $('select#no_so').on('change', function(E){
        var n = $('select#no_so').val();
        wire.pilihSO(n);
    });
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.paymentmade.form')[0];
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
                    $('select#no_so').val('').trigger('change');
                    $('select[name=member_user_id]').val('').trigger('change');
                    wire.newForm().then(()=>{

                    });
                },
                className: 'btn-success'
            },
            'csv', 'copy', 'excel', 'pdf', 'print',
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

        order: [[2, 'desc']],
        columnDefs: [
            {'targets':5, 'className':'dt-right'}
        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            { data:'no_so' },
            { data:'no_sj' },
            { data:'no_inv' },
            { data:'first_name', render:(data, type, row, meta) => {
                    return (data ?? '') + ' ' + (row['last_name'] ?? '' );
                } },
            { data:'nominal' , render:function (data, type, row, meta){
                    return formatUang(data);
                } },

            { data: 'id', render:(data, type, row, meta) => {
                    return `
                        <button class="btn btn-sm btn-rounded btn-info" onclick="editdata('${data}')"><i class="mdi mdi-pen"></i> Edit</button>
                    `;
                }},
        ]
    });
}

function editdata(id){

    $('#modalform').modal('show');
    $('#forminput')[0].reset();

    wire.edit(id).then(()=>{
        let name = wire.get('member');
        let memberuserid = wire.get('member_user_id');
        $('select[name=member_user_id]').html(`<option value="${memberuserid}">${name}</option>`);
        $('select[name=member_user_id]').val(memberuserid).trigger('change');

        let noso = wire.get('no_so');
        $('select[name=no_so]').html(`<option value="${noso}">${noso}</option>`);
        $('select[name=no_so]').val( noso ).trigger('change');
        $('select[name=no_sj]').val(wire.get('no_sj'));
        $('select[name=no_inv]').val(wire.get('no_inv'));
        $('input[name=nominal]').val(wire.get('nominal')).trigger('input');
    });
}

function store(){
    wire.set('member_user_id',   $('select#member_user_id').val(), false);

    wire.set('nominal', $('input[name=nominal]').cleanVal(),false );
    wire.store().then(()=>{

    });
}
