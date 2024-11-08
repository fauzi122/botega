<!-- resources/views/livewire/reset-password.blade.php -->

<div  >
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reset Sandi</div>
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


                <div class="card-body">
                    <form wire:submit.prevent="resetPassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Sandi Lama</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" wire:model="current_password">
                            @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Sandi Baru</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" wire:model="new_password">
                            @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Ulangi Sandi Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation" wire:model="new_password_confirmation">
                        </div>

                        <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">Reset Sandi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
