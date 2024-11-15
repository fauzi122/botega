<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Singkron Data</h5>
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

                    <div wire:loading.class.remove="hide" class="hide" style="width: 100%; justify-content: center">
                        <div class="loader-wrapper">
                            <div class="loader"></div>
                        </div>
                        <div style="display: flex; justify-content: center">
                            Sedang memuat data...
                        </div>
                    </div>

                    <form id="form_data" wire:submit.prevent>

                        <div wire:loading.class="hide">
                            <div class="alert alert-label-icon alert-border-left  alert-warning fade show ">
                                <p style="text-align: justify;">Proses akan dikerjakan di background service. Ini akan menyebabkan beberapa proses belakang layar akan menjadi sibuk.
                                    Hasil tidak dapat langsung diketahui seketika. Untuk mengetahui hasil singkron, cek kembali beberapa menit kemudian setelah klik Mulai Singkronkan.</p>

                            </div>
                            <div class="form-group mb-3">
                                <label for="tgl1">Tanggal Transaksi</label>
                                <input type="date" id="tgl1" name="tgl1" class="form-control @error('tgl1') is-invalid @enderror" wire:model="tgl1" />
                                @error('tgl1')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="nomor_so">Nomor Sales Order (SO)</label>
                                <input type="text" id="nomor_so" name="nomor_so" class="form-control @error('nomor_so') is-invalid @enderror" wire:model="nomor_so" />
                                @error('nomor_so')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    <button wire:loading.attr="disabled" onclick="proses()" type="button" class="btn btn-primary">Mulai Singkronkan</button>

                </div>
            </div>
        </div>
    </div>

</div>
