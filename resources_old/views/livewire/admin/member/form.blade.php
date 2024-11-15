<div>
    <div style="height: 40px"></div>
    <div style="display: none;" wire:loading.class="loading">
        Mohon tunggu sedang Loading...
    </div>
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

    <form id="formmodal" onsubmit="return false;">

        <div class="row">
            <div class="col-md-4">
                <div class='row mb-3'>
                    <div class="col-md-12 mb-3">
                        <label for="id_no">ID Member</label>
                        <input type="text" id="id_no" name="id_no" class="form-control @error('id_no') is-invalid @enderror" wire:model="id_no" />
                        @error('id_no')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="id_no">NIK</label>
                        <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" wire:model="nik" />
                        @error('nik')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="id_no">NPWP</label>
                        <input type="text" id="npwp" name="npwp" class="form-control @error('npwp') is-invalid @enderror" wire:model="npwp" />
                        @error('npwp')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    <div class="col-md-6">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" wire:model="first_name" />
                        @error('first_name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name" />
                        @error('last_name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="form-group mb-3">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" wire:model="gender">
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    @error('gender')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="birth_at">Tanggal Lahir</label>
                    <input type="date" id="birth_at" name="birth_at" class="form-control @error('birth_at') is-invalid @enderror" wire:model="birth_at" />
                    @error('birth_at')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="home_addr">Alamat Rumah</label>
                    <input type="text" id="home_addr" name="home_addr" class="form-control @error('home_addr') is-invalid @enderror" wire:model="home_addr" />
                    @error('home_addr')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="rt">RT</label>
                        <input type="text" id="rt" name="rt" class="form-control @error('rt') is-invalid @enderror" wire:model="rt" />
                        @error('rt')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="rt">RW</label>
                        <input type="text" id="rw" name="rw" class="form-control @error('rw') is-invalid @enderror" wire:model="rw" />
                        @error('rw')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="zip_code">Kode pos</label>
                        <input type="text" id="zip_code" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror" wire:model="zip_code" />
                        @error('zip_code')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


            </div>

            <div class="col-md-4">

                <div class="form-group mb-3">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" wire:model="phone" />
                    @error('phone')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="hp">HP</label>
                        <input type="text" id="hp" name="hp" class="form-control @error('hp') is-invalid @enderror" wire:model="hp" />
                        @error('hp')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="wa">WA</label>
                        <input type="text" id="wa" name="wa" class="form-control @error('wa') is-invalid @enderror" wire:model="wa" />
                        @error('wa')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" />
                    @error('email')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label for="web">Web</label>
                        <input type="web" id="web" name="web" class="form-control @error('web') is-invalid @enderror" wire:model="web" />
                        @error('web')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fax">Fax</label>
                        <input type="fax" id="fax" name="fax" class="form-control @error('fax') is-invalid @enderror" wire:model="fax" />
                        @error('fax')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="form-group ">
                        <label for="nppkp">NPPKP</label>
                        <input type="text" id="nppkp" name="nppkp" class="form-control @error('nppkp') is-invalid @enderror" wire:model="nppkp" />
                        @error('web')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


             {{--   <div class="form-group mb-3" >
                    <label for="role_id">Peran</label>
                    <select wire:model="role_id" class="form-select" name="role_id">
                        <option>Pilih Peran</option>
                        @foreach ($roles as $r )
                            <option value="{{$r->id}}">{{$r->name}}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div> --}}


                <div class="form-group mb-3" >

                    <label for="level_member_id">Level Member</label>
                    <select class="form-select" class="" wire:model="level_member_id" name="level_member_id" >

                        <option>Pilih Level Member</option>
                        @foreach($levelmember as $m)
                            <option value="{{$m->id}}">{{$m->level_name}}</option>
                        @endforeach
                    </select>
                    @error('level_member_id')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group mb-3" >

                    <label for="cabang_id">Cabang</label>
                    <select class="form-select" class="" wire:model="cabang_id" name="cabang_id" >

                        <option value=""><b>-- Pilih Cabang --</b></option>
                        <option value="X"><b>[Semua Cabang]</b></option>
                        @foreach($cabang as $m)
                            <option value="{{$m->id}}">{{$m->nama}}</option>
                        @endforeach
                    </select>
                    @error('cabang_id')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

            </div>
            <div class="col-md-4">


                <div class="form-group mb-3">
                    <label for="reward_type">Reward Type </label>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="member pro" id="memberCheckbox" {{$reward_type == 1 || $reward_type == 3 ? 'checked' : ''}} name="memberCheckbox">
                        <label class="form-check-label" for="memberCheckbox">
                            Member Professional
                        </label>
                    </div>
                    <div class="form-check">
                        <input  class="form-check-input" type="checkbox" value="umum" id="umumCheckbox" name="umumCheckbox" {{$reward_type == 2 || $reward_type == 3 ? 'checked' : ''}}>
                        <label class="form-check-label" for="umumCheckbox">
                            Umum
                        </label>
                    </div>

                    @error('reward_type')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="form-group ">
                        <label for="points">Total Poin</label>
                        <input type="number" id="points" name="points" class="form-control @error('points') is-invalid @enderror" wire:model="points" />
                        @error('points')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="form-group mb-3" >

                    <label for="is_perusahaan">Sebagai Perusahaan</label>
                    <select class="form-select" class="" wire:model="is_perusahaan" name="is_perusahaan" >

                        <option>Pilih </option>
                        <option value="0">Tidak</option>
                        <option value="1">Perusahaan</option>
                    </select>
                    @error('is_perusahaan')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group"  >
                    <label for="foto_path">Foto</label>
                    <div class="input-group">
                        <input type="file" id="foto_path" name="foto_path" class="form-control mb-2 @error('foto_path') is-invalid @enderror" wire:model="foto_path" />
                        <div class="input-group-append">
                            <button wire:click="clearFoto" onclick="removeimg()" class="input-group-text">
                                <i class="mdi mdi-trash-can"></i>
                            </button>
                        </div>
                    </div>
                    <img id='foto_path_preview' wire:ignore  src="{{ $urlfoto }}" style="display:block; background-color: grey; max-width: 150px; max-height: 200px"/>

                    @error('foto_path')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Verifikasi Email</label>
                    <select class="form-select" wire:model="is_email_verified" name="is_email_verified" >
                        <option value="0">Belum</option>
                        <option value="1">Sudah</option>
                    </select>
                    @error('is_email_verified')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>



            </div>
        </div>
        <!-- Add other fields (like rt, rw, zip_code) following the same pattern -->
    </form>

    <div class="card-footer">
        <button  type="button" wire:loading.attr='disabled' class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button onclick="save()"  wire:loading.attr='disabled' type="button" class="btn btn-primary">Simpan Perubahan</button>
    </div>

</div>
