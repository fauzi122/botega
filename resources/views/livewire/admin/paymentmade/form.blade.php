<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fee Pembayaran Sebelumnya</h5>

                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close" >
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



                    <form id="forminput" onsubmit="return false">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3"  >
                                    <label for="no_so">Nomor Sales Order {{$no_so}}</label>
                                    <div wire:ignore>
                                        <select id="no_so" name="no_so"  class="select2bind"
                                                data-url="{{url('admin/penjualan/select2nomor_so')}}"
                                                data-parent="#modalform"
                                        >
                                        </select>
                                    </div>
                                    <input type="hidden" class="@error('transaction_id') is-invalid  @enderror"/>
                                    @error('no_so')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label  >Nomor SJ</label>

                                    <select wire:change="gantiSJ()" name="no_sj" wire:model="no_sj" class="form-select">
                                        <option value="">--Pilih Surat Jalan--</option>
                                        @foreach($listsj as $sj)
                                            <option value="{{$sj->number_sj}}">{{$sj->number_sj}}</option>
                                        @endforeach
                                    </select>
                                    @error('no_sj')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Nomor Invoice</label>

                                    <select name="no_inv" wire:model="no_inv" class="form-select">
                                        <option value="">--Pilih Invoice--</option>
                                        @foreach($listInv as $inv)
                                            <option value="{{$inv->number_in}}">{{$inv->number_in}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="fee_date">Tanggal Pembayaran Fee</label>
                                    <input type="date" id="fee_date" name="fee_date" class="form-control @error('fee_date') is-invalid @enderror" wire:model="fee_date" />
                                    @error('fee_date')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label for="member_user_id">Member Professional</label>
                                    <div wire:ignore>
                                        <select id="member_user_id" name="member_user_id" class="select2bind"
                                                data-url="{{url('admin/member/select2prof')}}"
                                                data-parent="#modalform"
                                        >
                                        </select>
                                    </div>
                                    <input type="hidden" class="@error('member_user_id') is-invalid  @enderror"/>
                                    @error('member_user_id')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3 ">
                                    <div wire:ignore>
                                        <label for="nominal">Nominal</label>
                                        <input type="text" id="nominal" name="nominal" class="money form-control "  />
                                    </div>

                                    <input type="hidden" class="@error('nominal') is-invalid  @enderror"/>
                                    @error('nominal')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </form>


                </div>
                <div wire:loading.class.remove="hide" class="hide" style="width: 100%; justify-content: center">
                    <div class="loader-wrapper">
                        <div class="loader"></div>
                    </div>
                    <div style="display: flex; justify-content: center">
                        Sedang memuat data...
                    </div>
                </div>
                <div class="modal-footer">

                        <button wire:loading.attr="disabled"  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:loading.attr="disabled" onclick="store()" type="button" class="btn btn-primary">{{$editform ? 'Simpan Perubahan' : 'Simpan Baru'}}</button>

                </div>
            </div>
        </div>
    </div>

</div>
