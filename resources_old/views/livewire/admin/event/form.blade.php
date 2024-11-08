<div>

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

    <form id="form_data" wire:submit.prevent>

       <div wire:loading.class="hide">
           <div class="form-group mb-3"  >
               <label for="judul">Judul</label>
               <input type="text" id="judul" name="start" class="form-control @error('judul') is-invalid @enderror" wire:model="judul" />
               @error('judul')
               <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>
           <div class="row mb-3">
               <div class="form-group col-md-6">
                   <label for="start">Start</label>
                   <input type="date" id="start" name="start" class="form-control @error('start') is-invalid @enderror" wire:model="start" />
                   @error('start')
                   <span class="text-danger">{{ $message }}</span>
                   @enderror
               </div>

               <div class="form-group col-md-6">
                   <label for="end">End</label>
                   <input type="date" id="end" name="end" class="form-control @error('end') is-invalid @enderror" wire:model="end" />
                   @error('end')
                   <span class="text-danger">{{ $message }}</span>
                   @enderror
               </div>

           </div>

           <div class="form-group mb-3">
               <label for="descriptions">Keterangan kegiatan</label>
               <textarea id="descriptions" name="descriptions" class="form-control @error('descriptions') is-invalid @enderror" wire:model="descriptions"></textarea>
               @error('descriptions')
               <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>

           <div class="form-group mb-3">
               <label for="member">Member</label>
               <div wire:ignore>
                   <select class="form-control" name="member_id[]" id="choices-multiple-remove-button" multiple wire:model="member_id">
                       @foreach($member as $mem)
                           <option value="{{ $mem->id }}">
                               {{ $mem->level_name }}
                           </option>
                       @endforeach
                   </select>
               </div>
           </div>



           <div class="form-group mb-3">
               <label for="publish">Publish</label>
               <div wire:ignore>
                   <input  type="checkbox" id="publish" name="publish" switch="none">
                   <label for="publish" data-on-label="Ya" data-off-label="Tidak"></label>
               </div>
               @error('publish')
               <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>
       </div>

    </form>


    <div class="modal-footer">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">{{  $editform ? 'Simpan Perubahan' : 'Simpan Baru'  }}</button>

    </div>

</div>
