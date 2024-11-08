<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reward</h5>
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

                        <form id="form_data" wire:submit.prevent="submitForm">
                            <div wire:loading.class="hide">

                                <div class="form-group mb-3">
                                    <label for="code">Kode</label>
                                    <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror" wire:model="code" />
                                    @error('code')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="name">Nama Penghargaan</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="descriptions">Keterangan</label>
                                    <textarea id="descriptions" name="descriptions" class="form-control @error('descriptions') is-invalid @enderror" wire:model="descriptions"></textarea>
                                    @error('descriptions')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3" wire:ignore>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label for="path_image">Gambar Reward</label>
                                            <input type="file" accept="image/*" id=" let f = await getBase64File("input[name=filefoto]");
                                                   if(f !== null){
                                                   wire.set('filefoto', f);
                                            }" name="file_image" class="form-control @error('file_image') is-invalid @enderror" ></input>
                                            @error('file_image')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-5">
                                            <img src="" id="img-preview" style="width:100%; height:200px; object-fit: cover" />
                                            <button id="btnhapus" class="btn btn-sm btn-rounded btn-danger"  onclick="return false"><i class="fa fa-trash"></i> Hapus</button>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="point">Point</label>
                                        <input type="number" id="point" name="point" class="form-control @error('point') is-invalid @enderror" wire:model="point" />
                                        @error('point')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-5">
                                        <label for="expired_at">Tanggal Kadalaruasa</label>
                                        <input type="date" id="expired_at" name="expired_at" class="form-control @error('expired_at') is-invalid @enderror" wire:model="expired_at" />
                                        @error('expired_at')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </form>


                </div>
                <div class="modal-footer">
                    <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

</div>
