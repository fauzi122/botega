var wire;

document.addEventListener('DOMContentLoaded', function(){
    setSelectedMenu('reward');
    buildTable();
    inputFoto('input[name=file_image]', 'img#img-preview', ()=>{
        $('#img-preview').show();
    });

    $('#btnhapus').click(function(e){
        e.preventDefault();
        Swal.fire({
            title:'Hapus gambar',
            text:'Gambar reward yang telah dihapus tidak dapat dikembalikan',
            type:'warning',
            showCancelButton:true,
            cancelButtonText:'Tidak jadi'
        }).then((v)=>{
            if(v.value === true){
                wire.removeImage().then(()=>{
                    $('#img-preview').hide();
                    $('#btnhapus').hide();
                });
            }
        });
        return false;
    });
    $('#btnhapus').hide();
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.reward.form')[0];
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
                    $('#modalform').modal('show');
                    wire.newForm();
                    $('img#img-preview').hide();
                    $('#btnhapus').hide();
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
            { data: 'code'},
            { data: 'name', render:(data, type, row, meta)=>{
                let time = (new Date()).getTime();
                let img = row['path_image'] === '' ? '' : `<img src='${row['path_image']}/?time=${time}' style="width:100px; height:80px; object-fit: cover;" />`;
                return `${img}${data}`;

            }},
            { data: 'descriptions'},
            { data: 'point'},
            { data: 'expired_at'},
            { data: 'id', render:(data, type, row, meta) => {
                    return `<button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')"><i class="mdi mdi-pencil"></i> Edit</button>`;
                }},

        ]
    });
}

async function save(){
    let f = await getBase64File("input[name=file_image]");
    if(f !== null){
        wire.set('file_image', f);
    }

    wire.save().then((_)={

    });
}
function editdata(id){
    $('form#form_data')[0].reset();
    $('#modalform').modal('show');
    $('#img-preview').hide();
    $('#btnhapus').hide();
    wire.edit(id).then((e)=>{
        let imgsrc = wire.get('imgsrc');
        $('img#img-preview').attr('src', imgsrc);
        if(imgsrc.length > 10){
            $('#btnhapus').show();
            $('#img-preview').show();
        }

    });
}
