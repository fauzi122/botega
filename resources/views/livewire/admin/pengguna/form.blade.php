<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengguna</h5>
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


                    <div wire:loading.class.remove="hide" class="hide" style="width: 100%; justify-content: center">
                        <div class="loader-wrapper">
                            <div class="loader"></div>
                        </div>
                        <div style="display: flex; justify-content: center">
                            Sedang memuat data...
                        </div>
                    </div>
                    <div wire:loading.class="hide">

                        <form id="form_data"  wire:submit.prevent>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="foto_path">Foto</label>
                                        <div wire:ignore>
                                            <input type="file" class="form-control " name="foto_path" accept="image/*" />
                                            <img id="img-preview" style="width: 100%; height: 200px; object-fit: cover" />
                                        </div>

                                        <button id='btn-hapus-gambar' style="position: absolute; "
                                                class="  btn btn-sm btn-danger btn-rounded" title="Hapus gambar"
                                                onclick="return hapusgambar({{$id}}) "><i class="mdi mdi-close"></i></button>
                                        <input type="hidden" class="@error('file') is-invalid @enderror" />
                                        @error('file')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row mb-3">
                                        <div class="form-group col-md-6">
                                            <label for="first_name">Nama Awal</label>
                                            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" wire:model="first_name" />
                                            @error('first_name')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="last_name">Nama Akhir</label>
                                            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name" />
                                            @error('last_name')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>


                                    </div>
                                    <div class="row mb-3">
                                        <div class="form-group col-md-4">
                                            <label for="gender">Jenis Kelamin</label>
                                            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" wire:model="gender">
                                                <option value="">-Pilih Jenis Kelamin-</option>
                                                <option value="L">Laki-Laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            @error('gender')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="birth_at">Tanggal Lahir</label>
                                            <input type="date" id="birth_at" name="birth_at" class="form-control @error('birth_at') is-invalid @enderror" wire:model="birth_at" />
                                            @error('birth_at')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 ">
                                            <label for="role_id">Peran Akses</label>
                                            <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" wire:model="role_id">
                                                    <option value="">-Pilih Peran-</option>
                                                @foreach(\App\Models\RoleModel::get() as $k)
                                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="home_addr">Alamat Rumah</label>
                                        <textarea wire:model="home_addr" class="form-control @error('home_addr') is-invalid @enderror "></textarea>
                                        @error('home_addr')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>



                                    <div class="row mb-3">
                                        <div class="form-group col-md-6">
                                            <label for="phone">Phone</label>
                                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" wire:model="phone" />
                                            @error('phone')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group  col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" />
                                            @error('email')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="form-group  col-md-6">
                                            <label for="hp">HP</label>
                                            <input type="text" id="hp" name="hp" class="form-control @error('hp') is-invalid @enderror" wire:model="hp" />
                                            @error('hp')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group  col-md-6">
                                            <label for="wa">WA</label>
                                            <input type="text" id="wa" name="wa" class="form-control @error('wa') is-invalid @enderror" wire:model="wa" />
                                            @error('wa')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">{{  $editform ? 'Simpan Perubahan' : 'Simpan Baru'  }}</button>

                </div>
            </div>
        </div>
    </div>


    <div wire:ignore.self id="modalReset" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
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


                    <div wire:loading.class.remove="hide" class="hide" style="width: 100%; justify-content: center">
                        <div class="loader-wrapper">
                            <div class="loader"></div>
                        </div>
                        <div style="display: flex; justify-content: center">
                            Sedang memuat data...
                        </div>
                    </div>
                    <div wire:loading.class="hide">

                        <form id="form_data"  wire:submit.prevent>
                            <div class="form-group  col-md-6">
                                <label for="Email">Email Pengguna</label>
                                <input type="text" id="email" name="email" readonly class="form-control @error('hp') is-invalid @enderror" wire:model="email" />
                                @error('hp')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </form>

                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="resetsandi()" type="button" class="btn btn-primary">Reset Password</button>

                </div>
            </div>
        </div>
    </div>

</div>
