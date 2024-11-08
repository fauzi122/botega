@extends('admin.template')

@section('konten')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Fee Per Nama Professional</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan</a></li>
                        <li class="breadcrumb-item active">Form Laporan</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div id="card-report" class="card">
                <div class="card-header">
                    <h4 class="card-title">Fee Per Nama Profesional</h4>
                    <p class="card-title-desc">
                        Tentukan member dan periode yang ingin dilihat:
                    </p>
                </div>
                <div class="card-body">
                   <div class="col-md-4">
                       <form id="form-report" method="post" action="{{ url("/admin/report/fee-member/show")  }}">
                           @csrf
                           <div class="form-group mb-3">
                               <label class="control-label">Pilih Member</label>
                               <select class="form-control select2bind" name="users_id" required
                                data-parent="#card-report"
                                data-url="{{ url("/admin/member/select2prof") }}"
                               >
                               </select>
                           </div>

                           <div class="form-group mb-3">
                               <label class="control-label">Pilih Periode</label>
                               <div class="row">
                                   <div class="col-md-6">
                                       <input type="date" class="form-control" name="periode_awal" required>
                                   </div>
                                   <div class="col-md-6">
                                       <input type="date" class="form-control" name="periode_akhir" required>
                                   </div>
                               </div>
                           </div>


                       </form>
                   </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-4">
                            <button type="submit" form="form-report" class="btn btn-outline-info btn-rounded w-md">Tampilkan Laporan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("DOMContentLoaded", function () {
            select2bind();
            $("input[name=periode_awal]").on("change", function () {
                const periode_awal = $(this).val();
                $("input[name=periode_akhir]").prop("min", periode_awal);
            });
        });
    </script>
@endsection
