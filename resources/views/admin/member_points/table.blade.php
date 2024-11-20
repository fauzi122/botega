@extends('admin.template')

@section('konten')
<style>
    #member-detail-table {
        width: 100%;
        /* Pastikan tabel menggunakan 100% dari lebar kontainer */
        table-layout: auto;
        /* Biarkan tabel menyesuaikan lebarnya */
    }

    #member-detail-table th,
    #member-detail-table td {
        white-space: nowrap;
        /* Pastikan tidak ada teks yang menyatu */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Tambahkan ... jika teks terlalu panjang */
        vertical-align: middle;
    }

    #member-detail-table td:first-child {
        white-space: normal;
        /* Nama Barang dapat membungkus teks */
        word-wrap: break-word;
        /* Bungkus teks jika terlalu panjang */
    }
</style>
<div class="row">
    <div class="col-12 mb-3 text-end">
        <button id="reset-points-btn" class="btn btn-danger btn-sm">Reset Poin</button>
        <button id="update-points-btn" class="btn btn-success btn-sm">Perbarui Poin</button>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Member Points</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Member Points</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="member-points-list" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Total Points</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detailLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Ubah ukuran modal menjadi besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detailLabel">Detail Member Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="member-detail-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nomor SO</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Sale Price</th>
                                <th>DPP Amount</th>
                                <th>Retur Qty</th>
                                <th>Amount Retur</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')
<script>
    $(document).ready(function() {
        // DataTables untuk daftar member
        $('#member-points-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.member-points.datasource') }}",
                type: "GET",
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: null,
                    name: 'first_name',
                    render: function(data) {
                        return `${data.first_name} ${data.last_name}`;
                    }
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'points',
                    name: 'points'
                },
                {
                    data: 'id',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-info" onclick="showMemberDetail(${row.id})">Detail</button>`;
                    }
                }
            ],
            pageLength: 10,
            order: [
                [0, 'asc']
            ],
        });
    });
    // Reset Points
    $('#reset-points-btn').click(function() {
        if (confirm('Apakah Anda yakin ingin mereset semua poin?')) {
            $.post("{{ route('admin.member-points.reset') }}", {
                    _token: "{{ csrf_token() }}"
                })
                .done(response => alert(response.message))
                .fail(() => alert('Gagal mereset poin.'));
        }
    });

    // Update Points
    $('#update-points-btn').click(function() {
        if (confirm('Apakah Anda yakin ingin memperbarui poin untuk transaksi dalam 7 hari terakhir?')) {
            $.post("{{ route('admin.member-points.update') }}", {
                    _token: "{{ csrf_token() }}"
                })
                .done(response => alert(response.message))
                .fail(() => alert('Gagal memperbarui poin.'));
        }
    });
    var memberDetailUrl = "{{ route('admin.member-points.details', ':id') }}";

    function showMemberDetail(userId) {
        const url = memberDetailUrl.replace(':id', userId);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    const details = data.details;

                    // Hapus instance DataTables sebelumnya
                    if ($.fn.DataTable.isDataTable("#member-detail-table")) {
                        $('#member-detail-table').DataTable().clear().destroy();
                    }

                    // Kosongkan tabel sebelum menambahkan data baru
                    $('#member-detail-table tbody').empty();

                    // Tambahkan data baru ke tabel
                    let rows = '';
                    details.forEach(row => {
                        rows += `
                            <tr>
                                <td>${row.nomor_so}</td>
                                <td>${row.nama_barang}</td>
                                <td>${row.qty}</td>
                                <td>${parseFloat(row.sale_price).toFixed(0)}</td>
                                <td>${parseFloat(row.dpp_amount).toFixed(0)}</td>
                                <td>${row.retur_qty || 0}</td> <!-- Ganti null menjadi 0 -->
                                <td>${parseFloat(row.amount_retur || 0).toFixed(0)}</td>
                                <td>${parseFloat(row.points || 0).toFixed(0)}</td>
                            </tr>`;
                    });

                    $('#member-detail-table tbody').html(rows);

                    // Aktifkan DataTables dengan data baru
                    $('#member-detail-table').DataTable({
                        pageLength: 10,
                        autoWidth: false,
                        scrollX: true, // Untuk scroll horizontal
                        order: [
                            [0, 'asc']
                        ],
                    });

                    // Tampilkan modal setelah data berhasil dimuat
                    $('#modal-detail').modal('show');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error fetching data:", error);
                alert('Failed to load member details.');
            });
    }

    // Sesuaikan lebar kolom tabel setelah modal ditampilkan
    $('#modal-detail').on('shown.bs.modal', function() {
        $('#member-detail-table').DataTable().columns.adjust();
    });
</script>

@endpush