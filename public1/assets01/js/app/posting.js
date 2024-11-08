
$(function(){

    if ($(".tmce").length > 0) {
        tinymce.init({
            selector: "textarea.tmce",
            theme: "modern",
            height: 300,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

        });
    }

    var jdtable = $('table#jd-table').DataTable({
        dom: 'Bfrtip',
        buttons:[
            'csv', 'copy', 'excel', 'pdf', 'print',
            {
                text:'<i class="ti-trash"></i> Hapus',
                className: 'bg-red m-l-10 btn-sm btn-hapus float-right',
                action: function(e,dt,node,config){
                    confirmHapus();
                }
            },
            {
                text:'<i class="ti-plus"></i> Tambah',
                className: 'btn-success  m-l-10 btn-sm float-right',
                action: function(e,dt,node,config){
                    $.clearForm('#form-data');
                    tinymce.get('txtabstract').setContent('');
                    tinymce.get('txtreferensi').setContent('');

                    $('li#tabrepo a').trigger('click');
                    $('li#tabfile').hide();
                    $('select[name=isprivate]').val(0).trigger('change');
                    $('select[name=tb_language_id]').prop('selectedIndex',0).change();
                    $('#formModal').modal('show');
                }
            },

        ],
        initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
            $('div.dt-buttons').css('width', '100%');
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

            { data:'title', 'width':'150px', render:function(data,type, row, meta){
                    let url =  $.baseURL() + '/repo/' + row['id'] + '/' + data;
                    var btnpreview = `<a target="_blank" href="${url}" class="text-info"><i class="fa fa-eye"></i> Pratinjau</a>`
                    return `<a class="btn btn-sm btn-cyan text-white" onclick="return edit(${row['id']})" href="javascript:void(0)">
                                    <i class="ti-pencil"></i> Edit</a> ${data} <br/>${btnpreview}`;
                }
            },
            { data:'category'},
            { data:'author'},
            { data:'hits'},


        ]
    });

    $('button.btnSimpan').click(function(){
        var form = $(this).data('target');
        $(`#${form}`).submit();
    });

    formCallback = function(state, response){
        console.log("response : ",state,response);
        if(response.result >= 1) {
          //  $('#formModal').modal('hide');
            var baseurl = $('meta[name="baseurl"]').attr('content');
            $('li#tabfile').show();
            $('li#tabfile a.nav-link').trigger('click');
            let id = response.result;
            $("form#form-data input[name=id]").val(id);
            $('table#jd-tablefile').attr('data-datasource', baseurl + '/files/data-source/' + id);
            $('table#jd-tablefile').DataTable().ajax.url(baseurl + '/files/data-source/' + id);
            $('table#jd-tablefile').DataTable().ajax.reload();

            $('table#jd-table').DataTable().ajax.reload();
            $.toast({
                heading: 'Simpan Repository',
                text: 'Item Repository berhasil di rekam',
                position: 'top-right',
                bgColor: '#179fff',
                textColor: '#fff',
                icon: 'info'
            });
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

    edit = function(id){
        var baseurl = $('meta[name="baseurl"]').attr('content');
        var url = `${baseurl}/posting/` + id;
       $.httpGet(url, {
            onSuccess: (d)=>{
                console.log('edit : ',url,d);

                $('select[name=publisher_id]').html(`<option value="${d.publisher_id}">${d.publisher}</option>`);
                $('input[name=city]').val(d.publisher_city);
                $('input[name=prov]').val(d.publisher_prov);
                $('input[name=country]').val(d.publisher_country);
                $('select[name=tb_subject_id]').html(`<option value="${d.tb_subject_id}">${d.subject}</option>`)
                $('select[name=tb_category_id]').html(`<option value="${d.tb_category_id}">${d.category}</option>`)
                $('table#jd-tablefile').attr('data-datasource', baseurl + '/files/data-source/' + id);
                $('table#jd-tablefile').DataTable().ajax.url(baseurl + '/files/data-source/' + id);
                $('table#jd-tablefile').DataTable().ajax.reload();

                $('li#tabfile').show();
                $('li#tabrepo').trigger('click');
               // $('select[name=wilayah_id]').append(`<option value="${d.wilayah_id}">${d.wilayah} (${d.wilayah_id})</option>`);
                $.bindDataToForm('#form-data', d);
                tinymce.get('txtabstract').setContent(d.abstract ?? '');
                tinymce.get('txtreferensi').setContent(d.referensi ?? '');
                $('#formModal').modal('show');

            }
       });
    };

    formValidator = function(){
        var req1 =  $('select[name=publisher_id]').val() === '[LAINNYA]' ? true:false;
        $('textarea[name=referensi]').val(  tinymce.get('txtreferensi').getContent() );
        $('textarea[name=abstract]').val(  tinymce.get('txtabstract').getContent() );
        return {
            'rules' : {
                'title': { 'required' :true },
                'author': { 'required' :true },
                'publisher':{'required': req1 }
            }
        };
    };

    $('select[name=publisher_id]').on('change', function(){
        var val = $(this).val();
        if(val === '[LAINNYA]'){
            $('input[name=publisher]').prop('required',true);
            $('.publisher-lainnya').show();
        }else{

            $('input[name=publisher]').removeProp('required');
            $('.publisher-lainnya').hide();
            bindKotaProvNegPublisher(val);
        }
    });

    bindKotaProvNegPublisher = function(id){
        var baseurl = $('meta[name=baseurl]').attr('content');
        var url = baseurl + `/publisher/${id}`;
        $.get(url).done(function(E){
            console.log('publisher',E);
            $('input[name=city]').val(E.city);
            $('input[name=province]').val(E.province);
            $('input[name=country]').val(E.country);
        });
    };

    confirmHapus = function(){
        swal({
            title: 'Perhatian!!! Hapus Data',
            text: 'Data akan dihapus secara permanen! Mau dilanjutkan?',
            icon: 'warning',
            buttons: true,
            dangerMode: true
        }).then(function(r){
            if(r){
                var baseurl = $('meta[name="baseurl"]').attr('content');
                var url = `${baseurl}/posting/delete`;
                $.httpPost( url,
                    $("table#jd-table").getFormData(),
                    {
                        onSuccess:function(r){
                            console.log('success = ', r);
                            if(r.result > 0){
                                $('table#jd-table').DataTable().ajax.reload();
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
