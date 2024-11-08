<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Produk</h5>
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

                        <form id="forminput" wire:submit>

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group mb-3">
                                        <div wire:ignore>
                                            <label for="category_id">Kategori Produk</label>
                                            <select name="category_id" class="select2bind"
                                                    data-parent="#modalform"
                                                    data-url="{{url('admin/kategori-produk/select2')}}"
                                            ></select>
                                        </div>
                                        <input type="hidden" class="@error('category_id') is-invalid @enderror"/>
                                        @error('category_id')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="kode">Kode Produk</label>
                                            <input type="text" id="kode" name="kode" class="form-control @error('kode') is-invalid @enderror" wire:model="kode" />
                                            @error('kode')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-8">
                                            <label for="name">Nama Produk</label>
                                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
                                            @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div wire:ignore>
                                                <label for="price">Harga</label>
                                                <input type="text" id="price" name="price" class="money form-control @error('price') is-invalid @enderror"  />
                                            </div>
                                            <input type="hidden" class="@error('price') is-invalid @enderror"/>
                                            @error('price')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <div wire:ignore>
                                                <label for="cost_price">Harga dasar</label>
                                                <input type="text" id="cost_price" name="cost_price" class="money form-control @error('cost_price') is-invalid @enderror"  />
                                            </div>
                                            <input type="hidden" class="@error('cost_price') is-invalid @enderror"/>
                                            @error('cost_price')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group mb-3">
                                        <label for="qty">Quantity</label>
                                        <input type="number" id="qty" name="qty" class="form-control @error('qty') is-invalid @enderror" wire:model="qty" />
                                        @error('qty')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="descriptions">Deskripsi Produk</label>
                                        <textarea id="descriptions" name="descriptions" class="form-control @error('descriptions') is-invalid @enderror" wire:model="descriptions"></textarea>
                                        @error('descriptions')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                </div>
                            </div>

                        </form>


                </div>
                <div class="modal-footer">
                    @if($editform)
                        <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onclick="store()" type="button" class="btn btn-primary">Simpan Perubahan</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onclick="store()" type="button" class="btn btn-primary">Simpan Baru</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
