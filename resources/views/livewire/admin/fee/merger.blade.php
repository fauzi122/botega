<div>
    <div wire:ignore.self id="modal-merger" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-sm" role="document" >
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Fee Professional</h5>
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


                        <span>Ada {{$jmlmerger}} data yang akan dimerger, isikan kode merger.</span>
                    <form id="form_data" onsubmit="return false">
                        <div class="form-group mb-3">
                            <label for="kode_merger">Kode Merger</label>
                            <input type="text" id="kode_merger" wire:model="kode_merger" class="form-control @error('kode_merger') is-invalid  @enderror"/>
                            @error('kode_merger')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
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

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click="simpan()" wire:loading.attr="disabled" >Simpan</button>
                </div>

            </div>
        </div>
    </div>
</div>
