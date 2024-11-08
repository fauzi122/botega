
$(function(){

    initiDropify = function(opt = {defaultFile : ''}){
        var maxSize = $('meta[name=max-file-upload]').attr('content') ?? '10M';
        $('#filerepo div').remove();
        $('#filerepo').append(`<input type="file" class="dropify" data-default-file="${opt.defaultFile}" data-max-file-size="${maxSize}" name="berkas" />`);
        $('.dropify').dropify({
            defaultFile: opt.defaultFile
        });
    };

    var jdtablefile = $('table#jd-tablefile').DataTable({
        dom: 'Bfrtip',
        buttons:[
            'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="ti-trash"></i> Hapus',
                className: 'bg-red m-l-10 btn-sm btn-hapus float-right',
                action: function(e,dt,node,config){
                    confirmHapusFile();
                }
            },
            {
                text:'<i class="ti-plus"></i> Tambah',
                className: 'btn-success  m-l-10 btn-sm float-right',
                action: function(e,dt,node,config){
                    $.clearForm('#form-data-file');
                    $('select[name=group_archive_id]').prop('selectedIndex',0).trigger('change');
                    $('#form-data-file select[name=isprivate]').prop('selectedIndex',0).trigger('change');
                    var baseurl = $('meta[name="baseurl"]').attr('content');
                    var id = $('form#form-data input[name=id]').val();
                    $('form#form-data-file').attr('action', baseurl + '/files/' + id);
                    initiDropify();
                    $('#formModalFile').modal('show');
                }
            },

        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
            $('div.dt-buttons').css('width', '100%');
            this.api().columns.adjust();
        },
        processing: true,
        serverSide: true,
        ajax:{
            url:$('table#jd-tablefile').data('datasource'),
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

            { data:'alias', 'width':'150px', render:function(data,type, row, meta){
                    return `<small class="text-muted">${row['nama_group']}</small><br/><a class="btn btn-sm btn-cyan text-white" onclick="return editfile(${row['id']})" href="javascript:void(0)">
                                    <i class="ti-pencil"></i> Edit</a> ${data} `;
                }
            },
            { data:'filename', render:function(data,type,row){
                    var url = $.baseURL() + `/files/download/${row['id']}/${row['filename']}`
                    let fname = row['filename'].toString().replaceAll(' ', '-').replaceAll('/','-').replaceAll('\\','-');
                    var url2 = $.baseURL() + `/repo/files/${row['id']}/download/${fname}`
                    var btncopylink = `<br/><a href="void:javascript()" class="text-info" onclick="copylink('${url2}')"><i class="fa fa-copy"></i> Salin tautan unduhan</a>`;

                    let btnunzip = row['mime'] === "application/zip" ? `<br/><a data-label="${data}" href="javascript:void(0);" data-id="${row['id']}" class="btn btn-sm btn-primary btn-extract-zip"><i class="fa fa-file"></i> Ekstrak berkas zip</a>` : '';
                    return `<a title="unduh file ${data}" data-toggle="tooltip" class="${url}" href="${url}"><i class="fa fa-download"></i> ${data}</a>${btncopylink} ${btnunzip}`;
                }
            },
            { data:'last_download', render:function(data,type,row){
                    if(data === null)return '';
                    var d = new Date(data);
                    return moment(d).format('dddd, DD MMMM YYYY, HH:mm:ss');
                }},
            { data:'hits'},


        ]
    });


    copylink = function(t){
        navigator.clipboard.writeText(t);
        $.toast({
            heading: 'Salin tautan',
            text: 'Tautan telah di simpan di clipboard',
            position: 'mid-center',
            bgColor: '#179fff',
            textColor: '#fff',
            icon: 'info',
            hideAfter: 3000
        });
    }

    $("table#jd-tablefile").on("click", ".btn-extract-zip", function(){
        let id = $(this).data("id");
        let label = $(this).data('label');
        var baseurl = $('meta[name="baseurl"]').attr('content');

        swal({
           title: 'Ekstrak file zip',
           text: `Apakah anda yakin akan ekstrak file  "${label}" ? `,
           icon: 'warning',
           buttons:true
        }).then((v)=>{
             if(v === true){
                 $.toast({
                     heading: 'Ekstrak file',
                     text: 'Tunggu hingga progress extraksi file sedang berlangsung',
                     position: 'mid-center',
                     bgColor: '#179fff',
                     textColor: '#fff',
                     icon: 'info',
                     hideAfter: 6000
                 });

                 let url = baseurl + `/files/extract/${id}/${label}`;
                 let formData = new FormData();
                 formData.append("id", id);

                 $.httpPatch(url, formData, {
                     onSuccess:(response)=>{
                         $('table#jd-tablefile').DataTable().ajax.reload();
                     },
                     onFailed:(xhr)=>{
                         swal({
                            title:'Gagal ekstrak file',
                            text: 'Terjadi kesalahan dalam ekstraksi file',
                            icon:'error'
                         });
                     }
                 })
             }
        });

    });

    formCallbackFile = function(state, response){
        console.log("response : ",state,response);
        if(response.result >= 1) {
            $('#formModalFile').modal('hide');
            $('table#jd-tablefile').DataTable().ajax.reload();
        }else{
            swal({
                title: 'Perhatian!!! Simpan Data',
                text: response.message,
                icon: 'warning',
                buttons: true,
                dangerMode: true
            });
        }
    };

    editfile = function(id){
        var baseurl = $('meta[name="baseurl"]').attr('content');
        var url = `${baseurl}/files/` + id;
       $.httpGet(url, {
            onSuccess: (d)=>{
                console.log('edit : ',url,d);
                $('form#form-data-file').attr('action', baseurl + '/files/' + d.tb_items_id);
               // $('select[name=wilayah_id]').append(`<option value="${d.wilayah_id}">${d.wilayah} (${d.wilayah_id})</option>`);
                $.bindDataToForm('#form-data-file', d);

                $('#form-data-file select[name=isprivate]').val(d.isprivate == true?1:0).trigger('change');
                initiDropify({ defaultFile: d.url });
                $('#formModalFile').modal('show');
            }
       });
    };

    formValidatorFile = function(){
        return {
            'rules' : {
                'alias': { 'required' :true },
              //  'berkas': { 'required' :true },
                'group_archive_id': { 'required' :true },
            }
        };
    };

    confirmHapusFile = function(){
        swal({
            title: 'Perhatian!!! Hapus Data',
            text: 'Data akan dihapus secara permanen! Mau dilanjutkan?',
            icon: 'warning',
            buttons: true,
            dangerMode: true
        }).then(function(r){
            if(r){
                var baseurl = $('meta[name="baseurl"]').attr('content');
                var url = `${baseurl}/files/delete`;
                $.httpPost( url,
                    $("table#jd-tablefile").getFormData(),
                    {
                        onSuccess:function(r){
                            console.log('success = ', r);
                            if(r.result > 0){
                                $('table#jd-tablefile').DataTable().ajax.reload();
                            }else{
                                swal({
                                    title:'Gagal Hapus',
                                    text:'Maaf, Data gagal dihapus',
                                    icon:'warning'
                                });
                            }
                        },
                        onFailed: function(e){
                            swal({
                                title:'Koneksi ke server',
                                text: 'Maaf, terjadi kesalahan di sisi server',
                                icon: 'error'
                            });
                        }
                    }
                );
            }
        });
    };


});
