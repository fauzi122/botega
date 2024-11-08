class App {

    static tableCheckID(data){
        return `<div class="icheck-primary d-inline">
                    <input id="chk_${data}" class="check" type="checkbox" data-checkbox="icheckbox_flat-red" name="id[]" value="${data}" />
                    <label for="chk_${data}"></label>
                </div>`;
    }
}

function baseurl(){
    return $('meta[name=baseurl]').attr('content') ;
}

function formatDateDiff(date1, date2) {
    var diff = Math.abs(date1 - date2);
    var millisecondsPerDay = 1000 * 60 * 60 * 24;
    var daysDiff = Math.floor(diff / millisecondsPerDay);

    var yearsDiff = Math.floor(daysDiff / 365);
    var monthsDiff = Math.floor((daysDiff % 365) / 30);
    var weeksDiff = Math.floor((daysDiff % 365) / 7);
    var daysRemaining = daysDiff % 365 % 7;

    var result = "";
    if (yearsDiff > 0) {
        result += yearsDiff + " tahun ";
    }
    if (monthsDiff > 0) {
        result += monthsDiff + " bulan ";
    }
    if (weeksDiff > 0) {
        result += weeksDiff + " minggu ";
    }
    if (daysRemaining > 0) {
        result += daysRemaining + " hari ";
    }

    return result === '' ? date1 : result;
}


function showConfirmHapus(idtable = 'jd-table', urlhapus, onDone){
    showConfirm(idtable, "Hapus Data", "Data yang dihapus tidak dapat dikembalikan, mau dilanjutkan?", function(e, id){
        console.log("delete ",id,e);
        if(e.value){
            $.post(urlhapus, {
                '_method': 'delete',
                '_token': $('meta[name=csrf-token]').attr('content'),
                'id': id
            } ).done((ee)=>{

                if(ee.data === 0){
                    Swal.fire({
                        'title': 'Hapus data',
                        'text': 'Sepertinya tidak ada data yang berhasil dihapus',
                        'type': 'warning'
                    });
                }else{
                    if(onDone != null){
                        onDone();
                    }
                }
            }).catch((e)=>{
                console.log("error",e);
                Swal.fire({
                    'title': 'Hapus Gagal',
                    'text': 'data gagal dihapus',
                    'type': 'warning'
                });

            });
        }
    });

    // let id = [];
    // $(`table#${idtable} .check`).each((i,o)=>{
    //     if( $(o).is(':checked') ){
    //         id.push( $(o).val() );
    //     }
    // });
    // if(id.length > 0){
    //     Swal.fire({
    //         title:'Hapus data',
    //         text:'Data yang dihapus tidak dapat dikembalikan, mau dilanjutkan?',
    //         type: 'question',
    //         confirmButtonText:'Hapus aja',
    //         cancelButtonText:'Gak jadi deh',
    //         showCancelButton:true
    //     }).then((e)=>{
    //         if(e.value){
    //             $.post(urlhapus, {
    //                 '_method': 'delete',
    //                 '_token': $('meta[name=csrf-token]').attr('content'),
    //                 'id': id
    //             } ).done((ee)=>{
    //                 if(onDone != null){
    //                     onDone();
    //                 }
    //             });
    //         }
    //     });
    // }
}

function getCheckID(idtable){
    let id = [];
    $(`table#${idtable} .check`).each((i,o)=>{
        if( $(o).is(':checked') ){
            id.push( $(o).val() );
        }
    });
    return id;
}

function showConfirm(idtable = 'jd-table', title, text, onDone){
    let id = getCheckID(idtable);
    // $(`table#${idtable} .check`).each((i,o)=>{
    //     if( $(o).is(':checked') ){
    //         id.push( $(o).val() );
    //     }
    // });
    if(id.length > 0){
        Swal.fire({
            title:title,
            text:text,
            type: 'question',
            confirmButtonText:'Oke',
            cancelButtonText:'Gak jadi deh',
            showCancelButton:true
        }).then((e)=>{
            onDone(e, id);
        });
    }
}

function formatUang(nilai){
    return new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        useGrouping:true,
        minimumFractionDigits:2,
        maximumFractionDigits:2
    }).format(nilai) ;
}


async function getBase64File(selectorFile, types = 'image/'){
    return new Promise((resolve, reject)=>{
        let file = document.querySelector(selectorFile).files[0];
        if(!file){
            resolve(null) ;
            return;
        }
        if (!file.type.startsWith(types)) {
            resolve(null) ;
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            var dataurl = e.target.result;
            resolve(dataurl);
        };

        reader.readAsDataURL(file);
    });
}

function inputFoto(selectorInput, selectorImgPreview, onImageShow){

    let src = document.querySelector(selectorInput);
    let img = document.querySelector(selectorImgPreview);

    src.addEventListener('change', function(e){
         let selfile = e.target.files[0];
         if(selfile){
             var imgurl = URL.createObjectURL(selfile);

             img.src = imgurl;
             if(imgurl.length > 10){
                 if(onImageShow !== null){
                     onImageShow(imgurl);
                 }
             }
         }
    });
}

function setSelectedMenu(menukey){
    $(`li[data-key='${menukey}']`).attr('class','mm-active');
    $(`li[data-key='${menukey}']`).closest('ul').addClass('mm-show');
    $(`li[data-key='${menukey}']`).closest('ul').closest('li').addClass('mm-active');
}

function isObject(variable) {
    return typeof variable === 'object' && variable !== null;
}

function select2bind(){
    $('select.select2bind').each(function(i,o){
        let obj = o;
        let fnc = $(obj).data('fnc');
        let url = $(obj).data('url');
        let template = $(obj).data('template') ?? '';

        let parent = $(o).data('parent');

        $(o).select2({
            width:'100%',
            dropdownParent: $(o).closest(parent),
            ajax:{
                delay:250,
                dataType:'json',
                url:function(term){
                    if(typeof  window[url] === 'function'){
                        return window[url](obj,term);
                    }else{
                        return url;
                    }
                },
                processResults:function(data,params){
                    params.page = params.page || 1;
                    return {
                        results:data.items,
                        pagination:{
                            more: (params.page * 10) < data.total_items
                        }
                    };
                },
                cache:true
            },
            escapeMarkup: function(m){return m;},
            templateResult: function(r){
                if(template !== ''){
                    var f = new Function('r', "return `"+template+"`;");
                    return f(r);
                }else if(typeof window[fnc] === 'function'){
                    return window[fnc](r);
                }else {
                    return r.text ;// `${r.text}<br/><small>${r.jenis === 'P' ? 'Produk' : 'Jasa'}</small>`;
                }
            }
        });
    });
}

