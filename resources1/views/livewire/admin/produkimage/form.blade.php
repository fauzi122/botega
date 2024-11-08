<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gambar Produk</h5>
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

                    <form id="forminput" wire:submit>

                        <div class="row mb-3">
                           <div class="col-md-5">
                               <div wire:ignore>
                                   <img src="" id="filefoto" class="mb-3" style="border:0; background-color: #b7caa7; display: block; width: 100%; height: 250px" />
                               </div>
                           </div>
                           <div class="col-md-7">
                               <div class="mb-3">
                                   <label for="filefoto">Foto</label>
                                   <input type="file" id="filefoto" wire:model="filefoto" name="filefoto" class="form-control @error('filefoto') is-invalid @enderror"  />
                                   @error('filefoto')
                                   <span class="text-danger">{{$message}}</span>
                                   @enderror
                               </div>
                               <div class="mb-3">
                                   <label for="switch1">Sebagai gambar utama</label><br/>
                                   <input name="is_primary" value="{{$is_primary}}" {{  $is_primary ? 'checked' : ''  }} type="checkbox" id="switch1" switch="none" />
                                   <label for="switch1" data-on-label="Ya" data-off-label="Tidak"></label>
                               </div>

                           </div>
                        </div>

                        <div class="mb-3">
                            <label for="name">Judul Gambar</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" wire:model="name" />
                            @error('name')
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



                    </form>


                </div>
                <div class="modal-footer">
                    @if($editform)
                        <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onclick="store()" type="button" class="btn btn-primary">Simpan Perubahan</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onclick="store()" type="button" class="btn btn-primary">Simpan Baru</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
