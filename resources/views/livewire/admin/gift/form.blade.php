<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gift</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (session()->has('success'))
                        <div class="alert alert-border-left alert-label-icon alert-success alert-dismissible fade show">
                            <i class="mdi mdi-check-all align-middle me-3"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>

                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-border-left alert-danger alert-label-icon alert-dismissible fade show">
                            <i class="mdi mdi-alert-outline align-middle me-3"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>

                        </div>
                    @endif

                    <form id="form_data" wire:submit.prevent>
                        <div class="form-group mb-3">
                            <label for="user_id">Member</label>
                            <div wire:ignore>
                                <select id="user_id" name="user_id" data-url="{{ url('admin/member/select2') }}"
                                    data-parent="#modalform" class="select2bind @error('user_id') is-invalid @enderror">
                                </select>
                            </div>
                            <input type="hidden" class="@error('user_id') is-invalid @enderror" />
                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label for="gift_type_id">Gift Type</label>
                                <select wire:change="dapatHarga" id="gift_type_id" name="gift_type_id"
                                    class="form-select @error('gift_type_id') is-invalid @enderror"
                                    wire:model="gift_type_id">
                                    <option value="">Select Gift Type</option>
                                    <!-- Isi opsi dengan data jenis hadiah dari sumber data yang tersedia -->
                                    @if ($giftTypes)
                                        @foreach ($giftTypes as $giftType)
                                            <option value="{{ $giftType->id }}">{{ $giftType->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('gift_type_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <div wire:ignore>
                                    <label for="price">Harga</label>
                                    <input type="text" id="price" name="price" class="money form-control " />
                                </div>
                                <input type="hidden" class="@error('price') is-invalid @enderror" />
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>

                        <div class="form-group mb-3">
                            <label for="sent_at">Tanggal Pengiriman</label>
                            <input type="date" id="sent_at" name="sent_at"
                                class="form-control @error('sent_at') is-invalid @enderror" wire:model="sent_at" />
                            @error('sent_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="sent_at">Tanggal Diterima</label>
                            <input type="date" id="sent_at" name="sent_at"
                                class="form-control @error('received_at') is-invalid @enderror"
                                wire:model="received_at" />
                            @error('received_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="notes">Catatan</label>
                            <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" wire:model="notes"></textarea>
                            @error('notes')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button"
                        class="btn btn-primary">{{ $editform ? 'Simpan Perubahan' : 'Simpan Baru' }}</button>

                </div>
            </div>
        </div>
    </div>

</div>
