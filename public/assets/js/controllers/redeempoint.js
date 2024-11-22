var wire;

document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi Select2 dan DataTable
    setSelectedMenu("redeempoint");
    select2bind();
    buildTable();

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
    });
});

// Fungsi untuk membangun DataTable
function buildTable() {
    var mapstatus = ["Baru diajukan", "Proses", "Disetujui", "Ditolak"];
    var mapBadgestatus = [
        "badge-soft-primary",
        "badge-soft-warning",
        "badge-soft-success",
        "badge-soft-danger",
    ];

    // Inisialisasi DataTable untuk tabel pengajuan
    $("#jd-table").DataTable({
        dom: "Bfrtip",
        buttons: [
            {
                text: '<i class="mdi mdi-plus-circle"></i> Tambah',
                action: function () {
                    $("#modalform").modal("show");
                    wire.newForm(); // Memanggil fungsi newForm di Livewire
                },
                className: "btn-success",
            },
            "csv",
            "copy",
            "excel",
            "pdf",
            "print",
            {
                text: '<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: function () {
                    let url = $("#jd-table").data("urlaction");
                    showConfirmHapus("jd-table", url, function () {
                        $("#jd-table").DataTable().ajax.reload();
                    });
                },
                className: "btn-danger",
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
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            {
                data: "member",
                render: function (data, type, row) {
                    return `${data}<br/><small>${
                        row["points"] ?? "0"
                    } pts</small>`;
                },
            },
            { data: "reward" },
            { data: "point" },
            {
                data: "created_at",
                render: function (data, type, row) {
                    let status = mapstatus[row["status"]];
                    let badge = mapBadgestatus[row["status"]];
                    return `${data}<br/><span class="badge rounded-pill ${badge}">${status}</span>`;
                },
            },
            { data: "approved_at" },
            {
                data: "id",
                render: function (data) {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${data}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                },
            },
        ],
    });

    // Inisialisasi DataTable untuk tabel proses
    $("#jd-table-proses").DataTable({
        dom: "Bfrtip",
        buttons: ["csv", "copy", "excel", "pdf", "print"],
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
            { data: "id", sortable: false, searchable: false },
            { data: "member" },
            { data: "reward" },
            { data: "point" },
            { data: "created_at" },
            { data: "approved_at" },
            {
                data: "id",
                render: function (data) {
                    return `<button class='btn btn-sm btn-rounded btn-info'>Edit</button>`;
                },
            },
        ],
    });

    // Inisialisasi DataTable untuk tabel acc
    $("#jd-table-acc").DataTable({
        dom: "Bfrtip",
        buttons: ["csv", "copy", "excel", "pdf", "print"],
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
            { data: "id", sortable: false, searchable: false },
            { data: "member" },
            { data: "reward" },
            { data: "point" },
            { data: "created_at" },
            { data: "approved_at" },
            {
                data: "id",
                render: function (data) {
                    return `<button class='btn btn-sm btn-rounded btn-info'>Edit</button>`;
                },
            },
        ],
    });

    // Inisialisasi DataTable untuk tabel tolak
    $("#jd-table-tolak").DataTable({
        dom: "Bfrtip",
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
            { data: "id", sortable: false, searchable: false },
            { data: "member" },
            { data: "reward" },
            { data: "point" },
            { data: "created_at" },
            { data: "approved_at" },
            {
                data: "id",
                render: function (data) {
                    return `<button class='btn btn-sm btn-rounded btn-info'>Edit</button>`;
                },
            },
        ],
    });
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
