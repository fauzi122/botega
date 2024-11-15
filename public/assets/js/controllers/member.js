var wire;
var wireUserRek;

document.addEventListener("DOMContentLoaded", () => {
    setSelectedMenu("member");
    buildTable();
});

document.addEventListener("livewire:initialized", function () {
    wire = Livewire.getByName("admin.member.form")[0];
    wireUserRek = Livewire.getByName("admin.user-rekening.form")[0];

    wire.on("refreshData", function () {
        $("#jd-table").DataTable().ajax.reload();
        let id = wire.get("id");
        wireUserRek.editUser(id);
    });
});

function buildTable() {
    $("#jd-table").DataTable({
        dom: "Bltfrltip",
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ],
        pageLength: 10,
        buttons: [
            {
                text: '<i class="mdi mdi-plus-circle"></i> Tambah',
                action: (e, dt, node, c) => {
                    $("#modalform").modal("show");
                    wireUserRek.editUser(0);
                    wire.newForm();
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
                action: (e, dt, node, c) => {
                    let url = $("#jd-table").data("urlaction");
                    showConfirmHapus("jd-table", url, () => {
                        $("table#jd-table").DataTable().ajax.reload();
                    });
                },
                className: "btn-danger",
            },
        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: $("table#jd-table").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columnDefs: [],
        columns: [
            {
                data: "id",
                sortable: false,
                width: "20px",
                target: 0,
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            { data: "id_no" },
            {
                data: "first_name",
                render: (data, type, row, meta) => {
                    return `${data} ${
                        row["last_name"]
                    }<br/><small class="badge badge-soft-info" >${
                        row.kategori ?? ""
                    }</small> `;
                },
            },
            {
                data: "level_name",
                render: (data, type, row, meta) => {
                    let totalspent = formatUang(row["total_spent"]);
                    const reward = Number(row["reward_type"]);
                    const ispro = reward === 1 || reward === 3;
                    const label = ispro ? "Professional" : "";
                    return `${label} ${
                        data ?? ""
                    }<br/><small class="badge badge-soft-success">IDR ${totalspent}</small>`;
                },
            },
            {
                data: "hp",
                render: (data, type, row, meta) => {
                    const sudahverifi =
                        row["date_verify_email"] === null
                            ? ""
                            : `<i class="mdi mdi-check-all" style="color: green"></i>`;
                    return `${data ?? ""}<br/>${row["wa"] ?? ""}<br/>${
                        row["email"] ?? "-"
                    } ${sudahverifi}`;
                },
            },
            {
                data: "last_name",
                sortable: false,
                width: "100px",
                render: (data, type, row, meta) => {
                    return `<button class="btn btn-sm btn-info" onclick="editdata(${row["id"]})"><i class="mdi mdi-pencil"></i> Edit</button>
                     <button class="btn btn-sm btn-warning" onclick="syncData(${row["id"]})"><i class="mdi mdi-sync"></i> Sinkron</button>
                    `;
                },
            },
            { data: "email", visible: false },
            { data: "wa", visible: false },
        ],
    });
}

function simpanRekening() {
    wireUserRek.set("is_primary", $("input#is_primary").is(":checked"), false);
    wireUserRek.save().then(() => {});
}

function editdata(id) {
    $("#modalform").modal("show");
    $("#formmodal")[0].reset();
    $("img#foto_path_preview").attr("src", "");

    wireUserRek.editUser(id).then(() => {});

    wire.edit(id).then(() => {
        let url = wire.get("urlfoto");
        let reward = wire.get("reward_type");
        let cabangid = wire.get("cabang_id");

        if (cabangid === undefined) {
            $("select[name=cabang_id]").val("X").trigger("change");
        }
        $("img#foto_path_preview").attr("src", url);
        $("input[name=memberCheckbox]").prop(
            "checked",
            reward === 1 || reward === 3
        );
        $("input[name=umumCheckbox]").prop(
            "checked",
            reward === 2 || reward === 3
        );
    });
}
function save() {
    let m = $("input[name=memberCheckbox]").is(":checked") ? 1 : 0;
    let u = $("input[name=umumCheckbox]").is(":checked") ? 2 : 0;
    wire.set("reward_type", m + u, false);
    wire.save();
}

function editRek(id) {
    wireUserRek.edit(id).then(() => {
        let isprimary = wireUserRek.get("is_primary");
        $("#is_primary").prop("checked", isprimary == 1);
    });
}

function hapusRek(id) {
    Swal.fire({
        title: "Hapus data",
        text: "Data yang dihapus tidak dapat dikembalikan, mau dilanjutkan?",
        type: "question",
        confirmButtonText: "Hapus aja",
        cancelButtonText: "Gak jadi deh",
        showCancelButton: true,
    }).then((e) => {
        if (e.value) {
            wireUserRek.delete(id);
        }
    });
}
function syncData(id) {
    // Pastikan id valid sebelum sinkronisasi
    if (id) {
        // Memanggil endpoint sinkronisasi berdasarkan ID
        $.ajax({
            url: `/admin/member/sync-member/${id}`, // Pastikan endpoint ini sesuai dengan route yang telah dibuat
            method: "GET",
            success: function (response) {
                Swal.fire("Berhasil", response.message, "success");
                $("#jd-table").DataTable().ajax.reload(); // Memuat ulang tabel setelah sinkronisasi
            },
            error: function (error) {
                Swal.fire(
                    "Error",
                    "Gagal melakukan sinkronisasi data",
                    "error"
                );
            },
        });
    }
}
