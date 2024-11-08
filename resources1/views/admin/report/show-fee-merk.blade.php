@extends('admin.template')

@section('konten')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Fee Per Merk Produk</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan</a></li>
                        <li class="breadcrumb-item active"><a href="{{url('/admin/report/fee-member')}}">Form Laporan</a></li>
                        <li class="breadcrumb-item active">Fee Per Merk Produk</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    @php
//        $totalfee = doubleval( $data->sum('fee_amount') - $data->sum('pph_amount') );
        $totalfee = doubleval( $data->sum('total_pembayaran'));
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">FEE PER MERK PRODUK</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="control-label col-md-2">Kategori</label>
                        <label class="control-label col-md-4">: {{$kategori->category}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Merk</label>
                        <label class="control-label col-md-8">: {{ implode(", ", $merk) }}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Periode</label>
                        <label class="control-label col-md-3">: {{\Carbon\Carbon::parse($periode_awal)->translatedFormat("d/m/Y") }} - {{\Carbon\Carbon::parse($periode_akhir)->format("d/m/Y")}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Total Fee</label>
                        <label class="control-label col-md-3">:  IDR {{  number_format( $totalfee )  }}</label>
                    </div>

                    <table class="table table-bordered table-centered mb-0 table-hover table-striped">
                        <thead>
                            <tr>
                                <th>TANGGAL</th>
                                <th>NOMOR FEE</th>
                                <th>MERK</th>
                                <th>TOTAL DPP</th>
                                <th>TOTAL FEE - PPH</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $coll = [];
                            @endphp
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse( $d->dt_acc )->format("d/m/Y") }}</td>
                                    <td>{{ $d->nomor }}</td>
                                    <td>{{ $d->merk}} </td>
                                    <td style="text-align: right">{{ number_format( doubleval( $d->dpp_amount ) ) }}</td>
                                    <td  style="text-align: right">{{ number_format( doubleval( $d->total_pembayaran ) ) }}</td>
                                    <td  style="text-align: right">{{ number_format( doubleval( ( ($d->total_pembayaran) / $totalfee) * 100 ),2 ) }}%</td>
                                </tr>
                               @php
                                    $coll[$d->merk] = ($coll[$d->merk] ?? 0) + ($d->total_pembayaran);
                               @endphp
                            @endforeach
                            @php
                               $tmp = [];
                               foreach($coll as $m=>$sum){
                                   $tmp[] =[$m, $sum];
                               }
                               $newsort = \App\Library\Helper::sort($tmp, function($a, $b) {
                                  return $a[1] < $b[1];
                               });
                            @endphp
                            @foreach($newsort as $m=>$dt)
                                <tr>

                                    <td colspan="3">Total Merk <b>{{ $dt[0] }}</b></td>
                                    <td></td>
                                    <td style="text-align: right">{{ number_format( $dt[1] ) }}</td>
                                    <td  style="text-align: right">{{ number_format( ($dt[1] / $totalfee) * 100,2 ) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>


                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <form method="post" action="{{url('/admin/report/fee-merk/xls')}}">
                        @csrf
                        <input type="hidden" name="category_id" value="{{$kategori->id}}" />
                        <input type="hidden" name="periode_awal" value="{{$periode_awal}}" />
                        <input type="hidden" name="periode_ahir" value="{{$periode_akhir}}" />
                        <button class="btn btn-outline-info btn-rounded w-md"><i class="mdi mdi-file-excel"></i> Download XLS</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
