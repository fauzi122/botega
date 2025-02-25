var wire;
var wireDetail;
var wireMerger;

document.addEventListener("DOMContentLoaded", function () {
    setSelectedMenu("fee");
    select2bind();
    buildTable();
    $(".money").mask("#,###,###,###,###,###,-", { reverse: true });

    $("div#modalform").on("change", "select#member_user_id", function () {
        let v = $("select#member_user_id").val();
        wire.set("member_user_id", v);
    });

    $("div#modalform").on("change", "select#nomor_so", function () {
        let v = $("select#nomor_so").val();

        wire.getInfoTransaction(v).then(() => {
            // $('input#noso').val(wire.noso);
            $("input#customer").val(wire.customerName);
        });

        // $.get(baseurl() + '/admin/penjualan/json-detail/'+v).done(function(e){
        //     let data = e.data;
        //     // $("input#noso").val(data.nomor_so);
        //     $("input#nosj").val(data.nomor_sj);
        //     $("input#customer").val(data.member + " " + data.last_name);
        //
        // });
    });

    $("div#tab-resume").on("change", "input#checkall", function () {
        let c = $("div#tab-resume input#checkall").is(":checked");
        $("div#tab-resume input.check").prop("checked", c);
    });

    $("div#tab-pengajuan").on("change", "input#checkall", function () {
        let c = $("div#tab-pengajuan input#checkall").is(":checked");
        $("div#tab-pengajuan input.check").prop("checked", c);
    });

    $("div#tab-acc").on("change", "input#checkall", function () {
        let c = $("div#tab-acc input#checkall").is(":checked");
        $("div#tab-acc input.check").prop("checked", c);
    });

    $("div#tab-finish").on("change", "input#checkall", function () {
        let c = $("div#tab-finish input#checkall").is(":checked");
        $("div#tab-finish input.check").prop("checked", c);
    });

    refreshDataSUM();
});

function refreshDataSUM() {
    $.get(baseurl() + "/admin/fee/count-tab/0").done(function (e) {
        $("#badge-info-0").html(e);
    });
    $.get(baseurl() + "/admin/fee/count-tab/1").done(function (e) {
        $("#badge-info-1").html(e);
    });
    $.get(baseurl() + "/admin/fee/count-tab/2").done(function (e) {
        $("#badge-info-2").html(e);
    });
    $.get(baseurl() + "/admin/fee/count-tab/3").done(function (e) {
        $("#badge-info-3").html(e);
    });
    $.get(baseurl() + "/admin/fee/count-tab/4").done(function (e) {
        $("#badge-info-4").html(e);
    });

    $.get(baseurl() + "/admin/fee/sum-tab/0").done(function (e) {
        $("#sum-info-0").html("Total Bayar : IDR " + formatUang(Number(e)));
    });
    $.get(baseurl() + "/admin/fee/sum-tab/1").done(function (e) {
        $("#sum-info-1").html("Total Bayar : IDR " + formatUang(Number(e)));
    });
    $.get(baseurl() + "/admin/fee/sum-tab/2").done(function (e) {
        $("#sum-info-2").html("Total Bayar : IDR " + formatUang(Number(e)));
    });
    $.get(baseurl() + "/admin/fee/sum-tab/3").done(function (e) {
        $("#sum-info-3").html("Total Bayar : IDR " + formatUang(Number(e)));
    });
    $.get(baseurl() + "/admin/fee/sum-tab/4").done(function (e) {
        $("#sum-info-4").html("Total Bayar : IDR " + formatUang(Number(e)));
    });
}

document.addEventListener("livewire:initialized", function () {
    wire = Livewire.getByName("admin.fee.form")[0];
    wireDetail = Livewire.getByName("admin.fee.detailfee")[0];
    wireMerger = Livewire.getByName("admin.fee.merger")[0];

    wire.on("refresh", function () {
        $("#jd-table").DataTable().ajax.reload();
        refreshDataSUM();
    });
    wire.on("refresh1", function () {
        $("#jd-table").DataTable().ajax.reload();
        refreshDataSUM();
    });
    wireMerger.on("refreshData", function () {
        $("#modal-merger").modal("hide");
        $("#jd-table-setujui").DataTable().ajax.reload();
        refreshDataSUM();
    });
});

var isloading = false;

function buildTable() {
    $("#jd-table").DataTable({
        dom: "Bfrtp",
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
        ],
        pageLength: 100,
        buttons: [
            {
                text: '<i class="mdi mdi-plus-circle"></i> Tambah',
                action: (e, dt, node, c) => {
                    wire.newdata().then(() => {
                        $("#member_user_id").val("").trigger("change");
                        $("#nomor_so").val("").trigger("change");
                    });
                    $("#modalform").modal("show");
                },
                className: "btn-info",
            },
            {
                text: '<i class="mdi mdi-paperclip"></i> Pengajuan',
                action: (e, dt, node, c) => {
                    $("#jd-table_processing").css("display", "block");
                    let url = $("#jd-table").data("urlaction");
                    showConfirm(
                        "jd-table",
                        "Pengajuan Fee",
                        "Pastikan data member yang akan di usulkan sudah benar untuk dilakukan proses pengajuan?",
                        (e, id) => {
                            if (e.value) {
                                $.post(url + "/status/pengajuan", {
                                    _token: csrf_token(),
                                    id: id,
                                })
                                    .done(function (e) {
                                        $("table#jd-table")
                                            .DataTable()
                                            .ajax.reload();
                                        $("table#jd-table-pengajuan")
                                            .DataTable()
                                            .ajax.reload();
                                        refreshDataSUM();
                                    })
                                    .catch(function (e) {
                                        $("table#jd-table")
                                            .DataTable()
                                            .ajax.reload();
                                    });
                            }
                        }
                    );
                },
                className: "btn-warning",
            },
            "csv",
            "copy",
            {
                text: '<i class="mdi mdi-file-excel"></i> XLS',
                action: (e, dt, node, c) => {
                    window.open(
                        baseurl() + "/admin/fee/download/fee.xls",
                        "frame-download"
                    );
                },
                className: "btn-success",
            },
            {
                extend: "pdfHtml5",
                orientation: "landscape", // Mengatur orientasi menjadi landscape
                pageSize: "A4", // Mengatur ukuran halaman menjadi A4
                text: "Export PDF",
                title: "Fee Professional",
                titleAttr: "PDF",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                },
            },
            "print",
            {
                text: '<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e, dt, node, c) => {
                    let url = $("#jd-table").data("urlaction");
                    showConfirmHapus("jd-table", url, (e, id) => {
                        $("table#jd-table").DataTable().ajax.reload();
                        refreshDataSUM();
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
        columnDefs: [{ targets: [5, 6, 7, 8, 9, 10], className: "dt-right" }],
        columns: [
            {
                data: "member_user_id",
                sortable: false,
                width: "20px",
                target: 0,
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data + "|" + row["fee_number_id"]) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            {
                data: "member_user_id",
                render: (data, type, row, meta) => {
                    let btnedit = `<button class="btn btn-sm btn-rounded btn-info" onclick="editdata('${data}')"><i class="mdi mdi-pencil-circle"></i></button>`;
                    return btnedit;
                },
            },

            { data: "nomor" },
            { data: "periode" },
            {
                data: "member",
                render: (data, type, row, meta) => {
                    return `${data} (${
                        row["id_no"]
                    })<br/><small class="badge badge-soft-info">${
                        row["kategori"] ?? "-"
                    }</small>`;
                },
                searchable: true,
            },
            {
                data: "npwp",
                render: (data, type, row, meta) => {
                    return data?.length > 7 ? "Ya" : "Tidak";
                },
            },
            {
                data: "dpp_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "fee_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? "-"
                        : formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? formatUang(data)
                        : "-";
                },
            },
            {
                data: "total_pembayaran",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
        ],
    });

    $("#jd-table-pengajuan").DataTable({
        dom: "Bfrtp",
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
        ],
        pageLength: 100,

        buttons: [
            {
                text: '<i class="mdi mdi-paperclip"></i> Setujui',
                action: (e, dt, node, c) => {
                    $("#jd-table-pengajuan_processing").css("display", "block");
                    let url = $("#jd-table-pengajuan").data("urlaction");

                    showConfirm(
                        "jd-table-pengajuan",
                        "Proses Fee",
                        "Pastikan data member yang akan di usulkan untuk di proses sudah benar?",
                        (e, id) => {
                            if (e.value) {
                                $.post(url + "/status/acc", {
                                    _token: csrf_token(),
                                    id: id,
                                })
                                    .done(function (e) {
                                        $("#jd-table_processing").css(
                                            "display",
                                            "none"
                                        );
                                        $("table#jd-table-pengajuan")
                                            .DataTable()
                                            .ajax.reload();
                                        // $('table#jd-table-proses').DataTable().ajax.reload();
                                        $("table#jd-table-setujui")
                                            .DataTable()
                                            .ajax.reload();
                                        refreshDataSUM();
                                    })
                                    .catch(function (e) {})
                                    .catch(function (e) {
                                        $("table#jd-table-pengajuan")
                                            .DataTable()
                                            .ajax.reload();
                                        $("#jd-table_processing").css(
                                            "display",
                                            "none"
                                        );
                                    });
                            }
                        }
                    );
                },
                className: "btn-warning",
            },
            "csv",
            "copy",
            {
                text: '<i class="mdi mdi-file-excel"></i> XLS',
                action: (e, dt, node, c) => {
                    $("#jd-table-pengajuan_processing").css("display", "block");
                    window.open(
                        baseurl() + "/admin/fee/download/fee-pengajuan.xls",
                        "frame-downloads"
                    );

                    setTimeout(function () {
                        $("#jd-table-pengajuan_processing").css(
                            "display",
                            "none"
                        );
                    }, 3000);
                },
                className: "btn-success",
            },
            {
                extend: "pdfHtml5",
                orientation: "landscape", // Mengatur orientasi menjadi landscape
                pageSize: "A4", // Mengatur ukuran halaman menjadi A4
                text: "Export PDF",
                title: "Pengajuan Fee Professional",
                titleAttr: "PDF",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                },
            },
            "print",
            {
                text: '<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e, dt, node, c) => {
                    let url =
                        $("#jd-table-pengajuan").data("urlaction") +
                        "/remove/pengajuan";
                    showConfirmHapus("jd-table-pengajuan", url, () => {
                        $("table#jd-table-pengajuan").DataTable().ajax.reload();
                        $("table#jd-table").DataTable().ajax.reload();
                        refreshDataSUM();
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
            url: $("table#jd-table-pengajuan").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columnDefs: [{ targets: [5, 6, 7, 8, 9, 10], className: "dt-right" }],
        columns: [
            {
                data: "member_user_id",
                sortable: false,
                width: "20px",
                target: 0,
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data + "|" + row["fee_number_id"]) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            {
                data: "fee_number_id",
                render: (data, type, row, meta) => {
                    let periode = row["periode"];
                    return `<button class="btn btn-sm btn-rounded btn-info" onclick="showDetailFee('${data}')"><i class="mdi mdi-pencil-circle"></i></button>`;
                },
            },
            { data: "nomor" },
            { data: "periode" },
            {
                data: "member",
                render: (data, type, row, meta) => {
                    return `${data} (${
                        row["id_no"]
                    })<br/><small class="badge badge-soft-info">${
                        row["kategori"] ?? "-"
                    }</small>`;
                },
                searchable: true,
            },
            {
                data: "npwp",
                render: (data, type, row, meta) => {
                    return data?.length > 7 ? "Ya" : "Tidak";
                },
            },
            {
                data: "dpp_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "fee_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? "-"
                        : formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? formatUang(data)
                        : "-";
                },
            },
            {
                data: "total_pembayaran",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
        ],
    });
    /***
    $('#jd-table-proses').DataTable({
        dom: 'Bfrtilp',
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        pageLength: 100,

        buttons:[
            {
                text:'<i class="mdi mdi-paperclip"></i> Setujui',
                action: (e,dt,node, c)=>{
                    $('#jd-table_processing').css('display','block');
                    let url =  $('#jd-table-proses').data('urlaction');
                    showConfirm('jd-table-proses', "Setujui Fee", "Pastikan data member yang akan di usulkan untuk di setujui sudah benar?",(e, id)=>{
                        if(e.value){
                            $.post(url + '/status/acc', {
                                '_token': csrf_token(),
                                'id': id
                            }).done(function(e){
                                $('#jd-table_processing').css('display','none');
                                $('table#jd-table-proses').DataTable().ajax.reload();
                                $('table#jd-table-setujui').DataTable().ajax.reload();
                            }).catch(function(e){
                                $('#jd-table_processing').css('display','none');
                            });
                        }
                    });
                },
                className: 'btn-success'
            },
            'csv', 'copy', {
                text:'<i class="mdi mdi-file-excel"></i>',
                action: (e,dt,node, c)=>{
                    window.open( baseurl() + '/admin/fee/download/fee-proses.xls' ,'frame-download');
                },
                className: 'btn-success'
            }, 'pdf', 'print',
            {
                text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e,dt,node, c)=>{
                    let url =  $('#jd-table-proses').data('urlaction') + '/remove/proses';
                    showConfirmHapus('jd-table-proses', url , ()=>{
                        $('table#jd-table-proses').DataTable().ajax.reload();
                        $('table#jd-table-pengajuan').DataTable().ajax.reload();
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
            url:$('table#jd-table-proses').data('datasource'),
            method: 'GET'
        },
        order: [[1, 'asc']],

        columns:[
            { data:'member_user_id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data + "|" + row["fee_number_id"]) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },

            { data:'nomor'},
            { data:'periode'},
            { data:'first_name', render:(data, type, row, meta) => {
                    return `${data} ${row['last_name']} (${row['id_no']})`;
                }},
            { data: 'npwp', render:(data, type, row, meta) => {
                    return data?.length > 7 ? 'Ya' : 'Tidak';
                }},
            { data: 'dpp_amount', render:(data, type, row, meta) => {
                    return formatUang(data);
                }},
            { data: 'fee_amount', render:(data, type, row, meta) => {
                    return formatUang(data);
                }},
            { data: 'pph_amount',  render:(data, type, row, meta) => {
                    return row['is_perusahaan'] === '1' ? '-' : formatUang(data);
                }},
            { data: 'pph_amount', render:(data, type, row, meta) => {
                    return   row['is_perusahaan'] === '1' ?  formatUang(data) : '-';
                }},
            { data: 'total_pembayaran',  render:(data, type, row, meta) => {
                    return formatUang(data);
                }},
            {
                data:'nama_bank', render:(data, type, row, meta)=>{
                    return `${row['nama_bank']} - ${row['no_rekening']}<br><span class="badge badge-soft-primary">${row['an_rekening']}</span>`;
                }
            },

            {
                data:'member_user_id', render:(data, type, row, meta) => {
                    let periode = row['periode'];
                    return `<button class="btn btn-sm btn-rounded btn-info" onclick="showDetailFee('${data}', '${periode}')"><i class="mdi mdi-pencil-circle"></i></button>`;
                }
            }

        ]
    });
*/
    $("#jd-table-setujui").DataTable({
        dom: "Bfrtp",
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
        ],
        pageLength: 100,

        buttons: [
            {
                text: '<i class="mdi mdi-paperclip"></i> Selesai',
                action: (e, dt, node, c) => {
                    $("#jd-table_processing").css("display", "block");
                    let url = $("#jd-table-setujui").data("urlaction");
                    showConfirm(
                        "jd-table-setujui",
                        "Selesai Pembayaran",
                        "Pastikan data member yang akan di usulkan untuk di selesai sudah benar?",
                        (e, id) => {
                            if (e.value) {
                                $.post(url + "/status/selesai", {
                                    _token: csrf_token(),
                                    id: id,
                                })
                                    .done(function (e) {
                                        $("table#jd-table-setujui")
                                            .DataTable()
                                            .ajax.reload();
                                        $("table#jd-table-selesai")
                                            .DataTable()
                                            .ajax.reload();
                                        refreshDataSUM();
                                    })
                                    .catch(function (e) {
                                        $("#jd-table_processing").css(
                                            "display",
                                            "none"
                                        );
                                    });
                            }
                        }
                    );
                },
                className: "btn-info",
            },
            {
                text: '<i class="mdi mdi-file"></i> CSV',
                action: (e, dt, node, c) => {
                    $("#jd-table-pengajuan_processing").css("display", "block");
                    var ids = [];
                    $("div#tab-acc input.check").each((i, obj) => {
                        let chk = $(obj).is(":checked");

                        if (chk === true) {
                            let n = $(obj).val().toString().split("|");
                            ids.push(n[1]);
                        }
                    });
                    let url =
                        baseurl() +
                        `/admin/fee/download-csv/fee-disetujui.xls?id=${JSON.stringify(
                            ids
                        )}`;
                    console.log(url);
                    window.open(url, "frame-downloadx");

                    setTimeout(function () {
                        $("#jd-table-pengajuan_processing").css(
                            "display",
                            "none"
                        );
                    }, 3000);
                },
                className: "btn-info",
            },

            {
                text: '<i class="mdi mdi-merge"></i> Merger',
                action: (e, dt, node, c) => {
                    const id = getCheckID("jd-table-setujui");
                    if (id.length === 0) {
                        Swal.fire({
                            title: "Tidak ada data yang dipilih",
                            text: "Silahkan pilih data yang akan di merger",
                            type: "warning",
                        });
                    } else {
                        wireMerger.setId(id);
                        $("#modal-merger").modal("show");
                    }
                },
                className: "btn-warning",
            },

            "copy",
            {
                text: '<i class="mdi mdi-file-excel"></i>',
                action: (e, dt, node, c) => {
                    window.open(
                        baseurl() + "/admin/fee/download/fee-acc.xls",
                        "__blank"
                    );
                },
                className: "btn-success",
            },
            {
                extend: "pdfHtml5",
                orientation: "landscape", // Mengatur orientasi menjadi landscape
                pageSize: "A4", // Mengatur ukuran halaman menjadi A4
                text: "Export PDF",
                title: "Disetujui Fee Professional",
                titleAttr: "PDF",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                },
            },
            "print",
            {
                text: '<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e, dt, node, c) => {
                    let url =
                        $("#jd-table-setujui").data("urlaction") +
                        "/remove/acc";
                    showConfirmHapus("jd-table-setujui", url, () => {
                        $("table#jd-table-setujui").DataTable().ajax.reload();
                        $("table#jd-table-pengajuan").DataTable().ajax.reload();
                        refreshDataSUM();
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
            url: $("table#jd-table-setujui").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columnDefs: [{ targets: [5, 6, 7, 8, 9, 10], className: "dt-right" }],
        columns: [
            {
                data: "member_user_id",
                sortable: false,
                width: "20px",
                target: 0,
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data + "|" + row["fee_number_id"]) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            {
                data: "fee_number_id",
                render: (data, type, row, meta) => {
                    let periode = row["periode"];
                    return `<button class="btn btn-sm btn-rounded btn-info" onclick="showDetailFee('${data}')"><i class="mdi mdi-pencil-circle"></i></button>`;
                },
            },
            {
                data: "nomor",
                render: (data, type, row, meta) => {
                    return `${data}<br/><small class="badge badge-soft-success">${
                        row["kode_merger"] ?? ""
                    }</small>`;
                },
            },
            { data: "periode" },
            {
                data: "member",
                render: (data, type, row, meta) => {
                    return `${data} (${
                        row["id_no"]
                    })<br/><small class="badge badge-soft-info">${
                        row["kategori"] ?? "-"
                    }</small>`;
                },
                searchable: true,
            },
            {
                data: "npwp",
                render: (data, type, row, meta) => {
                    return data?.length > 7 ? "Ya" : "Tidak";
                },
            },
            {
                data: "dpp_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "fee_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? "-"
                        : formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? formatUang(data)
                        : "-";
                },
            },
            {
                data: "total_pembayaran",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },

            {
                data: "nama_bank",
                render: (data, type, row, meta) => {
                    return `${row["nama_bank"] ?? "[Bank not set]"} - ${
                        row["no_rekening"] ?? "[account bank not set]"
                    }<br><span class="badge badge-soft-primary">${
                        row["an_rekening"] ?? "Not set"
                    }</span>`;
                },
            },
            {
                data: null, // Karena hanya tombol, gunakan `null`
                render: function (data, type, row, meta) {
                    return `<button class="btn btn-sm btn-rounded btn-warning proses-dp-btn"
                    data-nomor="${row["nomor"]}" 
                    data-ids="${row["member_user_id"]}|${row["fee_number_id"]}">
                <i class="mdi mdi-arrow-right-bold"></i> Proses DP
            </button>`;
                },
                sortable: false, // Tidak perlu sorting
                width: "80px", // Atur lebar sesuai kebutuhan
                className: "text-center", // Untuk memposisikan tombol di tengah
            },

            { data: "no_rekening", visible: false },

            { data: "kode_merger", visible: false },
        ],
    });

    $("#jd-table-selesai").DataTable({
        dom: "Bfrtp",
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
        ],
        pageLength: 100,

        buttons: [
            "csv",
            "copy",
            {
                text: '<i class="mdi mdi-file-excel"></i>',
                action: (e, dt, node, c) => {
                    window.open(
                        baseurl() + "/admin/fee/download/fee-finish.xls",
                        "frame-download"
                    );
                },
                className: "btn-success",
            },
            {
                extend: "pdfHtml5",
                orientation: "landscape", // Mengatur orientasi menjadi landscape
                pageSize: "A4", // Mengatur ukuran halaman menjadi A4
                text: "Export PDF",
                title: "Fee Professional Selesai",
                titleAttr: "PDF",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                },
            },
            "print",
            {
                text: '<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e, dt, node, c) => {
                    let url =
                        $("#jd-table-selesai").data("urlaction") +
                        "/remove/selesai";
                    showConfirmHapus("jd-table-selesai", url, () => {
                        $("table#jd-table-selesai").DataTable().ajax.reload();
                        $("table#jd-table-setujui").DataTable().ajax.reload();
                        refreshDataSUM();
                        l;
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
            url: $("table#jd-table-selesai").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columnDefs: [{ targets: [5, 6, 7, 8, 9, 10], className: "dt-right" }],
        columns: [
            {
                data: "member_user_id",
                sortable: false,
                width: "20px",
                target: 0,
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data + "|" + row["fee_number_id"]) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            {
                data: "fee_number_id",
                render: (data, type, row, meta) => {
                    let periode = row["periode"];
                    return `<button class="btn btn-sm btn-rounded btn-info" onclick="showDetailFee('${data}')"><i class="mdi mdi-pencil-circle"></i></button>`;
                },
            },
            {
                data: "nomor",
                render: (data, type, row, meta) => {
                    return `${data}<br/>
                            <small class="badge badge-soft-success">${
                                row["kode_merger"] ?? ""
                            }</small>`;
                },
            },
            { data: "periode" },
            {
                data: "member",
                render: (data, type, row, meta) => {
                    return `${data} (${
                        row["id_no"]
                    })<br/><small class="badge badge-soft-info">${
                        row["kategori"] ?? "-"
                    }</small>
                            `;
                },
                searchable: true,
            },
            {
                data: "npwp",
                render: (data, type, row, meta) => {
                    return data?.length > 7 ? "Ya" : "Tidak";
                },
            },
            {
                data: "dpp_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "fee_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? "-"
                        : formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? formatUang(data)
                        : "-";
                },
            },
            {
                data: "total_pembayaran",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "no_faktur",
                render: (data, type, row, meta) => {
                    return `${row["no_faktur"] ?? row["kode_merger"]}`;
                },
            },
        ],
    });

    $("#jd-table-dp").DataTable({
        dom: "Bfrtp",
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
        ],
        pageLength: 100,

        buttons: [
            "csv",
            "copy",
            {
                // text: '<i class="mdi mdi-file-excel"></i>',
                // action: (e, dt, node, c) => {
                //     window.open(
                //         baseurl() + "/admin/fee/download/fee-finish.xls",
                //         "frame-download"
                //     );
                // },
                // className: "btn-success",
            },
            {
                extend: "pdfHtml5",
                orientation: "landscape", // Mengatur orientasi menjadi landscape
                pageSize: "A4", // Mengatur ukuran halaman menjadi A4
                text: "Export PDF",
                title: "Fee Professional Selesai",
                titleAttr: "PDF",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                },
            },
            "print",
            {
                text: '<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e, dt, node, c) => {
                    let url =
                        $("#jd-table-dp").data("urlaction") + "/remove/dp";
                    showConfirmHapus("jd-table-dp", url, () => {
                        $("table#jd-table-dp").DataTable().ajax.reload();
                        $("table#jd-table-setujui").DataTable().ajax.reload();
                        refreshDataSUM();
                        l;
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
            url: $("table#jd-table-dp").data("datasource"),
            method: "GET",
        },
        order: [[1, "asc"]],
        columnDefs: [{ targets: [5, 6, 7, 8, 9, 10], className: "dt-right" }],
        columns: [
            {
                data: "member_user_id",
                sortable: false,
                width: "20px",
                target: 0,
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        App.tableCheckID(data + "|" + row["fee_number_id"]) +
                        (meta.row + 1 + meta.settings._iDisplayStart)
                    );
                },
            },
            {
                data: "fee_number_id",
                render: (data, type, row, meta) => {
                    let periode = row["periode"];
                    return `<button class="btn btn-sm btn-rounded btn-info" onclick="showDetailFee('${data}')"><i class="mdi mdi-pencil-circle"></i></button>`;
                },
            },
            {
                data: "nomor",
                render: (data, type, row, meta) => {
                    return `${data} </br>
                            <small class="badge badge-soft-success">${
                                row["kode_merger"] ?? ""
                            }</small>`;
                },
            },
            { data: "periode" },
            {
                data: "member",
                render: (data, type, row, meta) => {
                    return `${data} (${
                        row["id_no"]
                    })<br/><small class="badge badge-soft-success">${
                        row["change_customer"] ?? "-"
                    }</small>
                    <br/><small class="badge badge-soft-info">${
                        row["kategori"] ?? "-"
                    }</small>
                            `;
                },
                searchable: true,
            },
            {
                data: "npwp",
                render: (data, type, row, meta) => {
                    return data?.length > 7 ? "Ya" : "Tidak";
                },
            },
            {
                data: "dpp_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "fee_amount",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? "-"
                        : formatUang(data);
                },
            },
            {
                data: "pph_amount",
                render: (data, type, row, meta) => {
                    return Number(row["is_perusahaan"]) === 1
                        ? formatUang(data)
                        : "-";
                },
            },
            {
                data: "total_pembayaran",
                render: (data, type, row, meta) => {
                    return formatUang(data);
                },
            },
            {
                data: "number",
                render: (data, type, row, meta) => {
                    return `${row["number"] ?? "[Unavailable]"}`;
                },
            },
        ],
    });
}

function save() {
    let periode = document.getElementById("periode").value;
    if (!periode) {
        Swal.fire("Error", "Tanggal periode wajib diisi!", "error");
        return;
    }
    wire.save().then(() => {});
}

function editdata(id) {
    $("#modalform").modal("show");
    $("#form_data")[0].reset();

    wire.edit(id).then(() => {
        let name = wire.get("namamemberlengkap");
        $("select#member_user_id").html(
            `<option value="${id}">${name}</option>`
        );
        $("select#member_user_id").val(id).trigger("change");

        $("select#nomor_so").html(``);
        $("select#nomor_so").val("").trigger("change");
    });
}

function showDetailFee(fee_number_id) {
    wireDetail.set("fee_number_id", fee_number_id);
    $("div#modalformDetail").modal("show");
}

$(document).ready(function () {
    let lastNomor = null; // Menyimpan nomor SO terakhir yang diklik
    // Event klik tombol Proses DP
    $("#jd-table-setujui").on("click", ".proses-dp-btn", function () {
        resetModalDP(); // Reset modal sebelum mengisi data baru

        let rawData = $(this).data("ids");
        let nomor = $(this).data("nomor");
        // Jika nomor SO berubah, reset modal
        if (nomor !== lastNomor) {
            resetModalDP();
        }
        lastNomor = nomor;

        let ids = rawData.split("|");
        let memberUserId = ids[0]; // Ambil member_user_id dari tombol
        $("#modalProsesDP").data("rawData", rawData);
        $("#modalProsesDP").data("nomor", nomor);
        $("#modalProsesDP").data("memberUserId", memberUserId);

        $("#modalProsesDP").modal("show");

        // Muat daftar SO berdasarkan memberUserId dari tombol
        loadSalesOrder(memberUserId);
    });

    // Reset modal saat ditutup
    $("#modalProsesDP").on("hidden.bs.modal", function () {
        resetModalDP();
    });

    // Fungsi untuk mereset modal ke keadaan awal
    function resetModalDP() {
        $("#dpOption").val("sendiri").trigger("change"); // Reset opsi DP
        $("#customerSelectContainer").addClass("d-none"); // Sembunyikan dropdown customer
        $(".js-example-basic-single").val(null).trigger("change"); // Reset Select2 Customer
        $("#salesOrderSelect").val(null).trigger("change"); // Reset Select2 Sales Order
    }

    // Event perubahan pada opsi DP
    $("#dpOption").on("change", function () {
        let selectedOption = $(this).val();

        if (selectedOption === "other") {
            $("#customerSelectContainer").removeClass("d-none");

            $(".js-example-basic-single").select2({
                placeholder: "Pilih Customer",
                ajax: {
                    url: "/admin/member/select2prof2",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items.map((item) => ({
                                id: item.id,
                                text: item.text,
                            })),
                        };
                    },
                },
                dropdownParent: $("#modalProsesDP"),
            });

            // Update daftar SO ketika customer lain dipilih
            $(".js-example-basic-single").on("change", function () {
                let selectedCustomer = $(this).val();
                loadSalesOrder(selectedCustomer);
            });
        } else {
            $("#customerSelectContainer").addClass("d-none");
            $(".js-example-basic-single").val(null).trigger("change"); // Reset Select2 Customer
            loadSalesOrder($("#modalProsesDP").data("memberUserId"));
        }
    });

    // Fungsi untuk memuat daftar SO berdasarkan ID member/customer
    function loadSalesOrder(memberId = null) {
        $("#salesOrderSelect").select2({
            placeholder: "Pilih Nomor SO",
            width: "100%",
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (data) {
                if (!data.id) {
                    return data.text;
                }
                return $(`<span>${data.text}</span>`);
            },
            templateSelection: function (data) {
                return data.text.replace(/<\/?[^>]+(>|$)/g, "");
            },
            ajax: {
                url: "/admin/penjualan/select2nomor_so_fee",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        member_id: memberId,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items.map((item) => ({
                            id: item.id,
                            text: `${item.text}`,
                        })),
                    };
                },
            },
            dropdownParent: $("#modalProsesDP"),
        });
    }

    // Event klik tombol Proses
    $("#prosesDPButton").on("click", function () {
        let rawData = $("#modalProsesDP").data("rawData");
        let nomor = $("#modalProsesDP").data("nomor");

        let dpType = $("#dpOption").val();
        let customerId =
            dpType === "other" ? $(".js-example-basic-single").val() : null;
        let selectedSO = $("#salesOrderSelect").val();

        let idParts = [rawData];

        if (dpType === "other" && customerId) {
            idParts.push(customerId);
        }
        if (selectedSO) {
            idParts.push(selectedSO);
        }

        let data = {
            _token: csrf_token(),
            id: [idParts.join("|")],
            dpType: dpType,
        };

        $.post(baseurl() + "/admin/fee/status/dp", data)
            .done(function (response) {
                if (response.success) {
                    Swal.fire(
                        "Berhasil!",
                        "Proses DP berhasil dilakukan.",
                        "success"
                    );
                    $("#modalProsesDP").modal("hide"); // Tutup modal setelah sukses
                    $("table#jd-table-setujui").DataTable().ajax.reload();
                    $("table#jd-table-dp").DataTable().ajax.reload();
                    refreshDataSUM();
                } else {
                    console.error("Error dari server:", response.message);
                    Swal.fire(
                        "Gagal!",
                        response.message ||
                            "Terjadi kesalahan saat memproses data.",
                        "error"
                    );
                }
            })
            .fail(function (xhr, textStatus, errorThrown) {
                console.error(
                    "AJAX Error:",
                    textStatus,
                    errorThrown,
                    xhr.responseText
                );

                let errorMessage = `Gagal memproses permintaan: ${xhr.status} ${xhr.statusText}`;
                if (xhr.responseText) {
                    errorMessage += `\nDetail: ${xhr.responseText}`;
                }

                Swal.fire("Gagal!", errorMessage, "error");
            });
    });
});
