var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('katalogproduk');
    buildGridView();

    inputFoto('input[name=filefoto]', 'img#img-preview', (u)=>{
        $('#image-preview').show();
    });
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.katalogproduk.form')[0];
    wire.on('refresh', function(){

        let isedit = wire.get('editform');
        if(!isedit){
            $('form#forminput')[0].reset();
            $('img#filefoto').attr('src','');
            $('#image-preview').hide();
        }
    });
});

function createFrameGridView(){
    let frame = $('div#gridview input[name=cari]');
    if(frame.length === 0){
        let header = `<div class="col-md-2"><label>Cari</label><input type="text" name="cari" class="form-control" placeholder="Cari..." /> </div>`;
        let tombol = `<div class="col-md-10 "> <button id='tambah' class="btn btn-sm btn-info btn-rounded align-text-bottom"><i class="label-icon bx bx-plus-circle"></i> Tambah</button>  </div>`
        $('div#gridview').html(`<div class="card"><div class="card-header">
            <div class="row">${header} ${tombol}</div> </div><div class="card-body" id="body-gridview"></div></div> </div>`);

        $('div#gridview .card-header input[name=cari]').on('keyup', function(e){
            clearTimeout(_timeOutSearch );
            _timeOutSearch = setTimeout(() => {
                buildGridView();
            }, 400);
        });

        $('div#gridview .card-header button#tambah').on('click', function(){
            $('#modalform').modal('show');
            $('#image-preview').hide();
            wire.newForm();
        });
    }
}

function createURLGridView(start=0){
    let cari = $('div#gridview .card-header input[name=cari]').val();
    var url = baseurl() + '/admin/katalog-produk/data-source/?';

    var searchQuery = '';
    let columns = ['nama_katalog' ];
    for(var idx in columns){
        let n = columns[idx];
        searchQuery += `&columns[${idx}][data]=` + encodeURI(n) + `&columns[${idx}][searchable]=true`;
    }

    return url + searchQuery + `&start=${start}&length=8&search[value]=`+ encodeURI(cari)
}

var _timeOutSearch;
function buildGridView(page){
    createFrameGridView();
    $('#image-preview').hide();
    $.get( createURLGridView(page) ).done(function(E){
        console.log(E);
        var body = '';
        let data = E['data'];
        for(var idx in data){
            let n = data[idx];

            let img = n['urlfoto'] === '' ? '' : `<img class="card-img-top img-fluid" src="${n['urlfoto']}" />`;
            let filekatalog = n['fileunduh'] === '' ? '' :  `<a href="${n['fileunduh']}" target="_blank"><i class="mdi mdi-download"></i> Unduh file</a>`;
            body += `<div class="col-md-3">
                        <div class="card">
                            ${img}
                            <div class="card-body">
                                <h3 class="card-title">${n['nama_katalog']}</h3>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                         <small class="text-muted"> ${filekatalog} ${ (n['created_at'] ?? n['updated_at']) ?? '' }</small>
                                    </div>
                                    <div class="col-md-6 ">
                                        <button data-toggle="tooltip" onclick="editdata('${n['id']}')" title="Edit" class="btn btn-sm btn-outline-info"><i class="mdi mdi-pencil"></i></button>
                                        <button data-toggle="tooltip" title="Hapus" onclick="hapusdata('${n['id']}')" class="btn btn-sm btn-outline-danger"><i class="mdi mdi-trash-can"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>`
        }
        let total = E['recordsTotal'];
        let limit = 8;
        let count = E['data'].length;
        let pageCount = Math.ceil(total / limit);
        maxPage = pageCount;
        var tombolpages = '<li class="page-item"><a class="page-link" href="#" onclick="toPrev()"><i class="mdi mdi-chevron-left"></i></a></li>';
        for(i=1; i<=pageCount; i++){
            let active = iPage == i ? 'active' : '';
            tombolpages += `<li class="page-item ${active}"><a href="#" class="page-link"  onclick="toPage(${i})">${i}</a></li>`;
        }
        tombolpages += '<li class="page-item"><a class="page-link" href="#" onclick="toNext()"><i class="mdi mdi-chevron-right"></i></a></li>';
        var html = `<div class="card">
                        <div class="card-body">
                            <div class="row">${body}</div>
                        </div>
                    </div>
        <div class="col-md-3">
            <div class="small">Menampilkan ${count} dari ${E['recordsTotal']} </div>
         </div>
        <div class="ml-2 col-md-7">
             <nav aria-label="Page navigation"><ul class="pagination">${tombolpages}</ul></nav>
        </div>`;
        $('div#body-gridview').html(html);
    });
}

var iPage = 1;
var maxPage = 1;
function toPrev(){
    iPage = iPage > 1 ? (iPage-1) : 1;
    toPage(iPage);
}

function toNext(){
    iPage = iPage < maxPage ? (iPage+1) : maxPage;
    toPage(iPage);
}

function toPage(page){
    iPage = page;
    let start = (page - 1) * 8;
    buildGridView(start);
}

function editdata(id){
    $('#modalform').modal('show');
    $('form#forminput')[0].reset();
    $('#image-preview').hide();

    wire.edit(id).then(()=>{
        let pathimage = wire.get('urlfoto');
        if(pathimage === null || pathimage === undefined || pathimage === '') {}else{
            $('img#img-preview').attr('src', pathimage);
            $('#image-preview').show();
        }
    });
}



function hapusgambar(){
    Swal.fire({
        title:'Hapus gambar',
        text:'Gambar slider akan dihapus, apakah tetap akan dilanjutkan?',
        type:'question',
        showCancelButton:true,
        cancelButtonText:'Tidak jadi'
    }).then((e)=>{
        if(e.value === true){
            let iseditmode = wire.get('editform');
            $('input[name=filefoto]').val(null);
            if(iseditmode === true) {
                wire.hapusgambar().then((e) => {
                    $('img#img-preview').attr('src', '');
                    $('#image-preview').hide();
                });
            }else{
                $('img#img-preview').attr('src','');
                $('#image-preview').hide();
            }
        }
    });
    return false;
}

function hapusdata(id){
    Swal.fire({
        type:'question',
        title:'Hapus gambar',
        text:'Gambar slider akan dihapus, mau dilanjutkan?',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText:'Hapus saja',
        cancelButtonText:'Tidak jadi'
    }).then((e)=>{
        if(e.value){
            wire.delete(id).then((e)=>{
                buildGridView();
                $('#image-preview').hide();
            });
        }
    })
}

async function store(){
    let f = await getBase64File("input[name=filefoto]");
    if(f !== null){ wire.set('filefoto', f); }


    let fk = await getBase64File("input[name=file_katalog]", 'application/pdf');
    if(fk !== null){ wire.set('fileunduh', fk); }

    wire.save().then(()=>{
        $('img#img-preview').attr('src', wire.get('urlfilefoto'));
        buildGridView();
        // $('#modalform').modal('hide');
    });

}
