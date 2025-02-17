<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Release Note</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (session()->has('success'))
                        <div class="alert alert-border-left alert-label-icon alert-success alert-dismissible fade show">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-border-left alert-danger alert-label-icon alert-dismissible fade show">
                            {{ session('error') }}
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
                                <label for="judul">Judul</label>
                                <input type="text" id="judul" name="judul"
                                    class="form-control @error('judul') is-invalid @enderror" wire:model="judul" />
                                @error('judul')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="tipe">Tipe</label>
                                <select class="form-select @error('tipe') is-invalid @enderror" id="tipe"
                                    name="tipe" wire:model="tipe">
                                    <option selected>Pilih tipe</option>
                                    <option value="2">Improvement</option>
                                    <option value="1">Bug</option>
                                </select>
                                @error('tipe')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="deskripsi">Keterangan</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    wire:model="deskripsi"></textarea>
                                @error('deskripsi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">Simpan
                        Perubahan</button>
                </div>
            </div>
        </div>
    </div>

</div>
