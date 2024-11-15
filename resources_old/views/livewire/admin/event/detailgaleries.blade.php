<div>
   <div class="row">
       @if($event != null)
        <div  class="mb-3">
             <h3>Galeri {{$event->judul}} {{$event->id}}</h3>
           @error('event_id') <span class="text-danger">{{ $message }}</span> @enderror
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


       <div class="col-md-4">


            @if($event == null)
                <div class="alert alert-warning alert-border-left">
                    Data Event belum tersedia
                </div>
            @else

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

                <div wire:loading.class="hide">
                 <form wire:submit.prevent="">
                    <!-- Membuat input title dengan atribut wire:model untuk mengikat properti $title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" wire:model="title">
                        <!-- Menampilkan pesan error jika ada -->
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Membuat textarea description dengan atribut wire:model untuk mengikat properti $description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="3" wire:model="description"></textarea>
                        <!-- Menampilkan pesan error jika ada -->
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Membuat input file path_file dengan atribut wire:model untuk mengikat properti $path_file -->
                    <div class="mb-3">
                        <label for="path_file" class="form-label">File Foto</label>
                        <div wire:ignore>
                            <input accept="image/*" type="file" class="form-control" id="path_file"  >
                        </div>
                        @if($urlfoto != '')
                            <img src="{{$urlfoto}}" style="width: 100px" /><hr/>
                        @endif
                        <!-- Menampilkan pesan error jika ada -->
                        @error('path_file') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Membuat tombol submit untuk mengirim data -->
                    <button onclick="saveGaleri()" class="btn btn-primary">Submit</button>
                </form>
            </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="row">
                @foreach($listgaleries as $l)
                    @php
                        $url = asset('/assets/images/bottega-brown.png');
                        if(Storage::exists($l->path_file)){
                            $url = url('/admin/event/images/'.$l->id.'.png' );
                        }
                    @endphp
                    <div class="col-md-4 mb-2">
                        <img style="width:100px" src="{{$url}}" />
                        <span class="badge badge-soft-primary">{{$l->title}}</span>
                        <div class="text-muted font-10">{{$l->description}}</div>
                        <div>
                            <button onclick="hapusGaleri('{{$l->id}}')" class="btn btn-sm btn-danger" data-toggle="tooltip" data-title="Hapus"><i class="mdi mdi-trash-can"></i></button>
                            <button wire:click="edit('{{$l->id}}')" class="btn btn-sm btn-warning" data-toggle="tooltip" data-title="Edit"><i class="mdi mdi-pencil-circle"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
   </div>
</div>
