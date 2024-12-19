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
                    <li class="breadcrumb-item active"><a href="{{url('/admin/report/fee-member')}}">Form Laporan</a></li>
                    <li class="breadcrumb-item active">Fee Per Nama Professional</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@php
$totalfee = 0; // Reset total fee
$kategori = [];
$kategoriPercent = [];
$totalPercentRounded = 0;

foreach ($data as $d) {
$totalfee += $d->total_pembayaran; // Gunakan total pembayaran dari iterasi
$kategori[$d->category] = ($kategori[$d->category] ?? 0) + $d->total_pembayaran;
}

foreach ($kategori as $k => $v) {
$percent = ($v / $totalfee) * 100;
$kategoriPercent[$k] = round($percent, 2);
$totalPercentRounded += $kategoriPercent[$k];
}

// Hitung selisih persentase untuk memastikan total 100%
$diffPercent = round(100 - $totalPercentRounded, 2);
if ($diffPercent != 0 && count($kategoriPercent) > 0) {
$firstCategory = array_key_first($kategoriPercent);
$kategoriPercent[$firstCategory] += $diffPercent;
}
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">FEE PER NAMA PROFESSIONAL</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="control-label col-md-2">Nama Professional</label>
                    <label class="control-label col-md-3">: {{$user->first_name}} {{$user->last_name}} ({{$user->id_no}})</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Periode</label>
                    <label class="control-label col-md-3">: {{\Carbon\Carbon::parse($periode_awal)->translatedFormat("d/m/Y") }} - {{\Carbon\Carbon::parse($periode_akhir)->format("d/m/Y")}}</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Total Fee</label>
                    <label class="control-label col-md-3">: IDR {{ number_format($totalfee, 2) }}</label>
                </div>
                <table class="table table-bordered table-centered mb-0 table-hover table-striped">
                    <thead>
                        <tr>
                            <th>TANGGAL</th>
                            <th>NOMOR FEE</th>
                            <th>KATEGORI</th>
                            <th>TOTAL DPP</th>
                            <th>TOTAL FEE</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $kategori = [];
                        @endphp

                        @foreach ($data as $d)
                        <tr>
                            <td>{{ \Illuminate\Support\Carbon::parse( $d->dt_acc )->format("d/m/Y") }}</td>
                            <td>{{ $d->nomor }}</td>
                            <td>{{ $d->category }}</td>
                            <td style="text-align: right">{{ number_format( doubleval( $d->dpp_amount ), 2 ) }}</td>
                            <td style="text-align: right">{{ number_format( doubleval( $d->total_pembayaran ), 2 ) }}</td>
                            @php
                            $percent = doubleval(($d->total_pembayaran / $totalfee) * 100);
                            $percentRounded = ceil($percent * 100) / 100; // Membulatkan ke atas 2 desimal
                            $kategoriPercent[$d->category] = ($kategoriPercent[$d->category] ?? 0) + $percentRounded;
                            $totalPercentRounded += $percentRounded;
                            @endphp
                            <td style="text-align: right">{{ number_format($percentRounded, 2) }}%</td>
                        </tr>
                        @php
                        $kategori[$d->category] = ($kategori[$d->category] ?? 0) + $d->total_pembayaran;
                        @endphp
                        @endforeach

                    </tbody>
                    <tfoot>
                        @php
                        $diffPercent = 100 - $totalPercentRounded; // Hitung selisih persen untuk menjadi 100%
                        if ($diffPercent > 0 && count($kategoriPercent) > 0) {
                        // Tambahkan selisih ke kategori pertama
                        $firstCategory = array_key_first($kategoriPercent);
                        $kategoriPercent[$firstCategory] += $diffPercent;
                        }
                        @endphp
                        @foreach($kategori as $k=>$v)
                        <tr>
                            <td colspan="4">Total Kategori <b>{{$k}}</b></td>
                            <td style="text-align: right">IDR {{ number_format( doubleval( $v ), 2 )  }}</td>
                            <td style="text-align: right">{{ number_format( $kategoriPercent[$k] ?? 0, 2 ) }}%</td>
                        </tr>
                        @endforeach

                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                <form method="post" action="{{url('/admin/report/fee-member/xls')}}">
                    @csrf
                    <input type="hidden" name="member_user_id" value="{{$user->id}}" />
                    <input type="hidden" name="periode_awal" value="{{$periode_awal}}" />
                    <input type="hidden" name="periode_ahir" value="{{$periode_akhir}}" />
                    <button class="btn btn-outline-info btn-rounded w-md"><i class="mdi mdi-file-excel"></i> Download XLS</button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection