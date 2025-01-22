var wire;

document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi Select2 dan DataTable
    setSelectedMenu("redeempoint");
    select2bind();
    // buildTable();

    buildTablePengajuan();
    buildTableProses();
    buildTableAcc();
    buildTableTolak();
    refreshDataSum();

    // Event listener untuk perubahan di reward_id
    $("select[name=reward_id]").on("change", function () {
        let rewardId = $(this).val();
        wire.showPoint(rewardId); // Panggil fungsi showPoint di Livewire
    });

    // Event listener untuk perubahan di user_id
    $("select[name=user_id]").on("change", function () {
        let userId = $(this).val();
        wire.set("user_id", userId); // Sinkronkan perubahan user_id
    });
});

// Menghubungkan wire dengan Livewire component
document.addEventListener("livewire:initialized", function () {
    wire = Livewire.getByName("admin.redeempoint.form")[0];

    // Refresh data tabel jika ada perubahan
    wire.on("refreshData", function () {
        $("#jd-table").DataTable().ajax.reload();
        $("#jd-table-proses").DataTable().ajax.reload();
        $("#jd-table-acc").DataTable().ajax.reload();
        $("#jd-table-tolak").DataTable().ajax.reload();
        refreshDataSum();
    });
});

function refreshDataSum() {
    for (let i = 0; i < 4; i++) {
        // contoh: url('admin/redeempoint/count-tab/{i}')
        // Anda bisa menyesuaikan ke route actual di Controller
        let url = baseurl() + `/admin/redeem/count-tab/${i}`;
        $.get(url).done(function (jumlah) {
            $("#badge-info-" + i).text(jumlah);
        });
    }
}
// Fungsi untuk membangun DataTable
function buildTablePengajuan() {
    $("#jd-table").DataTable({
        dom: "Bfrtip",
        pageLength: 25,
        buttons: [
            {
                text: '<i class="mdi mdi-plus-circle"></i> Tambah',
                className: "btn-success",
                action: function () {
                    // Tampilkan modal tambah data
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
                // Tombol Pengajuan => pindahkan status 0 => 1 (Proses)
                text: '<i class="mdi mdi-paperclip"></i> Pengajuan',
                className: "btn-warning",
                action: function (e, dt, node, config) {
                    let urlAction =
                        $("#jd-table").data("urlaction") + "/status/pengajuan";
                    showConfirm(
                        "jd-table",
                        "Pengajuan Redeem",
                        "Yakin mengajukan data ini?",
                        function (res, ids) {
                            if (res.value) {
                                $.post(urlAction, {
                                    _token: csrf_token(),
                                    id: ids,
                                }).done(function (resp) {
                                    $("#jd-table").DataTable().ajax.reload();
                                    $("#jd-table-proses")
                                        .DataTable()
                                        .ajax.reload();
                                    refreshDataSum();
                                });
                            }
                        }
                    );
                },
            },
            {
                // Tombol Tolak => status 3
                text: '<i class="mdi mdi-trash-can-outline"></i> Tolak',
                className: "btn-danger",
                action: function (e, dt, node, config) {
                    let urlAction =
                        $("#jd-table").data("urlaction") + "/status/tolak";
                    showConfirm(
                        "jd-table",
                        "Tolak Redeem",
                        "Yakin menolak data ini?",
                        function (res, ids) {
                            if (res.value) {
                                $.post(urlAction, {
                                    _token: csrf_token(),
                                    id: ids,
                                }).done(function (resp) {
                                    $("#jd-table").DataTable().ajax.reload();
                                    $("#jd-table-tolak")
                                        .DataTable()
                                        .ajax.reload();
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
            { data: "member" },
            { data: "reward" },
            // { data: "point" },
            { data: "created_at" }, // pengajuan
            { data: "approved_at" }, // setujui
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
 * Build DataTable: TAB PROSES
 * Status = 1
 */
function buildTableProses() {
    $("#jd-table-proses").DataTable({
        dom: "Bfrtip",
        pageLength: 25,
        buttons: [
            "csv",
            "copy",
            "excel",
            "pdf",
            "print",
            {
                // Tombol Setujui => status 2
                text: '<i class="mdi mdi-check"></i> Setujui',
                className: "btn-success",
                action: function (e, dt, node, config) {
                    let urlAction =
                        $("#jd-table-proses").data("urlaction") + "/status/acc";
                    showConfirm(
                        "jd-table-proses",
                        "Setujui Redeem",
                        "Yakin menyetujui data ini?",
                        function (res, ids) {
                            if (res.value) {
                                $.post(urlAction, {
                                    _token: csrf_token(),
                                    id: ids,
                                }).done(function (resp) {
                                    $("#jd-table-proses")
                                        .DataTable()
                                        .ajax.reload();
                                    $("#jd-table-acc")
                                        .DataTable()
                                        .ajax.reload();
                                    refreshDataSum();
                                });
                            }
                        }
                    );
                },
            },
            {
                // Tombol Tolak => status 3
                text: '<i class="mdi mdi-trash-can-outline"></i> Pengajuan',
                className: "btn-danger",
                action: function (e, dt, node, config) {
                    let urlAction =
                        $("#jd-table-proses").data("urlaction") +
                        "/status/draft";
                    showConfirm(
                        "jd-table-proses",
                        "Pengajuan Redeem",
                        "Yakin pengajan data ini?",
                        function (res, ids) {
                            if (res.value) {
                                $.post(urlAction, {
                                    _token: csrf_token(),
                                    id: ids,
                                }).done(function (resp) {
                                    $("#jd-table-proses")
                                        .DataTable()
                                        .ajax.reload();
                                    $("#jd-table").DataTable().ajax.reload();
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
            url: $("#jd-table-proses").data("datasource"),
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
            { data: "member" },
            { data: "reward" },
            // { data: "point" },
            { data: "created_at" }, // pengajuan
            { data: "approved_at" }, // setujui
            {
                data: "id",
                render: function (data) {
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
 * Build DataTable: TAB DISUTUJUI
 * Status = 2
 */
function buildTableAcc() {
    $("#jd-table-acc").DataTable({
        dom: "Bfrtip",
        pageLength: 25,
        buttons: [
            "csv",
            "copy",
            "excel",
            "pdf",
            "print",
            {
                // Tombol Tolak => status 3
                text: '<i class="mdi mdi-trash-can-outline"></i> Proses',
                className: "btn-danger",
                action: function (e, dt, node, config) {
                    let urlAction =
                        $("#jd-table-acc").data("urlaction") +
                        "/status/pengajuan";
                    showConfirm(
                        "jd-table-acc",
                        "Proses Redeem",
                        "Yakin proses data ini?",
                        function (res, ids) {
                            if (res.value) {
                                $.post(urlAction, {
                                    _token: csrf_token(),
                                    id: ids,
                                }).done(function (resp) {
                                    $("#jd-table-acc")
                                        .DataTable()
                                        .ajax.reload();
                                    $("#jd-table-proses")
                                        .DataTable()
                                        .ajax.reload();
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
            url: $("#jd-table-acc").data("datasource"),
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
            { data: "member" },
            { data: "reward" },
            // { data: "point" },
            { data: "created_at" }, // pengajuan
            { data: "approved_at" }, // setujui
            {
                data: "id",
                render: function (data) {
                    // return `
                    //     <button class="btn btn-sm btn-info" onclick="editdata('${data}')">
                    //         <i class="mdi mdi-pencil"></i> Edit
                    //     </button>
                    // `;
                    return `<button class="btn btn-sm btn-secondary" disabled>No Action</button>`;
                },
            },
        ],
    });
}

/**
 * Build DataTable: TAB TOLAK
 * Status = 3
 */
function buildTableTolak() {
    $("#jd-table-tolak").DataTable({
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
            url: $("#jd-table-tolak").data("datasource"),
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
            { data: "member" },
            { data: "reward" },
            // { data: "point" },
            { data: "created_at" }, // pengajuan
            { data: "approved_at" }, // setujui
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
// Fungsi untuk menyimpan data melalui Livewire
function save() {
    wire.set("user_id", $("select[name=user_id]").val(), false);
    wire.set("reward_id", $("select[name=reward_id]").val(), false);

    wire.save().then(() => {
        let pesan = wire.get("pesan");
        if (pesan !== "") {
            Swal.fire({
                title: "Konfirmasi",
                text: pesan,
                icon: "warning",
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    wire.set("confirm", true, false);
                    wire.save(); // Simpan ulang setelah konfirmasi
                }
            });
        }
    });
}

// Fungsi untuk mengedit data melalui Livewire
function editdata(id) {
    $("#modalform").modal("show");
    $("form#form_data")[0].reset();

    wire.edit(id).then(() => {
        let userId = wire.get("user_id");
        let member = wire.get("member");
        let rewardId = wire.get("reward_id");
        let reward = wire.get("reward");

        // Set nilai pada field user_id
        $("select[name=user_id]").html(
            `<option value="${userId}">${member}</option>`
        );
        $("select[name=user_id]").val(userId).trigger("change");

        // Set nilai pada field reward_id
        $("select[name=reward_id]").html(
            `<option value="${rewardId}">${reward}</option>`
        );
        $("select[name=reward_id]").val(rewardId).trigger("change");

        // Panggil ulang showPoint untuk menghitung sisa poin
        wire.showPoint(rewardId);
    });
}
