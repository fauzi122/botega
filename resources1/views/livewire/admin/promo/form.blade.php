<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Member</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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

                    <form id="formmodal" wire:submit="store">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div wire:ignore>
                                        <label>Pilih Produk</label>
                                        <select class="select2bind"
                                                data-parent=".modal"
                                                data-url="{{url('admin/produk/select2')}}"
                                                name="product_id"></select>
                                    </div>
                                    <input type="hidden" class="@error('product_id') is-invalid @enderror">
                                    @error('product_id')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div wire:ignore>
                                            <label>Harga</label>
                                            <input type="text" name="price" class="money form-control @error('price') is-invalid   @enderror" />
                                        </div>
                                        @error('price')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">

                                        <label>Berlaku hingga</label>
                                        <input type="date" name="expired_at" class="form-control @error('expired_at') is-invalid   @enderror" wire:model="expired_at" />
                                        @error('expired_at')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div>
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    @if($editform)
                        <button  type="button" wire:loading.attr='disabled' class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onclick="update()" wire:loading.attr='disabled' type="button" class="btn btn-primary">Simpan Perubahan</button>
                    @else
                        <button type="button"  wire:loading.attr='disabled' class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onclick="store()"  wire:loading.attr='disabled' type="button" class="btn btn-primary">Simpan Baru</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', ()=>{
        select2bind();
        $('.money').mask('#.000.000.000.000,-',{reverse:true});
    });

    function store(){
        let wire = Livewire.getByName('admin.promo.form')[0];
        let pid = $('select[name=product_id]').val();
        let price = $('input[name=price]').unmask().val();

        $('.money').mask('#.000.000.000.000,-',{reverse:true});

        wire.set('product_id', pid, false);
        wire.set('product_id', pid, false);
        wire.set('price', price, false);
        wire.store().then(()=>{
            $('table#jd-table').DataTable().ajax.reload();
            $('#formmodal')[0].reset();
        });
    }
</script>
