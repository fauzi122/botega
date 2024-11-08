<div>
    <div class="table-rep-plugin">
        <div class="table-wrapper">
            <div class="row">
                <div class="btn-toolbar">
                    <div  class="btn-group focus-btn-group pull-right ">
                        <button wire:click="$dispatch('newForm')" class="btn btn-primary "><i class="mdi mdi-plus-circle"></i> Tambah</button>
                        &nbsp;
                        <button wire:click='showConfirmDelete' class="btn btn-danger "><i class="mdi mdi-trash-can"></i> Hapus</button>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-10">
                    <div class="col-md-1">
                        <label>
                            <select  class="form-control" name="limit" wire:model="limit" wire:click="cari">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="cari" wire:keyup="cari" wire:model="keyword" placeholder="Cari ..." />
                </div>
            </div>

            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th style="width:20px">#</th>
                    <th style="width:20px">No</th>
                    <th>Tanggal Permohonan</th>
                    <th>Nama Member</th>
                    <th>Status</th>
                    <th style="width: 20px">Sunting</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>
                            <input type="checkbox" value="{{$item->id}}" wire:model="ids" class="form-check-input" />
                        </td>
                        <td>{{  $no++  }}</td>
                        <td>{{  $item->level_name  }}</td>
                        <td>{{  $item->level  }}</td>
                        <td>
                            <input wire:click="publishlevel('{{$item->id}}', {{$item->publish}})" value="{{$item->id}}" type="checkbox" id="publish_{{$item->id}}" readonly switch="none" {{ $item->publish == 1 ? 'checked' : ''  }} />
                            <label for="publish_{{$item->id}}" data-on-label="On" data-off-label="Off"></label>

                        </td>
                        <td>
                            <a class="btn btn-sm btn-info btn-rounded" href="#"
                               wire:click="edit('{{$item->id}}')"> <i class="mdi mdi-pencil-circle"></i> Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-11">
                        <label>Halaman</label>
                        {{  $data->links('livewire.livewire-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
