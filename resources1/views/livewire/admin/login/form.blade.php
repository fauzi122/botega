<div>
    <div wire:loading.class="hide">
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
    </div>

    <div wire:loading.class.remove="hide" class="hide" style="width: 100%; justify-content: center">
        <div class="loader-wrapper">
            <div class="loader"></div>
        </div>
        <div style="display: flex; justify-content: center">
            Sedang memuat data...
        </div>
    </div>

    <div class="form-login" style="display: {{$isforgot ? 'none' : 'block'}};">
        <form wire:submit.prevent="login">
            <div wire:loading.class="hide">
                <div class="text-center">
                    <p class="text-muted mt-2">Masuk Ke Ruang Pengelola Sistem</p>
                </div>


                <div class="mb-3" style="text-align: left">
                    <label class="form-label" >Nama Pengguna</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                           wire:model="username" id="username" placeholder="Nama Pengguna" />
                    @error("username")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3"  style="text-align: left">
                    <div class="d-flex align-items-start" >
                        <div class="flex-grow-1">
                            <label class="form-label">Kata Sandi</label>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="">
                                <a href="javascript:void(0);" onclick="showForgot()" class="text-muted">Lupa password...?</a>
                            </div>
                        </div>
                    </div>

                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" id="pwd-login"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Enter password" aria-label="Password" aria-describedby="password-addon"
                               wire:model="password"
                        />
                        <button  class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error("password")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="row mb-4">
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember-check">
                            <label class="form-check-label" for="remember-check">
                                Ingatkan saya
                            </label>
                        </div>
                    </div>

                </div>
                <div class="mb-3">
                    <button wire:loading.attr="disabled" class="btn btn-primary w-100 waves-effect waves-light" type="submit">Masuk</button>
                </div>
            </div>

        </form>
    </div>

    <div class="form-lupapassword"  style="display: {{$isforgot ? 'block' : 'none'}};">
        <div wire:loading.class="hide">


                <div class="auth-content my-auto">
                    <div class="text-center">
                        <h5 class="mb-0">Forgot Password !</h5>
                        <p class="text-muted mt-2">Sign in to continue to Bottega Admin.</p>
                    </div>
                    <form class="mt-4 pt-2" wire:submit.prevent="forgot">
                          <div  class="mb-3" style="text-align: left">
                            <label>Email Address*</label>
                            <input type="email" class="form-control" wire:model="email" placeholder="Email Address"
                                   name="email" required>

                        </div>

                        <div class="row mb-4">
                            <div class="col">

                            </div>

                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary w-100 waves-effect waves-light"
                                    id="registerButton" type="submit">Kirim Email
                            </button>
                        </div>
                    </form>


                    <div class="mt-5 text-center">
                        <p class="text-muted mb-0">Already have an account ? <a href="javascript:void(0)" onclick="loginForm()" class="text-primary fw-semibold">
                                Login </a></p>
                    </div>
                </div>
                <div class="mt-4 mt-md-5 text-center">
                    <p class="mb-0">©
                        <script>document.write(new Date().getFullYear())</script> | Bottega & Artisan
                    </p>
                </div>

        </div>
    </div>
<script>
    function showForgot() {
        $(".form-lupapassword").show();
        $(".form-login").hide();
    }

    function loginForm(){
        $(".form-lupapassword").hide();
        $(".form-login").show();
    }
</script>
</div>
