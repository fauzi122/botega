<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Level Member</h5>
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

                        <!-- resources/views/livewire/example-form.blade.php -->


                    <div class="mb-3">
                        <label for="actions" class="form-label">Actions:</label>
                        <input type="text" readonly id="actions" class="form-control" wire:model="actions">
                    </div>
                    <div class="mb-3">
                        @php
                            $jsonPayload = json_decode($payload, true);
                        @endphp
                        <label>Deskripsi</label>
                        <p>{{$jsonPayload['description'] ?? ''}}</p>
                        <label>Data</label>
                        <p>
                            @php
                                $data = $jsonPayload['data'] ?? [];
                                foreach($data as $k=>$v){
                                    echo "$k: $v <br/>";
                                }
                            @endphp
                        </p>

                    </div>



                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

</div>
