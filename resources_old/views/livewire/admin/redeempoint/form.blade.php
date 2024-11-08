<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Redeem Point</h5>
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
                            <div wire:ignore>
                                <label for="user_id">Member</label>
                                <select class="select2bind"
                                    name="user_id"
                                    data-url="{{url('admin/member/select2')}}"
                                    data-parent="#modalform"
                                >

                                </select>
                            </div>
                            <input type="hidden" class="@error('user_id') is-invalid @enderror" />

                            @error('user_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <div wire:ignore>
                                <label for="reward_id">Reward</label>
                                <select class="select2bind"
                                        name="reward_id"
                                        data-url="{{url('admin/reward/select2')}}"
                                        data-parent="#modalform"
                                ></select>
                            </div>
                            <input type="hidden" class="@error('reward_id') is-invalid @enderror" />

                            @error('reward_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">

                            <div class="form-group col-md-5">
                                <label for="point">Point</label>
                                <input type="number" id="point" name="point" class="form-control @error('point') is-invalid @enderror" wire:model="point" />
                                @error('point')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="status">Status Pengajuan</label>
                                <select name="status" class="form-select" wire:model="status">
                                    <option value="0">0 - Baru Pengajuan</option>
                                    <option value="1">1 - Proses</option>
                                    <option value="2">2 - Disetujui</option>
                                    <option value="3">3 - ditolak</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" wire:model="notes"></textarea>
                            @error('notes')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <!-- Add other fields (like rt, rw, zip_code) following the same pattern -->
                    </form>


                </div>
                <div class="modal-footer">
                    <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">{{$editform ? 'Simpan Perubahan' : 'Simpan Baru'}}</button>
                </div>
            </div>
        </div>
    </div>

</div>
