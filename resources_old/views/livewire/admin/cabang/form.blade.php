<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cabang</h5>
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

                    <form wire:submit.prevent>
                        <div class="form-group">
                            <label for="nama">Nama Cabang</label>
                            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" wire:model="nama" />
                            @error('nama')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>


                        <div class="form-group mt-3">
                            <label for="aktif">Aktif</label>
                            <select id="aktif" name="aktif" class="form-control @error('aktif') is-invalid @enderror" wire:model="aktif">
                                <option value="0">Tidak Aktif</option>
                                <option value="1">Aktif</option>
                            </select>
                            @error('aktif')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    @if($editform)
                        <button wire:loading.attr='disabled'  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:loading.attr='disabled' wire:click="update" type="button" class="btn btn-primary">Simpan Perubahan</button>
                    @else
                        <button wire:loading.attr='disabled' type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:loading.attr='disabled' wire:click="store" wire:loading.attr='disabled' type="button" class="btn btn-primary">Simpan Baru</button>
                    @endif
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
