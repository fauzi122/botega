<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Artikel</h5>
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

                    <form wire:loading.class="hide" id="form_data" wire:submit.prevent>
                       <div class="row mb-3">
                           <div class="form-group col-md-7">
                               <label for="judul">Judul</label>
                               <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" wire:model="judul" />
                               @error('judul')
                               <span class="text-danger">{{$message}}</span>
                               @enderror
                           </div>

                           <div class="form-group col-md-5">
                               <label for="keyword">Keyword</label>
                               <input type="text" id="keyword" name="keyword" class="form-control @error('keyword') is-invalid @enderror" wire:model="keyword" />
                               @error('keyword')
                               <span class="text-danger">{{$message}}</span>
                               @enderror
                           </div>
                       </div>

                        <div class="form-group mb-3">
                            <div wire:ignore>
                                <label for="article">Isi Artikel</label>
                                <textarea id="article" rows="15" name="article" class="form-control @error('article') is-invalid @enderror" ></textarea>
                            </div>
                            <input type="hidden" class="@error('article') is-invalid @enderror" />
                            @error('article')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                       <div class="row mb-4">
                           <div class="form-group col-md-5">
                               <div wire:ignore>
                                   <label for="article">Gambar Artikel</label>
                                   <input type="file" name="gambar_artikel" class="form-control-file form-control" />
                               </div>
                               <input type="hidden" class="@error('article') is-invalid @enderror" />
                               @error('article')
                               <span class="text-danger">{{$message}}</span>
                               @enderror
                           </div>
                           <div class="col-md-7">
                               <img style="width: 200px; height: 150px; object-fit: cover" src="" id="img-preview" />
                               <button id='btn-hapus-gambar' style="position: absolute; margin-top: 130px; margin-left: -50px"
                                       class="btn btn-sm btn-danger btn-rounded" title="Hapus gambar"
                                       onclick="return hapusgambar({{$id}})"><i class="mdi mdi-close"></i></button>
                           </div>

                       </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-3">
                                <label for="published_at">Tanggal Tayang</label>
                                <input type="date" id="published_at" name="published_at" class="form-control @error('published_at') is-invalid @enderror" wire:model="published_at" />
                                @error('published_at')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="expired_at">Tanggal Berakhir</label>
                                <input type="date" id="expired_at" name="expired_at" class="form-control @error('expired_at') is-invalid @enderror" wire:model="expired_at" />
                                @error('expired_at')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="article_category_id">Kategori Artikel</label>
                                <div wire:ignore>
                                    <select id="article_category_id"
                                            name="article_category_id"
                                            data-parent="#modalform"
                                            data-url="{{url('admin/kategori/select2')}}"
                                            class="select2bind"  >
                                    </select>
                                </div>
                                <input type="hidden" class=" @error('article_category_id') is-invalid @enderror" />
                                @error('article_category_id')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                        </div>


                        <div class="form-group mb-3">
                            <label for="product_id">Produk</label>
                            <div wire:ignore>
                                <select id="product_id"
                                        name="product_id"
                                        data-parent="#modalform"
                                        data-url="{{url('admin/produk/select2')}}"
                                        class="select2bind"  >
                                </select>
                            </div>
                            <input type="hidden" class=" @error('product_id') is-invalid @enderror" />

                            @error('product_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>


                    </form>


                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onclick="save()" type="button" class="btn btn-primary">{{$editform ? 'Simpan Perubahan' : 'Simpan baru'}}</button>

                </div>
            </div>
        </div>
    </div>
<style>

</style>
</div>

