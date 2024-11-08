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

                    <form id="form_data" wire:submit.prevent>
                        <div class="form-group mb-3">
                            <label for="category">Kategori</label>
                            <input type="text" id="category" name="category" class="form-control @error('category') is-invalid @enderror" wire:model="category" />
                            @error('category')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="publish">Publish</label>
                            <div wire:ignore>
                                <input type="checkbox" name="publish" id="publish" switch="none" >
                                <label for="publish" data-on-label="Ya" data-off-label="Tidak"></label>
                            </div>
                        </div>

                    </form>


                </div>
                <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onclick="save()" type="button" class="btn btn-primary">{{$editform ? 'Simpan Perubahan' : 'Simpan Baru'}}</button>
                </div>
            </div>
        </div>
    </div>

</div>
