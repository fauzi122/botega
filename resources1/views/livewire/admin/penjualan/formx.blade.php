<div>
    <div id="card-form" class="card">
        <div class="card-body">
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

            <form id="form-data" wire:submit.prevent>
                <input type="hidden" name="edit" value="{{$editform}}" />
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="trx_at">Tanggal</label>
                        <input type="date" id="trx_at" name="trx_at" class="form-control @error('trx_at') is-invalid @enderror" wire:model="trx_at" />
                        @error('trx_at')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="invoice_no">No Invoice</label>
                        <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" wire:model="invoice_no" />
                        @error('invoice_no')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class='col-md-5'>
                        @if(!$editform)
                        <div id="pilih-member" style="display: {{$editform ? 'none':'block'}}">
                            <div wire:ignore>
                                <label for="member_user_id">Member</label>
                                <select class="select2bind"
                                        data-parent="#card-form"
                                        data-url="{{url('admin/member/select2')}}"
                                        name="member_user_id" id="member_user_id">
                                    @if($lm != null)
                                        <option selected value="{{$member_user_id}}">{{$lm?->member}} {{$lm?->last_name}} ({{$lm?->no_member}})</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        @else
                            <div >
                                <label for="member_user_id">Member</label>
                                <input type="text"  class="form-control" readonly value="{{$lm->member}} {{$lm->last_name}} ({{$lm->no_member}})" />
                                <input type="hidden" name="member_user_id" readonly value="{{$member_user_id}}" />
                            </div>
                        @endif

                        <input type="hidden" class="@error('member_user_id') is-invalid @enderror"/>
                        @error('member_user_id')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    <div class="col-md-2">
                        <div wire:ignore>
                            <label for="total">Total</label>
                            <input type="text" id="total" name="total" readonly class="money form-control" value="{{intval($total) }}" />
                        </div>
                        <input type="hidden" class=" @error('total') is-invalid @enderror" />
                        @error('total')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-1">
                        <label for="point">Poin</label>
                        <input type="text" id="point" name="point" class="money form-control @error('point') is-invalid @enderror" wire:model="point" />
                        @error('point')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes">Catatan</label>
                    <textarea class=form-control @error('notes') is-invalid @enderror" name="notes"
                              id="notes" wire:model="notes"></textarea>
                    @error('notes')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <h3 id="jenis-level" class="text-info "></h3>

                <div class="row mb-3">
                    <div class="col-md-5">
                        <div wire:ignore>
                            <label>Produk</label>
                            <select class="select2bind" name="product_id"
                                    data-parent="#card-form"
                                    data-url="urlProduk"
                                    data-fnc="formatTextProductSelect2"
                                ></select>
                        </div>
                        <input type="hidden" class="@error('product_id') is-invalid @enderror" />
                        @error('product_id')
                        <span class="text-danger">{{$message}}</span>
                        @enderror

                    </div>

                    <div class="col-md-2" >
                        <div wire:ignore>
                            <label for="sale_price">Harga</label>
                            <input type="text" name="sale_price" class="form-control money" id="sale_price"/>
                        </div>
                        <input type="hidden" class="@error('sale_price') is-invalid @enderror" />
                        @error('sale_price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror

                    </div>

                    <div class="col-md-1">
                       <div wire:ignore>
                           <label for="qty">Qty</label>
                           <input type="number" name="qty" class="form-control" id="qty"/>
                       </div>
                        @error('qty')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-2" wire:ignore>
                        <label for="subtotal">Total</label>
                        <input type="text" name="subtotal" class="form-control money" readonly id="subtotal"/>
                    </div>

                </div>

            </form>

        </div>
        <div class="card-footer">
            @if($editform)
                <button onclick="storedata()" type="button" class="btn btn-primary">Simpan Perubahan</button>
            @else
                <button onclick="storedata()" type="button" class="btn btn-primary">Simpan Baru</button>
            @endif
        </div>
    </div>
</div>
