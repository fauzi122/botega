<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gambar Slider</h5>
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

                    <form id="forminput" wire:submit>

                        <div class="row mb-4">
                            <div class="form-group col-md-5">
                                <div wire:ignore>
                                    <label for="article">Gambar Slider</label>
                                    <input type="file" name="filefoto" class="form-control-file form-control" />
                                </div>
                                <input type="hidden" class="@error('filefoto') is-invalid @enderror" />
                                @error('filefoto')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-7" id="image-preview" wire:ignore>
                                <img style="width: 200px; height: 150px; object-fit: cover" src="" id="img-preview" />
                                <button id='btn-hapus-gambar' style="position: absolute; margin-top: 130px; margin-left: -50px"
                                        class="btn btn-sm btn-danger btn-rounded" title="Hapus gambar"
                                        onclick="return hapusgambar()"><i class="mdi mdi-close"></i></button>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="title">Judul Gambar</label>
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" wire:model="title" />
                            @error('title')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" >Keterangan</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="name" wire:model="description"></textarea>
                            @error('description')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="order">Urutan ke</label>
                            <input id="order" type="number" class="form-control @error('order') is-invalid @enderror" name="order" wire:model="order" />
                            @error('order')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>



                    </form>


                </div>
                <div class="modal-footer">
                    @if($editform)
                        <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:loading.attr="disabled" onclick="store()" type="button" class="btn btn-primary">Simpan Perubahan</button>
                    @else
                        <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:loading.attr="disabled" onclick="store()" type="button" class="btn btn-primary">Simpan Baru</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
