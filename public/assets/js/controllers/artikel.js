var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('artikel');
    buildTable();
    select2bind();
    inputFoto('input[name=gambar_artikel]', 'img#img-preview');

    $('textarea#article').summernote({
        height:300,
        placholder: "Tulis artikel disini..."
    });

});


document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.artikel.form')[0];

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
                    $('form#form_data')[0].reset();
                    wire.newForm().then(()=>{
                        $('#btn-hapus-gambar').hide();
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
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            {data:'created_at'},
            {data:'judul', render:(data, type, row) => {
                return `${data}<br/><small class="help-list-item">${row['published_at'] ?? '-'} s/d ${row['expired_at'] ?? '-'}</small>`
            }},
            {data:'product', render:(data, type, row, meta) => {
                return `${row['kode'] ?? ""} - ${data ?? ""}`
            }},
            {data:'first_name'},
            { data: 'id', render:(data, type, row, meta) => {
                return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
            }},
        ]
    });
}

function hapusgambar(id){
  Swal.fire({
      title:'Hapus gambar',
      text:'Gambar akan dihapus, apakah tetap akan dilanjutkan?',
      type:'question',
      showCancelButton:true,
      cancelButtonText:'Tidak jadi'
  }).then((e)=>{
      if(e.value === true){
        wire.hapusgambar(id).then((e)=>{
            $('img#img-preview').attr('src', '');
            $('#btn-hapus-gambar').hide();
        });
      }
  });
  return false;
}

function editdata(id){
    $('#modalform').modal('show');
    $('form#form_data')[0].reset();

    $('textarea[name=article]').summernote('code', '');

    wire.edit(id).then(()=>{
       let category_id = wire.get('article_category_id');
       let productid = wire.get('product_id');
       let kategori = wire.get('category');
       let produk = wire.get('kode') + ' - ' + wire.get('product');

       $('textarea[name=article]').summernote('code', wire.get('article'));
       $('select[name=article_category_id]').html(`<option value="${category_id}">${kategori}</option>`);
       $('select[name=article_category_id]').val(category_id).trigger('change');
       $('select[name=product_id]').html(`<option value="${productid}">${produk}</option>`);
       $('select[name=product_id]').val(productid).trigger('change');
       $('textarea[name=article]').val(wire.get('article'));
       let pathimage = wire.get('path_images');
        $('#btn-hapus-gambar').hide();
       if(pathimage !== '') {
           $('img#img-preview').attr('src', wire.get('path_images'));
           $('#btn-hapus-gambar').show();
       }
    });
}


async function save(){
    wire.set("article_category_id", $('select[name=article_category_id]').val(), false);
    wire.set("product_id", $('select[name=product_id]').val(), false);
    wire.set('article', $('textarea[name=article]').summernote('code'));
    let f = await getBase64File("input[name=gambar_artikel]");
    if(f !== null){
        wire.set('gambar_artikel', f);
    }
    wire.save().then(()=>{
        $('img#img-preview').attr('src', wire.get('path_images'));
    });
}
