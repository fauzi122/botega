<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kategori Produk</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(session()->has('success'))
                        <div class="alert alert-border-left alert-label-icon alert-success alert-dismissible fade show">
                            <i class="mdi mdi-check-all align-middle me-3"></i>
                            {{session('success')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-border-left alert-danger alert-label-icon alert-dismissible fade show">
                            <i class="mdi mdi-alert-outline align-middle me-3"></i>
                            {{session('error')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        </div>
                    @endif

                    <form wire:submit.prevent>
                        <div class="form-group mb-3">
                            <label for="category">Kategori</label>
                            <input type="text" id="category" name="category" class="form-control @error('category') is-invalid @enderror" wire:model="category" />
                            @error('category')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="descriptions">Deskripsi</label>
                            <input type="text" id="descriptions" name="descriptions" class="form-control @error('descriptions') is-invalid @enderror" wire:model="descriptions" />
                            @error('descriptions')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <!-- Add other fields (like rt, rw, zip_code) following the same pattern -->
                    </form>


                </div>
                <div class="modal-footer">
                    @if($editform)
                        <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:click="update" type="button" class="btn btn-primary">Simpan Perubahan</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:click="store" type="button" class="btn btn-primary">Simpan Baru</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
