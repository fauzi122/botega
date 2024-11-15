<div>

    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-xxl" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Fee Professional</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(session()->has('success'))
                    <div class="alert alert-border-left alert-label-icon alert-success alert-dismissible fade show">
                        {{session('success')}}
                    </div>
                    @endif
                    @if(session()->has('error'))
                    <div class="alert alert-border-left alert-danger alert-label-icon alert-dismissible fade show">
                        {{session('error')}}
                    </div>
                    @endif


                    <form id="form_data" onsubmit="return false">
                        <div>
                            <div class="row mb-3 ">
                                <div class="col-md-5">
                                    <div class="form-group mb-3">
                                        <label for="member_user_id">Member Professional</label>
                                        <div wire:ignore>
                                            <select id="member_user_id" class="select2bind"
                                                data-url="{{url('admin/member/select2prof')}}"
                                                data-parent="#modalform">
                                            </select>
                                        </div>
                                        <input type="hidden" class="@error('member_user_id') is-invalid  @enderror" />
                                        @error('member_user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group ">
                                        <label for="nomor_so">Nomor Sales Order </label>
                                        <div wire:ignore>
                                            <select id="nomor_so" class="select2bind"
                                                data-url="{{url('admin/penjualan/select2nomor_so')}}"
                                                data-parent="#modalform">
                                            </select>
                                        </div>
                                        <input type="hidden" class="@error('transaction_id') is-invalid  @enderror" />
                                        @error('transaction_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group mb-3">
                                        <div class="row">
                                            <label class="col-md-3">Nomor SJ</label>
                                            <div class="col-md-5">
                                                <select wire:change="gantiSJ()" name="no_sj" wire:model="nosj" class="form-select">
                                                    <option value="">[ Semua Surat Jalan ]</option>
                                                    @foreach($listsj as $sj)
                                                    <option value="{{$sj->id}}">{{$sj->history_number}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="form-group mb-3">
                                      <div class="row">
                                          <label class="col-md-3">Nomor SO</label>
                                          <div class="col-md-5"><input type="text" id="noso" class="form-control" disabled /> </div>
                                      </div>
                                   </div>--}}
                                    <div class="form-group mb-3">
                                        <div class="row">
                                            <label class="col-md-3">Customer</label>
                                            <div class="col-md-5"><input type="text" id="customer" class="form-control" disabled /> </div>
                                        </div>
                                    </div>

                                    {{--<div class="form-group mb-3">
                                       <div class="row">
                                           <label class="col-md-3">Nomor Fee</label>
                                           <div class="col-md-5"><input type="text" id="fee_nomor" class="form-control" disabled wire:model="fee_nomor" /> </div>
                                       </div>--}}
                                </div>
                            </div>

                            <div class="col-md-12 table-responsive table-responsive-md m-t-40">

                                @if($detail_transactions->count() <= 0)

                                    @else
                                    <table class="table table-hover table-bordered table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">NO</th>
                                            <th>BARANG</th>
                                            @if($modeData == 2)
                                            <th>INVOICE NO</th>
                                            <th>SJ NO</th>
                                            @endif
                                            <th>HARGA SATUAN</th>
                                            <th>QTY</th>
                                            <th>DISKON</th>
                                            <th>TOTAL PENJUALAN</th>
                                            <th>DPP PENJUALAN</th>
                                            <th style="width:100px">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $no = 1;
                                        @endphp
                                        @foreach($detail_transactions as $d)

                                        <tr>
                                            <td>{{$no++}}</td>
                                            <td>{{$d->name}}<br /><small class="text-muted">({{$d->kode}})</small>
                                                @if( $d->kode == '')
                                                <a wire:loading.class="hide" class="btn btn-sm btn-info" onclick="javascript:void(0)" wire:click="refreshInfoProduct"> refresh {{$noso}} </a>
                                                @endif
                                            </td>
                                            @if($modeData == 2)
                                            <td style="text-align: center">{{ $d->number_in }}
                                                @if($d->retur_no != null)
                                                <br />
                                                <span style="color: #ca433a; font-style: italic;">{{ $d->retur_no }}</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center">{{ $d->number_sj }}</td>

                                            @endif

                                            <td style="text-align: right">{{ number_format($d->sale_price,2)  }}</td>
                                            <td style="text-align: right">{{ number_format($d->qty,2)  }}
                                                @if($d->retur_no != null)
                                                <br />
                                                <span style="color: #ca433a; font-style: italic;">{{ number_format($d->retur_qty,2) }}</span>
                                                @endif
                                            </td>
                                            <td style="text-align: right">{{ number_format($d->discount,2)  }}</td>
                                            <td style="text-align: right">{{ number_format($d->total_price,2)  }}</td>
                                            <td style="text-align: right">{{ number_format($d->dpp_amount,2)  }}</td>
                                            <td>

                                                @php
                                                // $fieldname = $d?->type == 'DD' ? 'detail_delivery_order_id' : 'detail_transactions_id';
                                                // $c = \App\Models\ClaimItemTransactionModel::query()
                                                // ->where($fieldname, $d?->id)->count();
                                                // if($c == 0 && $d?->type == 'DD'){
                                                // $c = \App\Models\ClaimItemTransactionModel::query()
                                                // ->where('detail_transactions_id', $d?->detail_transaction_id)->count();
                                                // }

                                                $xid = $d?->type == 'DD' ? $d?->detail_transaction_id : $d?->id ;
                                                $c = \App\Models\ClaimItemTransactionModel::query()
                                                ->where('detail_transactions_id', $xid)->count();
                                                if($c > 0){
                                                @endphp

                                                <small onclick="cekDetailInfo({{$xid}})" class="pointer badge badge-soft-warning">{{$c}} Terproses</small>
                                                @php
                                                }
                                                @endphp

                                                <button wire:loading.attr="disabled" wire:click="addItemForFee({{$d->id}}, '{{$d?->type ?? ''}}')" class="btn btn-sm btn-rounded btn-info"><i class="mdi mdi-plus-circle"></i></button>


                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                    <input type="hidden" class="@error('detail_transaction_id') is-invalid  @enderror" />
                                    @error('detail_transaction_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @endif
                            </div>

                            <div class="col-md-12 table-responsive table-responsive-md m-t-40">

                                @if($fee_professional->count() <= 0)

                                    @else
                                    <h3>Fee Professional</h3>
                                    @if($member != null)
                                    <div class="form-group">
                                        <label class="col-md-1">Nama</label><span class="col-md-3">{{$member->first_name}} {{$member->last_name}} ({{ $member->id_no }})</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-1">NPWP</label><span class="col-md-3">{{$member->npwp}} </span>
                                    </div>
                                    @endif
                                    @php
                                    $pengurang = 0;
                                    @endphp
                                    <table style="width:2000px" class="table table-hover table-bordered table-striped table-responsive ">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width:40px">NO</th>
                                                <th style="text-align: center;width:200px">INVOICE NO</th>
                                                <th style="text-align: center;">TANGGAL INVOICE</th>
                                                <th style="text-align: center;">NOMOR SO</th>
                                                <th style="text-align: center;width:200px">NOMOR SJ</th>
                                                <th style="text-align: center;width:300px">CUSTOMER</th>
                                                <th style="text-align: center;width:300px">BARANG</th>
                                                <th style="text-align: center;">QTY</th>
                                                <th style="text-align: center;">HARGA SATUAN</th>
                                                <th style="text-align: center;">DISKON</th>
                                                <th style="text-align: center;">TOTAL PENJUALAN</th>
                                                <th style="text-align: center;">DPP PENJUALAN</th>
                                                <th style="text-align: center;">FEE (%)</th>
                                                <th style="text-align: center;">FEE (IDR)</th>
                                                <th style="text-align: center;">PPH</th>
                                                <th style="text-align: center; width:80px">(%) BAYAR</th>
                                                <th style="text-align: center;">TOTAL BAYAR</th>
                                                <th style="text-align: center;width:50px">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $no = 1;
                                            $total = 0;
                                            $totalpph = 0;
                                            $totaljual = 0;
                                            $totalfee = 0;
                                            $totaldpp = 0;
                                            $totaldiskon = 0;
                                            $cacheRetur = [];
                                            @endphp
                                            @foreach($fee_professional as $d)

                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>
                                                    {{$d->invoice_number}}
                                                    {{-- @php
                                                        $inv = \App\Models\ProsesHistoryModel::query()
                                                                    ->where('transactions_id', $d->transaction_id)
                                                                   ->whereRaw('LEFT(history_number,2)=?', ['IN'])->get
                                                    @endphp-
                                                    <select wire:change="changeInvoice({{$d->id}}, $event.target.value)" class="form-select" wire:model="d.proses_history_invoice_id">
                                                    @foreach( $inv as $ph )
                                                    <option {{$d->proses_history_invoice_id == $ph->id ? 'selected' : ''}} value="{{$ph->id}}">{{$ph->history_number}} </option>
                                                    @endforeach
                                                    </select>--}}
                                                </td>
                                                <td>{{$d->invoice_date}}</td>
                                                <td>{{$d->nomor_so}}
                                                    @if($d->retur_no != null)
                                                    <br />
                                                    <span style="color: #ca3c2c; font-style: italic">{{$d->retur_no}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $d->sj_number }}
                                                    {{-- @php
                                                        $inv = \App\Models\ProsesHistoryModel::query()
                                                                    ->where('transactions_id', $d->transaction_id)
                                                                    ->whereRaw('LEFT(history_number,2)=?', ['SJ'])->get();

                                                    @endphp
                                                    <select wire:change="changeSJ({{$d->id}}, $event.target.value)" class="form-select" wire:model="d.proses_history_nomor_sj">
                                                    @foreach( $inv as $ph )
                                                    <option {{$d->proses_history_nomor_sj == $ph->id ? 'selected' : ''}} value="{{$ph->id}}">{{$ph->history_number}} </option>
                                                    @endforeach
                                                    </select>--}}
                                                </td>
                                                <td>{{$d->customer}}</td>
                                                <td>{{$d->product}}</td>

                                                <td style="text-align: right">{{ number_format($d->pqty,2)  }}
                                                    @if($d->retur_no != null)
                                                    <br />
                                                    <span style="font-style: italic;color:#ca3c2c">{{ $d->retur_qty  }}</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: right">{{ number_format($d->sale_price,2)  }}</td>
                                                <td style="text-align: right">{{ number_format($d->discount,2)  }}</td>
                                                <td style="text-align: right">{{ number_format($d->total_price,2)  }}</td>
                                                <td style="text-align: right">{{ number_format($d->dpp_amount,2)  }}
                                                    @if($d->retur_no != null)
                                                    @php
                                                    $dppsatuan = ($d->dpp_amount / ($d->pqty - $d->retur_qty) );
                                                    $dpptotalakhir = ($dppsatuan * $d->pqty);
                                                    $dppretur = $dppsatuan * $d->retur_qty;
                                                    @endphp
                                                    <br />
                                                    <span style="font-style: italic;color:#ca3c2c">{{ number_format($dppretur,2)  }}</span>

                                                    <br />
                                                    <span style="font-style: italic;color:#1622ca">{{ number_format($dpptotalakhir,2)  }}</span>

                                                    @endif
                                                </td>
                                                <td style="text-align: right; width: 100px">
                                                    <input type="text" class="form-control" wire:change="ubahFee({{$d->id}}, $event.target.value)" value="{{$d->fee_percent}}" />
                                                </td>
                                                <td style="text-align: right">{{ number_format($d->fee_amount,2)  }}</td>
                                                <td style="text-align: right">{{ number_format($d->pph_amount,2)  }} ({{$d->pph_percent}}%)</td>
                                                <td>
                                                    @if($d->num_split == 2)
                                                    {{$d->percentage_fee}}
                                                    @else
                                                    <input type="text" class="form-control" wire:change="ubahPercentPaidFee({{$d->id}}, $event.target.value)" value="{{$d->percentage_fee ?? 100}}" />
                                                    @endif
                                                </td>
                                                <td style="text-align: right">{{ number_format($d->total_pembayaran,2)  }}</td>
                                                <td>
                                                    @if($d->dt_pengajuan == null)
                                                    <button wire:click="hapusFee({{$d}})" class="btn btn-sm btn-rounded btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                                    @endif
                                                </td>
                                            </tr>

                                            @php
                                            $total += $d->total_pembayaran;
                                            $totalpph += $d->pph_amount;
                                            $totaljual += $d->total_price;
                                            $totalfee += $d->fee_amount;
                                            $totaldpp += $d->dpp_amount;
                                            $totaldiskon += $d->discount;
                                            @endphp
                                            @endforeach

                                            @if($paymentMade->count() > 0)
                                            @php
                                            $pengurang = 0;
                                            @endphp
                                            @foreach($paymentMade as $k)
                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>{{$k->no_inv}}</td>
                                                <td></td>
                                                <td>{{$k->no_so}}</td>
                                                <td>{{$k->no_sj}}</td>
                                                <td colspan="11">{{ ($k->keterangan == null ? 'Pembayaran Fee yang Telah dilakukan pada '.$k->fee_date : $k->keterangan)  }}</td>
                                                <td style="text-align: right">({{ number_format($k->nominal - $k->nominal_hutang)  }})</td>
                                                <td></td>
                                            </tr>
                                            @php
                                            $pengurang += ($k->nominal - $k->nominal_hutang);
                                            @endphp
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="9">TOTAL</th>
                                                <th>{{ number_format($totaldiskon)  }}</th>
                                                <th>{{ number_format($totaljual)  }}</th>
                                                <th>{{ number_format($totaldpp)  }}</th>
                                                <th></th>
                                                <th>{{ number_format($totalfee)  }}</th>
                                                <th>{{ number_format($totalpph)  }}</th>
                                                <th></th>
                                                <th>{{ number_format($total - $pengurang)  }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <input type="hidden" class="@error('detail_transaction_id') is-invalid  @enderror" />
                                    @error('detail_transaction_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @endif
                            </div>
                        </div>

                    </form>


                    <div wire:loading.class.remove="hide" class="hide" style="width: 100%; justify-content: center">
                        <div class="loader-wrapper">
                            <div class="loader"></div>
                        </div>
                        <div style="display: flex; justify-content: center">
                            Sedang memuat data...
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">--}}
                {{-- <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
                {{-- <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">{{  $editform ? 'Simpan Perubahan' : 'Simpan Baru'  }}</button>--}}

                {{-- </div>--}}
            </div>
        </div>
    </div>
    <style>
        .hide {
            display: none;
        }

        .pointer {
            cursor: pointer;
        }
    </style>

    <script>
        function cekDetailInfo(id) {
            wire.cekDetailTerproses(id).then((ee) => {
                Swal.fire({
                    title: 'Informasi Terproses',
                    text: ee,
                    type: 'info',
                });
            });

        }
    </script>
</div>