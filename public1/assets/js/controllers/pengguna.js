var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('pengguna');
    buildTable();
    select2bind();
    inputFoto('input[name=foto_path]', 'img#img-preview');
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.pengguna.form')[0];
    wire.on('refreshData', function(){
        $('#jd-table').DataTable().ajax.reload();
    });
});

function buildTable(){
    $('#jd-table').DataTable({
        dom: 'Bfrtip',
        buttons:[

            {
                text:'<i class="mdi mdi-plus-circle"></i> Tambah',
                action: (e,dt,node, c)=>{
                    $('form#form_data')[0].reset();
                    $('#btn-hapus-gambar').hide();
                    $('#modalform').modal('show');
                    wire.newForm();
                },
                className: 'btn-success'
            }, 'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="mdi mdi-trash-can-outline"></i> Hapus',
                action: (e,dt,node, c)=>{
                    let url =  $('#jd-table').data('urlaction');
                    showConfirmHapus('jd-table', url , ()=>{
                        $('table#jd-table').DataTable().ajax.reload();
                    });
                },
                className: 'btn-danger'
            },

        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        },
        processing: true,
        serverSide: true,
        ajax:{
            url:$('table#jd-table').data('datasource'),
            method: 'GET'
        },
        order: [[1, 'asc']],
        columns:[
            { data:'id', sortable:false, width:'20px', target:0,
                searchable:false,
                render: function(data, type,row, meta){
                    return  App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
                }
            },
            {data:'first_name'},
            {data:'sub_kategori'},
            {data:'nik', render:(data, type, row) => {
                return `NIK: ${data ?? ''}<br/>NPWP: ${row['npwp'] ?? ''}`;
            }},
            {data:'email', render:(data, type, row, meta) => {
                return `Email: ${data}<br/>
                        HP: ${row['hp'] ?? ''} / ${row['wa'] ?? ''}`;
            }},
            {data:'role'},
            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdatae('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>
                            <button class="btn btn-sm btn-rounded btn-success" onclick="reset('${row['id']}')" title="Reset Password"><i class="fa fa-recycle"></i></button>
                            `;
                }},

        ]
    });
}

function reset(id){
    wire.prepareReset(id).then(()=>{
        $('div#modalReset').modal('show');
    });
}

function resetsandi(){
    wire.resetSandi().then(()=>{
        $('div#modalReset').modal('hide');
    });
}

function editdatae(id){
    $('form#form_data')[0].reset();
    $('img#img-preview').attr('src', '');
    $('button#btn-hapus-gambar').hide();

    wire.edit(id).then(()=> {
        var foto = wire.get('foto_path');
        if (foto === '' || foto == null || foto == undefined) {

            $('button#btn-hapus-gambar').hide();
        }else{
             foto = foto + '?t=' + Date.now();
            $('img#img-preview').attr('src', foto);
            $('button#btn-hapus-gambar').show();
        }
    });
    $('#modalform').modal('show');
}

function hapusgambar(id){
    Swal.fire({
        title:'Hapus gambar',
        text:'Gambar akan dihapus, apakah tetap akan dilanjutkan?',
        type:'question',
        showCancelButton:true,
        cancelButtonText:'Tidak jadi'
    }).then((e)=>{
        if(e.value === true){
            wire.hapusgambar(id).then((e)=>{
                $('img#img-preview').attr('src', '');
                $('#btn-hapus-gambar').hide();
            });
        }
    });
    return false;
}

async function save(){
    let file = await getBase64File('input[name=foto_path]');
    console.log(file);
    wire.set('file', file);
    wire.save().then(()=>{
        let foto = wire.get('foto_path');
        if (foto === '' || foto == null || foto == undefined) {}else{
            foto = foto + '?t=' + Date.now();
            $('img#img-preview').attr('src', foto);
            $('button#btn-hapus-gambar').show();
        }
    });
}
