var wire;

document.addEventListener("DOMContentLoaded", function () {
    setSelectedMenu("produk");
    buildGridView();

    inputFoto("input[name=filefoto]", "img#filefoto");
});

document.addEventListener("livewire:initialized", function () {
    wire = Livewire.getByName("admin.produkimage.form")[0];
    wire.on("refresh", function () {
        $("#jd-table").DataTable().ajax.reload();
        let isedit = wire.get("editform");
        if (!isedit) {
            $("form#forminput")[0].reset();
            $("img#filefoto").attr("src", "");
        }
    });
});

function createFrameGridView() {
    let frame = $("div#gridview input[name=cari]");
    if (frame.length === 0) {
        let header = `<div class="col-md-2"><label>Cari</label><input type="text" name="cari" class="form-control" placeholder="Cari..." /> </div>`;
        let tombol = `<div class="col-md-10 "> <button id='tambah' class="btn btn-sm btn-info btn-rounded align-text-bottom"><i class="label-icon bx bx-plus-circle"></i> Tambah</button>  </div>`;
        $("div#gridview").html(`<div class="card"><div class="card-header">
            <div class="row">${header} ${tombol}</div> </div><div class="card-body" id="body-gridview"></div></div> </div>`);
        $("div#gridview .card-header input[name=cari]").on(
            "keyup",
            function (e) {
                clearTimeout(_timeOutSearch);
                _timeOutSearch = setTimeout(() => {
                    buildGridView();
                }, 400);
            }
        );

        $("div#gridview .card-header button#tambah").on("click", function () {
            $("#modalform").modal("show");
            wire.newForm();
        });
    }
}

function createURLGridView(start = 0) {
    let produkid = $("div#gridview").data("produkid");
    let cari = $("div#gridview .card-header input[name=cari]").val();
    var baseurl =
        $("meta[name=baseurl]").attr("content") +
        "/admin/produk-image/data-source/?id=" +
        produkid;

    var searchQuery = "";
    let columns = ["name", "description"];
    for (var idx in columns) {
        let n = columns[idx];
        searchQuery +=
            `&columns[${idx}][data]=` +
            encodeURI(n) +
            `&columns[${idx}][searchable]=true`;
    }

    return (
        baseurl +
        searchQuery +
        `&start=${start}&length=8&search[value]=` +
        encodeURI(cari)
    );
}

var _timeOutSearch;
function buildGridView(page) {
    createFrameGridView();
    $.get(createURLGridView(page)).done(function (E) {
        console.log(E);
        var body = "";
        let data = E["data"];
        for (var idx in data) {
            let n = data[idx];
            let isprime =
                n["is_primary"] === 1
                    ? '<i class="mdi mdi-star-circle font-size-15 text-warning" title="Gambar utama"></i>'
                    : "";
            body += `<div class="col-md-3">
                        <div class="card">
                            <img class="card-img-top img-fluid" src="${n["path_file"]}" />
                            <div class="card-body">
                                <h3 class="card-title">${n["name"]}</h3>
                                <p class="card-text">${n["description"]}</p>
                                <div class="row">
                                    <div class="col-md-8">
                                         <small class="text-muted">${isprime} ${n["created_at"]}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <button data-toggle="tooltip" onclick="editdata('${n["id"]}')" title="Edit" class="btn btn-sm btn-outline-info"><i class="mdi mdi-pencil"></i></button>
                                        <button data-toggle="tooltip" title="Hapus" onclick="hapusdata('${n["id"]}')" class="btn btn-sm btn-outline-danger"><i class="mdi mdi-trash-can"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>`;
        }
        let total = E["recordsTotal"];
        let limit = 8; // E['input']['length'];
        let count = E["data"].length;
        let pageCount = Math.ceil(total / limit);
        maxPage = pageCount;
        var tombolpages =
            '<li class="page-item"><a class="page-link" href="#" onclick="toPrev()"><i class="mdi mdi-chevron-left"></i></a></li>';
        for (i = 1; i <= pageCount; i++) {
            let active = iPage == i ? "active" : "";
            tombolpages += `<li class="page-item ${active}"><a href="#" class="page-link"  onclick="toPage(${i})">${i}</a></li>`;
        }
        tombolpages +=
            '<li class="page-item"><a class="page-link" href="#" onclick="toNext()"><i class="mdi mdi-chevron-right"></i></a></li>';
        var html = `<div class="card">
                        <div class="card-body">
                            <div class="row">${body}</div>
                        </div>
                    </div>
        <div class="col-md-3">
            <div class="small">Menampilkan ${count} dari ${E["recordsTotal"]} </div>
         </div>
        <div class="ml-2 col-md-7">
             <nav aria-label="Page navigation"><ul class="pagination">${tombolpages}</ul></nav>
        </div>`;
        $("div#body-gridview").html(html);
    });
}

var iPage = 1;
var maxPage = 1;
function toPrev() {
    iPage = iPage > 1 ? iPage - 1 : 1;
    toPage(iPage);
}

function toNext() {
    iPage = iPage < maxPage ? iPage + 1 : maxPage;
    toPage(iPage);
}

function toPage(page) {
    iPage = page;
    let start = (page - 1) * 8;
    buildGridView(start);
}

function editdata(id) {
    $("#modalform").modal("show");
    wire.edit(id).then(() => {
        let cid = wire.get("category_id");
        let category = wire.get("category");
        $("select[name=category_id]").html(
            `<option value="${cid}">${category}</option>`
        );
        $("select[name=category_id]").val(cid).trigger("change");
        let price = wire.get("price");
        $("img#filefoto").attr("src", wire.get("urlfilefoto"));
        $("input[name=price]").val(price).trigger("input");
        $("input[name=cost_price]")
            .val(wire.get("cost_price"))
            .trigger("input");

        let n = wire.get("is_primary") == 1 ? true : false;
        $("input[name=is_primary]").prop("checked", n);
    });
}

function hapusdata(id) {
    Swal.fire({
        type: "question",
        title: "Hapus gambar",
        text: "Gambar produk akan dihapus, mau dilanjutkan?",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Hapus saja",
        cancelButtonText: "Tidak jadi",
    }).then((e) => {
        if (e.value) {
            wire.delete(id).then((e) => {
                buildGridView();
            });
        }
    });
}

async function store() {
    wire.set("is_primary", $("input[name=is_primary]").is(":checked"), false);
    let f = await getBase64File("input[name=filefoto]");
    if (f !== null) {
        wire.set("filefoto", f);
    }

    wire.save().then(() => {
        buildGridView();
        // $('div#modalform').modal('hide');
    });
}