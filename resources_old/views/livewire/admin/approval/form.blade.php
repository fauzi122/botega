<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approval Permintaan Ubah Data Diri</h5>
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

                    <form id="formmodal" wire:submit.prevent="" wire>
                        <div wire:loading.class="hide">
                           <div class="row">
                               <div class="col-md-6">
                                   <div class="card">
                                       <div class="card-header">
                                           <h4 class="card-title">Data Saat ini</h4>
                                       </div>
                                       <div class="card-body">
                                           <div class="row">
                                               <div class="col-md-12">
                                                   <div class='row mb-3'>


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

                                                   <div class="row mb-3">
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

                                           </div>

                                       </div>
                                   </div>

                               </div>
                               <div class="col-md-6">
                                   <div class="card">
                                       <div class="card-header">
                                           <h4 class="card-title">Data Usulan</h4>
                                       </div>
                                       <div class="card-body">

                                           <div class="row">
                                               <div class="col-md-12">
                                                   <div class='row mb-3'>

                                                       <div class="col-md-12 mb-3">
                                                           <label for="n_nik">NIK</label>
                                                           <input type="text" id="n_nik" name="n_nik" class="form-control @error('n_nik') is-invalid @enderror" wire:model="n_nik" />
                                                           @error('nik')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                       <div class="col-md-12 mb-3">
                                                           <label for="n_npwp">NPWP</label>
                                                           <input type="text" id="n_npwp" name="n_npwp" class="form-control @error('n_npwp') is-invalid @enderror" wire:model="n_npwp" />
                                                           @error('npwp')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>


                                                       <div class="col-md-6">
                                                           <label for="n_first_name">First Name</label>
                                                           <input type="text" id="n_first_name" name="n_first_name" class="form-control @error('n_first_name') is-invalid @enderror" wire:model="n_first_name" />
                                                           @error('first_name')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>

                                                       <div class="col-md-6">
                                                           <label for="n_last_name">Last Name</label>
                                                           <input type="text" id="n_last_name" name="n_last_name" class="form-control @error('n_last_name') is-invalid @enderror" wire:model="last_name" />
                                                           @error('n_last_name')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                   </div>


                                                   <div class="form-group mb-3">
                                                       <label for="n_gender">Jenis Kelamin</label>
                                                       <select id="n_gender" name="n_gender" class="form-select @error('n_gender') is-invalid @enderror" wire:model="n_gender">
                                                           <option value="L">Laki-Laki</option>
                                                           <option value="P">Perempuan</option>
                                                       </select>
                                                       @error('gender')
                                                       <span class="text-danger">{{$message}}</span>
                                                       @enderror
                                                   </div>

                                                   <div class="form-group mb-3">
                                                       <label for="n_birth_at">Tanggal Lahir</label>
                                                       <input type="date" id="n_birth_at" name="n_birth_at" class="form-control @error('n_birth_at') is-invalid @enderror" wire:model="n_birth_at" />
                                                       @error('birth_at')
                                                       <span class="text-danger">{{$message}}</span>
                                                       @enderror
                                                   </div>

                                                   <div class="form-group mb-3">
                                                       <label for="n_home_addr">Alamat Rumah</label>
                                                       <input type="text" id="homn_home_addrdr" name="n_home_addr" class="form-control @error('n_home_addr') is-invalid @enderror" wire:model="n_home_addr" />
                                                       @error('n_home_addr')
                                                       <span class="text-danger">{{$message}}</span>
                                                       @enderror
                                                   </div>

                                                   <div class="row">
                                                       <div class="col-md-3">
                                                           <label for="n_rt">RT</label>
                                                           <input type="text" id="n_rt name="n_rt" class="form-control @error('n_rt') is-invalid @enderror" wire:model="n_rt" />
                                                           @error('n_rt')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                       <div class="col-md-3">
                                                           <label for="n_rw">RW</label>
                                                           <input type="text" id="n_rw" name="n_rw" class="form-control @error('n_rw') is-invalid @enderror" wire:model="n_rw" />
                                                           @error('n_rw')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                       <div class="col-md-6">
                                                           <label for="n_zip_code">Kode pos</label>
                                                           <input type="text" id="n_zip_code" name="n_zip_code" class="form-control @error('n_zip_code') is-invalid @enderror" wire:model="n_zip_code" />
                                                           @error('zip_code')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                   </div>


                                               </div>

                                               <div class=" ">

                                                   <div class="form-group mb-3">
                                                       <label for="n_phone">Phone</label>
                                                       <input type="text" id="n_phone" name="n_phone" class="form-control @error('n_phone') is-invalid @enderror" wire:model="n_phone" />
                                                       @error('phone')
                                                       <span class="text-danger">{{$message}}</span>
                                                       @enderror
                                                   </div>

                                                   <div class="row mb-3">
                                                       <div class="col-md-6">
                                                           <label for="n_hp">HP</label>
                                                           <input type="text" id="n_hp" name="n_hp" class="form-control @error('n_hp') is-invalid @enderror" wire:model="n_hp" />
                                                           @error('n_hp')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>

                                                       <div class="col-md-6">
                                                           <label for="n_wa">WA</label>
                                                           <input type="text" id="n_wa" name="n_wa" class="form-control @error('n_wa') is-invalid @enderror" wire:model="n_wa" />
                                                           @error('n_wa')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                   </div>

                                                   <div class="form-group mb-3">
                                                       <label for="n_email">Email</label>
                                                       <input type="email" id="n_email" name="n_email" class="form-control @error('n_email') is-invalid @enderror" wire:model="n_email" />
                                                       @error('n_email')
                                                       <span class="text-danger">{{$message}}</span>
                                                       @enderror
                                                   </div>

                                                   <div class="row mb-3">
                                                       <div class="form-group col-md-6">
                                                           <label for="n_web">Web</label>
                                                           <input type="web" id="n_web" name="n_web" class="form-control @error('n_web') is-invalid @enderror" wire:model="n_web" />
                                                           @error('web')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                           <label for="n_fax">Fax</label>
                                                           <input type="fax" id="n_fax" name="n_fax" class="form-control @error('n_fax') is-invalid @enderror" wire:model="n_fax" />
                                                           @error('fax')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>

                                                   </div>

                                                   <div class="row mb-3">
                                                       <div class="form-group ">
                                                           <label for="n_nppkp">NPPKP</label>
                                                           <input type="text" id="n_nppkp" name="n_nppkp" class="form-control @error('n_nppkp') is-invalid @enderror" wire:model="n_nppkp" />
                                                           @error('web')
                                                           <span class="text-danger">{{$message}}</span>
                                                           @enderror
                                                       </div>
                                                   </div>



                                               </div>

                                           </div>
                                       </div>
                                   </div>
                               </div>

                               <div class="col-md-12">
                                   <div class="card">
                                       <div class="card-body">
                                           <div class="mb-3">
                                               <label>Alasan Member</label>
                                               <textarea readonly wire:model="reason_user" class="form-control"></textarea>
                                           </div>
                                           <div class="mb-3">
                                               <label>Status Pengajuan {{$status}}</label>
                                               <select class="form-select" wire:model="status">
                                                   <option value="Approved">Terima</option>
                                                   <option value="Rejected">Tolak</option>
                                               </select>
                                           </div>
                                           <div class="mb-3">
                                               <label>Alasan Pengelola</label>
                                               <textarea wire:model="reason_admin" class="form-control"></textarea>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                        </div>

                            <!-- Add other fields (like rt, rw, zip_code) following the same pattern -->
                    </form>


                </div>
                <div class="modal-footer">
                    <button wire:loading.attr='disabled'  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onclick="save()" type="button" class="btn btn-primary">Simpan Perubahan</button>

                </div>
            </div>
        </div>
    </div>

</div>
