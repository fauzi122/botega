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

                    <form wire:submit.prevent>
                        <div class="form-group">
                            <label>Level Name</label>
                            <input type="text" name="level_name" class="form-control  @error('level_name') is-invalid @enderror" wire:model="level_name" />
                            @error('level_name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Level</label>
                            <input type="number" name="level" class="form-control  @error('level') is-invalid @enderror" wire:model="level" />
                            @error('level')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Aktif</label>
                            <select name="aktif" wire:model="publish" class="form-control  @error('publish') is-invalid @enderror">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                            @error('aktif')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
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
