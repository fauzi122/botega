var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('produk');
    select2bind();
    buildTable();
    $('.money').mask('#.###.###.000.000,-', {reverse:true});
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.produk.form')[0];
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
                    wire.newForm().then(()=>{
                        $('select[name=category_id]').val('').trigger('change');
                        $('input[name=price]').val('').trigger('input');
                        $('input[name=cost_price]').val('').trigger('input');
                    });
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
        columnDefs: [
            {'targets':4, 'className':'dt-right'},
            {'targets':5, 'className':'dt-right'}
        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            { data: 'kode', width:'100px'},
            { data: 'name'},
            { data: 'category'},
            { data: 'price', width:'100px', render:(data, type, row, meta) => formatUang(data)},
            { data: 'qty', width: '40px'},
            { data: 'id', render:(data, type, row, meta) => {
                    let url = $('meta[name=baseurl]').attr('content') + '/admin/produk-image/' + row['id'];
                    return `
                        <button class="btn btn-sm btn-rounded btn-info" onclick="editdata('${data}')"><i class="mdi mdi-pen"></i> Edit</button>
                        <a class="btn btn-sm btn-rounded  waves-effect btn-light" href="${url}"><i class="mdi mdi-view-gallery"></i> Galeri</a>
                    `;
                }},

        ]
    });
}

function editdata(id){
    $('#modalform').modal('show');
    wire.edit(id).then(()=>{
        let cid = wire.get('category_id');
        let category = wire.get('category');
        $('select[name=category_id]').html(`<option value="${cid}">${category}</option>`);
        $('select[name=category_id]').val(cid).trigger('change');
        let price = wire.get('price');

        $('.money').mask('#.###.###.000.000,-', {reverse:true});
        $('input[name=price]').val( price).trigger('input');
        $('input[name=cost_price]').val( wire.get('cost_price') ).trigger('input');
    });
}

function store(){
    wire.set('category_id', $('select[name=category_id]').unmask().val() );
    let price = $('input[name=price]').unmask().val();

    wire.set('price', price);
    wire.set('cost_price', $('input[name=cost_price]').unmask().val() );
    wire.save().then(()=>{

    });
    $('.money').mask('#.###.###.000.000,-', {reverse:true});
}
