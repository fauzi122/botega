<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hak Akses</h5>
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



                        <div >

                            <form wire:submit.prevent>

                                <div class="form-group mb-3">
                                    <label for="name">Nama Peran</label>
                                    <input type="text" class="form-control" readonly value="{{$role?->name}}" />
                                    @error('role_id')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="access_right_id">Hak Akses</label>
                                    <div wire:ignore>
                                        <select name="access_right_id"
                                                data-url="{{url('admin/role-access-right/select2')}}"
                                                data-parent="#modalform"
                                                class="select2bind"
                                        ></select>
                                    </div>
                                    <input type="hidden" class="@error('access_right_id') is-invalid @enderror">
                                    @error('access_right_id')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>Mode Akses</label>
                                    <div wire:ignore>
                                        <input type="checkbox" id="grant" switch="none" >
                                        <label for="grant" data-on-label="Ya" data-off-label="Tidak"></label>
                                    </div>
                                </div>

                            </form>

                        </div>

                </div>
                <div class="modal-footer">
                    <button wire:loading.attr="disabled"  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">{{$editform ? 'Simpan Perubahan' : 'Simpan Baru'}}</button>
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
