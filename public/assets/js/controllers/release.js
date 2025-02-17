var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('releasenotes');
    buildTableBug();
    buildTableImprovement();
    buildTablesolved();
    refreshDataSum();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.release.form')[0];
    wire.on('refreshData', function(){
        $('#jd-table').DataTable().ajax.reload();
        $("#jd-table-improve").DataTable().ajax.reload();
        $("#jd-table-solved").DataTable().ajax.reload();
        refreshDataSum();
    });
    
});

function refreshDataSum() {
    for (let i = 1; i < 4; i++) {
        // contoh: url('admin/redeempoint/count-tab/{i}')
        // Anda bisa menyesuaikan ke route actual di Controller
        let url = baseurl() + `/admin/release/count-tab/${i}`;
        $.get(url).done(function (jumlah) {
            $("#badge-info-" + i).text(jumlah);
        });
    }
}

// Fungsi untuk membangun DataTable

/**
 * Build DataTable: TAB BUG
 * Status = 3
 */
function buildTableBug() {
    $("#jd-table").DataTable({
        dom: "Bfrtip",
        pageLength: 25,
        buttons: [
            {
                text: '<i class="mdi mdi-plus-circle"></i> Tambah',
                className: "btn-success",
                action: function () {
                    // Tampilkan modal tambah data
                    $("form#form_data")[0].reset();
                    $("#modalform").modal("show");
                    // kalau pakai Livewire: wire.newForm() dsb
                },
            },
            "csv",
            "copy",
            "excel",
            "pdf",
            "print",
            {
                // Tombol Proses => status 3
                text: '<i class="mdi mdi-check"></i> Proses',
                className: "btn-info",
                action: function (e, dt, node, config) {
                    let urlAction =
                        $("#jd-table").data("urlaction") +
                        "/status";
                    showConfirm(
                        "jd-table",
                        "Proses Solved",
                        "Yakin proses data ini?",
                        function (res, ids) {
                            if (res.value) {
                                $.post(urlAction, {
                                    _token: csrf_token(),
                                    id: ids,
                                }).done(function (resp) {
                                    $('#jd-table').DataTable().ajax.reload();
                                    $("#jd-table-improve").DataTable().ajax.reload();
                                    $("#jd-table-solved").DataTable().ajax.reload();
                                    refreshDataSum();
                                });
                            }
                        }
                    );
                },
            },
        ],
        initComplete: function () {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#jd-table").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columns: [
            {
                data: "id",
                sortable: false,
                render: function (data, type, row, meta) {
                    // App.tableCheckID => fungsi custom checkbox + numbering
                    return (
                        App.tableCheckID(data) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            { data: 'kode'},
            { data: 'judul'},
            { data: 'deskripsi'},
            {
                data: "id",
                render: function (data, type, row, meta) {
                    return `
                        <button class="btn btn-sm btn-info" onclick="editdata('${data}')">
                            <i class="mdi mdi-pencil"></i> Edit
                        </button>
                    `;
                },
            },
        ],
    });
}

/**
 * Build DataTable: TAB IMPROVEMENT
 * Status = 1
 */
function buildTableImprovement() {
    $("#jd-table-improve").DataTable({
        dom: "Bfrtip",
        pageLength: 25,
        buttons: [
            "csv",
            "copy",
            "excel",
            "pdf",
            "print",
            {
                // Tombol Proses => status 3
                text: '<i class="mdi mdi-check"></i> Proses',
                className: "btn-info",
                action: function (e, dt, node, config) {
                    let urlAction =
                        $("#jd-table-improve").data("urlaction") +
                        "/status";
                    showConfirm(
                        "jd-table-improve",
                        "Proses Solved",
                        "Yakin proses data ini?",
                        function (res, ids) {
                            if (res.value) {
                                $.post(urlAction, {
                                    _token: csrf_token(),
                                    id: ids,
                                }).done(function (resp) {
                                    $('#jd-table').DataTable().ajax.reload();
                                    $("#jd-table-improve").DataTable().ajax.reload();
                                    $("#jd-table-solved").DataTable().ajax.reload();
                                    refreshDataSum();
                                });
                            }
                        }
                    );
                },
            },
        ],
        initComplete: function () {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#jd-table-improve").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columns: [
            {
                data: "id",
                sortable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            { data: 'kode'},
            { data: 'judul'},
            { data: 'deskripsi'},
            {
                data: "id",
                render: function (data, type, row, meta) {
                    return `
                        <button class="btn btn-sm btn-info" onclick="editdata('${data}')">
                            <i class="mdi mdi-pencil"></i> Edit
                        </button>
                    `;
                },
            },
        ],
    });
}

/**
 * Build DataTable: TAB sOLVED
 * Status = 3
 */
function buildTablesolved() {
    $("#jd-table-solved").DataTable({
        dom: "Bfrtip",
        pageLength: 25,
        buttons: ["csv", "copy", "excel", "pdf", "print"],
        initComplete: function () {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#jd-table-solved").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columns: [
            {
                data: "id",
                sortable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            { data: 'kode'},
            { data: 'judul'},
            { data: 'deskripsi'},
            {
                data: "id",
                render: function (data) {
                    // Data sudah ditolak, misal tidak ada aksi
                    return `<button class="btn btn-sm btn-secondary" disabled>No Action</button>`;
                },
            },
        ],
    });
}

function showConfirm(tableId, title, text, callback) {
    let ids = getCheckID(tableId);
    if (!ids || ids.length === 0) {
        Swal.fire("Pilih Data", "Belum ada data yang dipilih.", "warning");
        return;
    }

    Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((res) => {
        callback(res, ids);
    });
}

function getCheckID(tableId) {
    let arr = [];
    $("#" + tableId + " input.check").each(function () {
        if ($(this).is(":checked")) {
            arr.push($(this).val());
        }
    });
    return arr;
}

function save(){
    wire.save().then((_)={

    });
}
function editdata(id){
    $('form#form_data')[0].reset();
    $('#modalform').modal('show');
    wire.edit(id).then((e)=>{
        let tipe = wire.get('tipe');
        $('#tipe').val(tipe).change();
    });
}
