<div>
    <style>

    </style>
    <div wire:ignore.self id="modalformDetail" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-xxl" role="document" >
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Rincian Fee Professional</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                        <div >

                            <div class="col-md-12 table-responsive table-responsive-md m-t-40">
                                @if($fee_professional->count() <= 0)

                                @else
                                    <h3>Fee Professional</h3>
                                    @if($member != null)
                                        <div class="form-group">
                                            <label class="col-md-1">Nama</label><span  class="col-md-3">{{$member->first_name}} {{$member->last_name}} ({{  $member->id_no }})</span>
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-md-1">NPWP</label><span  class="col-md-3">{{$member->npwp}} </span>
                                        </div>
                                    @endif
                                    <table class="table table-hover table-bordered table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <th style="width:40px">NO</th>
                                            <th>INVOICE NO</th>
                                            <th>TANGGAL INVOICE</th>
                                            <th>NOMOR SO</th>
                                            <th>NOMOR SJ</th>
                                            <th>CUSTOMER</th>
                                            <th>BARANG</th>
                                            <th>QTY</th>
                                            <th>HARGA SATUAN</th>
                                            <th>DISKON</th>
                                            <th>TOTAL PENJUALAN</th>
                                            <th>DPP PENJUALAN</th>
                                            <th>FEE </th>
                                            <th>PPH </th>
                                            <th>TOTAL PEMBAYARAN</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $no = 1;
                                            $total_fee = 0;
                                            $total_pph = 0;
                                            $total = 0;
                                            $totaldiskon = 0;
                                            $total_penjualan = 0;
                                            $total_dpppenjualan = 0;
                                        @endphp
                                        @foreach($fee_professional as $d)
                                            <tr>
                                                <td> {{$no++}} </td>
                                                <td> {{$d->invoice_number}} </td>
                                                <td> {{$d->invoice_date}} </td>
                                                <td> {{$d->nomor_so}} </td>
                                                <td> {{$d->sj_number}} </td>
                                                <td> {{$d->customer}} </td>
                                                <td> {{$d->product}} </td>
                                                <td style="text-align: right; width: 100px"> {{ number_format($d->pqty,2)  }} </td>
                                                <td style="text-align: right; width: 100px"> {{ number_format($d->sale_price)  }} </td>
                                                <td style="text-align: right; width: 100px"> {{ number_format($d->discount)  }} </td>
                                                <td style="text-align: right; width: 100px"> {{ number_format($d->total_price)  }} </td>
                                                <td style="text-align: right; width: 100px"> {{ number_format($d->dpp_amount)  }} </td>
                                                <td style="text-align: right">{{ number_format($d->fee_amount)  }} ({{$d->fee_percent}}%)</td>
                                                <td style="text-align: right">{{ number_format($d->pph_amount)  }} ({{$d->pph_percent}}%)</td>
                                                <td style="text-align: right">{{ number_format($d->total_pembayaran)  }}<br/>({{ $d->percentage_fee  }}%)</td>
                                            </tr>
                                            @php
                                                $totaldiskon += $d->discount;
                                                $total_fee += $d->fee_amount;
                                                $total_pph += $d->pph_amount;
                                                $total += $d->total_pembayaran;
                                                $total_penjualan += $d->total_price;
                                                $total_dpppenjualan += $d->dpp_amount;;
                                            @endphp
                                        @endforeach
                                        @php
                                            $totalfpm = 0;
                                        @endphp
                                        @foreach($fee_payment_made as $fpm)
                                            <tr>
                                                <td> {{$no++}} </td>
                                                <td> {{$fpm->no_inv}} </td>
                                                <td>  </td>
                                                <td> {{$fpm->no_so}} </td>
                                                <td> {{$fpm->no_sj}} </td>
                                                <td colspan="9"> Pembayaran fee yang telah dilakukan {{ $fpm->fee_date  }} </td>

                                                <td style="text-align: right">{{ number_format($fpm->nominal)  }}</td>
                                            </tr>
                                            @php
                                                $totalfpm += $fpm->nominal;
                                            @endphp
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            {!! str_repeat('<th></th>', 7) !!}
                                                <th colspan="2">Total</th>
                                                <th  style="text-align: right;">{{ number_format($totaldiskon)  }}</th>
                                                <th  style="text-align: right;">{{ number_format($total_penjualan)  }}</th>
                                                <th  style="text-align: right;">{{ number_format($total_dpppenjualan)  }}</th>
                                                <th  style="text-align: right;">{{ number_format($total_fee)  }}</th>
                                                <th  style="text-align: right;">{{ number_format($total_pph)  }}</th>
                                                <th  style="text-align: right;">{{ number_format($total-$totalfpm)  }}</th>
                                            </th>
                                        </tfoot>
                                    </table>
                                    <input type="hidden" class="@error('detail_transaction_id') is-invalid  @enderror"/>
                                    @error('detail_transaction_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>
                        </div>


                    <div wire:loading.class.remove="hide" class="hide" style="width: 100%; justify-content: center">
                        <div class="loader-wrapper">
                            <div class="loader"></div>
                        </div>
                        <div style="display: flex; justify-content: center">
                            Sedang memuat data...
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
