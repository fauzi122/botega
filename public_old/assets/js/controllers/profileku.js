var wire;

document.addEventListener('DOMContentLoaded', function(){

    select2bind();
    inputFoto('input[name=foto_path]', 'img#img-preview');
});

document.addEventListener('livewire:initialized', function(){
    wire = Livewire.getByName('admin.profileku')[0];
    wire.showdata().then(()=>{
        var urlfoto = wire.get('foto_path');
        if(urlfoto === '' || urlfoto === null){
            $('img#img-preview').attr('src', '');
        }else {
            $('img#img-preview').attr('src', urlfoto);
        }
    });
});

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
