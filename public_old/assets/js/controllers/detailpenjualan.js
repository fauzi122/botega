var wire;
var lvlmemberid;
document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('penjualan');
    buildTable();
    select2bind();

    $('.money').mask('###,###,###,###,###,-',{reverse:true});

    if($('select[name=member_user_id]').length > 0) {
        $('select[name=member_user_id]').on('change', function () {
            let v = $(this).val();
            getInfoMember(v).then((e)=>{
                lvlmemberid = e.level_member_id;
                $('#jenis-level').html(e.level_name);
            });
        });
        $('select[name=member_user_id]').trigger('change');
    }else{
        let v = $('input[name=member_user_id]').val();
        getInfoMember(v).then((e)=>{
            lvlmemberid = e.level_member_id;
            $('#jenis-level').html(e.level_name);
        });
    }

    $('select[name=product_id]').on('change', ()=>{
        let n = $('select[name=product_id]').val();
        let url = baseurl() + `/admin/promo/info-produk/${n}/${lvlmemberid}`;
        $.get(url).done((e)=>{
            let harga = parseInt(`${e.price_promo}`, 10);
            $('input[name=sale_price]').val(harga).trigger('input')
        });
    });

    $('input[name=qty]').on('keyup', function(e){
        let qty = $('input[name=qty]').val();
        let harga = $('input[name=sale_price]').cleanVal();
        let total = qty * harga;
        $('input[name=subtotal]').val(total).trigger('input');
    });

});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.penjualan.form')[0];
    wire.on('refresh', function(){
        $('#jd-table').DataTable().ajax.reload();
        let total = wire.get('total');
        $('input[name=total').val(total).trigger('input');
        $('select[name=product_id]').val('').trigger('change');
        $('input[name=sale_price]').val('').trigger('input');
        $('input[name=qty]').val('').trigger('input');
    });
    wire.on('onEdit', function(){

    });
});

function readhitungTotal(){

}

function getInfoMember(idmember){
    let url = baseurl() + '/admin/member/info/' + idmember;

    return new Promise((resolve, reject)=>{
        $.get(url).done((e) => {
            if (!isObject(e)) {
                try {
                    let js = JSON.parse(e);
                    resolve(js);
                } catch (err) {
                    reject(err);
                }
            }else{
               resolve(e);
            }
        }).fail(()=>{
            reject('Gagal');
        });
    });

}

function formatTextProductSelect2(data){
    let url = baseurl() + '/admin/produk/image/' + data?.id + '.png'
    return `${data.text}<br/>
            <img src="${url}" style="width:80px; height: 80px; object-fit: cover; margin-right: 10px;" /><small style="vertical-align: top">${data?.category ?? ''} [${data?.harga ?? ''}]</small>`;
}

function urlProduk(){
    let url = $('meta[name=baseurl]').attr('content');
    return url + '/admin/promo/select2/?level_member_id=' + lvlmemberid;
}

function buildTable(){
    $('#jd-table').DataTable({
        dom: 'Bfrtip',
        buttons:[
            'csv', 'copy', 'excel', 'pdf', 'print',
            // {
            //     text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
            //     action: (e,dt,node, c)=>{
            //         let url =  $('#jd-table').data('urlaction');
            //         showConfirmHapus('jd-table', url , ()=>{
            //             $('table#jd-table').DataTable().ajax.reload();
            //         });
            //     },
            //     className: 'btn-danger'
            // },

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
            {'targets':3, 'className':'dt-right'},
            {'targets':4, 'className':'dt-right'},
            {'targets':5, 'className':'dt-right'}
        ],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    // return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                    return (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            { data:'kode' },
            { data:'name' },
            { data:'sale_price', width:'100px', render:(data, type, row, meta) => {
                    return formatUang(data);
                } },
            { data:'qty', width:'50px' },
            { data:'id', width:'120px', render:(data, type, row, meta) => {
                    let harga   = parseFloat(`${row['sale_price']}`);
                    let qty     = parseFloat(`${row['qty']}`);
                    let total = parseFloat(`${row['dpp_amount']}`); //harga * qty;

                    return formatUang(total);
                } },

        ]
    });
}

function storedata(){
    let mid = $('select[name=member_user_id]').val();
    let total = $('input[name=total]').cleanVal();
    let price = $('input[name=sale_price]').cleanVal();

    wire.set('member_user_id', mid, false);
    wire.set('total', total, false);
    wire.set('sale_price', price, false);
    wire.set('product_id', $('select[name=product_id]').val(), false);
    wire.set('qty', $('input[name=qty]').val(), false);
    wire.save().then(()=>{
        let e = wire.get('editform');
        if(e === 1){
            $("#pilih-member").html('');
        }
    });
}

function editdata(id){
    wire.edit(id).then(()=>{
        let mid = wire.get('member_user_id');
        let model = wire.get('lm');
        console.log(model);
        $('select[name=member_user_id]').html(`<option value="${mid}"></option>`);
    });
}
