@extends('admin.template')

@section('konten')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">History Level</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Member Level</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="member-spent-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th> <!-- Kolom Nomor Urut -->
                                <th>ID Customer</th>
                                <th>Nama</th>
                                <th>Tahun</th>
                                <th>Total Transaksi</th> <!-- Kolom Total Spent -->
                                <th>Level</th>
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
        $('#member-spent-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.member-spent.datasource') }}",
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, // Nomor Urut
                {
                    data: 'id_no',
                    name: 'users.id_no'
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                }, // Kolom full_name untuk pencarian
                {
                    data: 'tahun',
                    name: 'member_spent.tahun'
                },
                {
                    data: 'total_spent',
                    name: 'member_spent.total_spent',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ')
                }, // Format Total Spent
                {
                    data: 'level_name',
                    name: 'level_member.level_name'
                },
            ],

            pageLength: 10,
            order: [
                [1, 'asc']
            ],
        });

    });
</script>
@endpush