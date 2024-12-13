@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Promo</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Promo</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- <div class="card-header">
                                <h4 class="card-title">Promo</h4>
                                <p class="card-title-desc">Promo digunakan untuk mengelola harga produk berdasarkan level member.
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md">
                    <table id="jd-table"
                        width="100%"
                        data-datasource="{{url('admin/promo/data-source/')}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>LEVEL</th>
                                <th>KATEGORI</th>
                                <th>LIMIT TRANSAKSI</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#jd-table').dataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'copy', 'excel', 'pdf', 'print',


            ],
            initComplete: function(settings, json) {
                $(".dt-button").addClass("btn btn-sm btn-primary");
                $(".dt-button").removeClass("dt-button");
                // $('div.dt-buttons').css('width', '100%');
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: $('table#jd-table').data('datasource'),
                method: 'GET'
            },
            order: [
                [1, 'asc']
            ],
            columns: [{
                    data: 'id',
                    sortable: false,
                    width: '20px',
                    target: 0,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                    }
                },

                {
                    data: 'level_name',
                    render: (data, type, row, meta) => {
                        return `<a href="{{url('admin/promo')}}/${row['id']}"><i class="mdi mdi-pencil"></i> ${data} </a>`
                    }
                },
                {
                    data: 'kategori'
                },
                {
                    data: 'limit_transaction',
                    render: function(data, type, row, meta) {
                        let formatter = new Intl.NumberFormat('id-ID', {
                            style: 'decimal',
                            maximumFractionDigits: 2 // Jumlah desimal maksimal
                        });

                        if (Math.ceil(data) === -1) {
                            return 'Lebih dari 1 Milyar';
                        } else if (Math.ceil(data) === 0) {
                            return 'Tidak terbatas';
                        }
                        let formattedNumber = formatter.format(data);
                        return formattedNumber;
                    }
                },
                {
                    data: 'publish',
                    render: function(data, type, row, meta) {
                        return `<i class="${data===1?"text-success fa fa-check-circle":"text-danger fa fa-circle"}"></i> ` + (data === 1 ? 'Aktif' : 'Tidak Aktif');
                    }
                },
            ]
        });
    });
</script>
@endpush