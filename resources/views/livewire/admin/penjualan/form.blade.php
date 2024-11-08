<div>
    <div id="card-form" class="card">
        <div class="card-body">
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

            <form id="form-data" wire:submit.prevent>
                <input type="hidden" name="edit" value="{{$editform}}" />
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="trx_at">Tanggal</label>
                        <input readonly type="date" id="trx_at" name="trx_at" class="form-control @error('trx_at') is-invalid @enderror" wire:model="trx_at" />
                        @error('trx_at')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="nomor_so">No SO</label>
                        <input readonly type="text" id="nomor_so" name="nomor_so" class="form-control @error('nomor_so') is-invalid @enderror" wire:model="nomor_so" />
                        @error('nomor_so')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class='col-md-5'>
                        @if(!$editform)
                        <div id="pilih-member" style="display: {{$editform ? 'none':'block'}}">
                            <div wire:ignore>
                                <label for="member_user_id">Member</label>
                                <select readonly class="select2bind"
                                        data-parent="#card-form"
                                        data-url="{{url('admin/member/select2')}}"
                                        name="member_user_id" id="member_user_id">
                                    @if($lm != null)
                                        <option selected value="{{$member_user_id}}">{{$lm?->member}} {{$lm?->last_name}} ({{$lm?->no_member}})</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        @else
                            <div >
                                <label for="member_user_id">Member</label>
                                <input type="text"  class="form-control" readonly value="{{$lm->member}} {{$lm->last_name}} ({{$lm->no_member}})" />
                                <input type="hidden" name="member_user_id" readonly value="{{$member_user_id}}" />
                            </div>
                        @endif

                        <input type="hidden" class="@error('member_user_id') is-invalid @enderror"/>
                        @error('member_user_id')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

{{--
                    <div class="col-md-2">
                        <div wire:ignore>
                            <label for="total">Total</label>
                            <input type="text" readonly id="total" name="total" readonly class="money form-control" value="{{intval($total) }}" />
                        </div>
                        <input type="hidden" class=" @error('total') is-invalid @enderror" />
                        @error('total')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-1">
                        <label for="point">Poin</label>
                        <input type="text" readonly id="point" name="point" class="money form-control @error('point') is-invalid @enderror" wire:model="point" />
                        @error('point')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    --}}
                </div>

                <div class="form-group mb-3">
                    <label for="notes">Catatan</label>
                    <textarea readonly class=form-control @error('notes') is-invalid @enderror" name="notes"
                              id="notes" wire:model="notes"></textarea>
                    @error('notes')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <h3 id="jenis-level" class="text-info "></h3>

            </form>

        </div>

    </div>
</div>
