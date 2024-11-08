<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Peran Akses</h5>
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

                    <form wire:submit.prevent>
                        <div wire:loading.class="hide">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="descriptions">Descriptions</label>
                                <textarea id="descriptions" name="descriptions" class="form-control @error('descriptions') is-invalid @enderror" wire:model="descriptions"></textarea>
                                @error('descriptions')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    <button wire:loading.attr="disabled"  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">{{$editform ? 'Simpan Perubahan' : 'Simpan Baru'}}</button>
                </div>
            </div>
        </div>
    </div>

</div>
