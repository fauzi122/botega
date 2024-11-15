
<div>
    <div class="row">
        <div class="col-md-5">

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

            <div wire:loading.class="hide">
                <form wire:submit.prevent="save">

                    <div class="mb-3">
                        <label for="rek_debt" class="form-label">No. Rekening Debit:</label>
                        <input type="text" id="rek_debt" class="form-control" wire:model="rek_debt">
                    </div>
                    <div class="mb-3">
                        <label for="rek_debt" class="form-label">No. Rekening Debit:</label>
                        <input type="text" id="rek_debt" class="form-control" wire:model="rek_debt">
                    </div>
                    <div class="mb-3">
                        <label for="email_pic" class="form-label">Alamat Email Nitifikasi CSV:</label>
                        <input type="text" id="email_pic" class="form-control" wire:model="email_pic">
                    </div>

                    <div class="mb-3">
                        <label for="bank_id" class="form-label">Bank:</label>
                        <select id="bank_id" class="form-select" wire:model="bank_id">
                            <option value="">Pilih Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->akronim }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
