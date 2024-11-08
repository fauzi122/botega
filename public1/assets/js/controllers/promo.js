
document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('promo');
    buildTable();
});

function buildTable(){
    $('#jd-table').DataTable({
        dom: 'Bfrtip',
        buttons:[

            {
                text:'<i class="mdi mdi-plus-circle"></i> Tambah',
                action: (e,dt,node, c)=>{
                    $('#modalform').modal('show');
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
            {targets:4, className:'dt-right'}
        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },

            { data: 'kode', render:(data, type, row, meta) => {
                    return `${data ?? '-'}`;
                }},
            { data: 'id', render:(d, t, row) => {
                    let url = $('meta[name=baseurl]').attr('content') + '/admin/produk/image/' + row['product_id'];
                    return `<img src="${url}" style="width:100px" />`;
                }},
            { data: 'product'},
            { data: 'price', render:(data, type) => {
                return formatUang(data);
                }},
            { data: 'expired_at'},
            { data:'id', render:(d, t, row)=>{
                return ` <button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')">Edit</button>`;
                }}

        ]
    });
}

function editdata(id){
    $('#modalform').modal('show');

    let wire = Livewire.getByName('admin.promo.form')[0];
    wire.edit(id).then(()=>{
        let pid = wire.get('product_id');
        let price = Math.ceil(wire.get('price'));
        let kode = wire.get('kode');
        let product = wire.get('product');
        let expired_at = wire.get('expired_at');
        $('select[name=product_id]').html(`<option value="${pid}">${kode} - ${product}</option>`);
        $('select[name=product_id]').val(pid).trigger('change');
        $('input[name=price]').val(price).trigger('input');
        $('input[name=expired_at]').val(expired_at).trigger('input');
    });
}
