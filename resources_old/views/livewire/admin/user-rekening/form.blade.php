<div style="padding: 20px 5px 5px 5px">
    @if($user != null)
   <div class="card">
       <div class="card-header">
           <h3 class="card-title">Rekening {{$user?->first_name}} {{$user?->last_name}} ({{$user?->id_no}})</h3>
       </div>
       <div class="card-body">
          <div class="row">
              <div class="col-md-4">

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
                          <div class="form-group mb-3">
                              <label for="bank_id">Bank</label>
                              <select id="bank_id" name="bank_id" class="form-control @error('bank_id') is-invalid @enderror" wire:model="bank_id">
                                  <option value="">[ Pilih Bank ]</option>
                                  @foreach($banks as $bank)
                                      <option value="{{ $bank->id }}">{{ $bank->name }} ({{$bank->akronim}})</option>
                                  @endforeach
                              </select>
                              @error('bank_id')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                          </div>
                          <div class="form-group mb-3">
                              <label for="bank_kota">Kota Bank</label>
                              <input type="text" id="bank_kota" name="bank_kota" class="form-control @error('bank_kota') is-invalid @enderror" wire:model="bank_kota" />
                              @error('bank_kota')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                          </div>

                          <div class="form-group mb-3">
                              <label for="is_primary">Rekening Utama</label>

                             <div class="mb-3">
                                 <input id="is_primary"  type="checkbox" wire:model="is_primary" switch="none" {{$is_primary ? 'checked' : ''}}>
                                 <label for="is_primary" data-on-label="Ya" data-off-label="Tidak"></label>
                             </div>
                              @error('is_primary')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                          </div>
                          <div class="form-group mb-3">
                              <label for="no_rekening">Nomor Rekening</label>
                              <input type="text" id="no_rekening" name="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror" wire:model="no_rekening" />
                              @error('no_rekening')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                          </div>
                          <div class="form-group mb-3">
                              <label for="an">Atas Nama Nomor Rekening</label>
                              <input type="text" id="an" name="an" class="form-control @error('an') is-invalid @enderror" wire:model="an" />
                              @error('an')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                          </div>

                      </form>
                  </div>
              </div>
              <div class="col-md-8">
                  <table class="table table-bordered table-responsive table-striped table-hover">
                      <thead>
                        <tr>
                            <th style="width:30px">No.</th>
                            <th>Primary</th>
                            <th>Bank</th>
                            <th>No. Rekening</th>
                            <th>AKSI</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                            $no = 1;
                        @endphp
                            @foreach( ($rekenings ?? []) as $rekening)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$rekening->is_primary ? 'Ya' : 'Tidak'}}</td>
                                    <td><img src="{{url('admin/bank/'.$rekening->bank_id.'.png')}}" style="width:40px; object-fit: scale-down" /> {{$rekening->bank}} ({{$rekening->bank_akro}}) <br/>{{$rekening->bank_kota}}</td>
                                    <td>{{$rekening->no_rekening}}<br/>An. {{$rekening->an}}</td>
                                    <td>
                                        <a class="btn btn-sm btn-warning btn-rounded" href="#" onclick="editRek('{{$rekening->id}}')"><i class="fa fa-pencil-alt"></i></a>
                                        <a class="btn btn-sm btn-danger btn-rounded" href="#" onclick="hapusRek('{{$rekening->id}}')" ><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
       </div>
       <div class="card-footer">
           <button onclick="simpanRekening()" wire:loading.attr='disabled' type="button" class="btn btn-primary">Simpan Rekening</button>
       </div>
   </div>
    @else
        <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show mb-0" role="alert">
            <i class="mdi mdi-alert-circle-outline label-icon"></i><strong>Info</strong> - Data Member Belum tersedia
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
