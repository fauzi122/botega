<div>
    <div   id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profesional</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#page-form" role="tab" aria-selected="true">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">Biodata</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link  " data-bs-toggle="tab" href="#page-userrek" role="tab" aria-selected="false" tabindex="-1">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">Rekening</span>
                                    </a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div id="page-form" class="tab-pane active show" role="tabpanel">
                                    @livewire('admin.member.form')
                                </div>

                                <div id="page-userrek" class="tab-pane" role="tabpanel">
                                    @livewire('admin.user-rekening.form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .loading{
            width:98%;
            height: 100%;
            padding-left: 40%;
            background-color: white;
            opacity: 0.7;
            z-index:2000;
            padding-top:20%;
            display: block !important;
            position: absolute;
            overflow: hidden;
        }
    </style>
</div>
@push('script')

    <script>
        function removeimg(){
            $('img#foto_path_preview').attr('src','');
            $('input[name=foto_path]').val(null);
            Livewire.getByName('admin.member.form')[0].clearFoto();
            return false;
        }
        document.addEventListener('livewire:init', function(e){
            inputFoto('input[name=foto_path]', 'img#foto_path_preview');
        });


    </script>

@endpush

