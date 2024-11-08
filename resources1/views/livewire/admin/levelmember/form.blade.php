<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Level Member</h5>
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

                    <form wire:submit.prevent >
                        <div>
                            <div class="form-group">
                                <label for="level_name">Nama Level</label>
                                <input type="text" id="level_name" name="nama_level" class="form-control @error('level_name') is-invalid @enderror" wire:model="level_name" />
                                @error('level_name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="level">Level</label>
                                <select class="form-select @error('level') is-invalid @enderror" name="level" id="level" wire:model="level" >
                                    @for($i=1;$i<10;$i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                @error('level')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror" wire:model="kategori">
                                    <option value="">Pilih Kategori</option>
                                    <option value="MEMBER PRO">Member Pro</option>
                                    <option value="UMUM">Umum</option>
                                </select>
                                @error('kategori')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control @error('description') is-invalid @enderror" wire:model="description"></textarea>
                                @error('description')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="limit_transaction">Limit Transaction</label>
                                <input type="text" id="limit_transaction" name="limit_transaction" class="form-control @error('limit_transaction') is-invalid @enderror" wire:model="limit_transaction" />
                                @error('limit_transaction')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <label for="publish" class="form-check-label">Publish</label>
                                    <div class="square-switch">
                                        <input type="checkbox" onchange="wire.set('publish', this.checked, false )" id="publish" switch="info" {{$publish ? 'checked' : ''}} />
                                        <label for="publish" data-on-label="Yes"
                                               data-off-label="No"></label>
                                    </div>
                                </div>
                                @error('publish')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>


                    </form>


                </div>
                <div class="modal-footer">
                    @if($editform)
                        <button wire:loading.attr='disabled'  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:click="update" type="button" class="btn btn-primary">Simpan Perubahan</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:click="store" wire:loading.attr='disabled' type="button" class="btn btn-primary">Simpan Baru</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
