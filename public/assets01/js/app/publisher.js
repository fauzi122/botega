
$(function(){

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
                    $.clearForm('#formModal');
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

            { data:'publisher', 'width':'150px', render:function(data,type, row, meta){
                    return `<a class="btn btn-sm btn-cyan text-white" onclick="return edit(${row['id']})" href="javascript:void(0)">
                                    <i class="ti-pencil"></i> Edit</a> ${data}`;
                }
            },
            { data:'city'},
            { data:'province'},
            { data:'country'},


        ]
    });

    $('button.btnSimpan').click(function(){
        var form = $(this).data('target');
        $(`#${form}`).submit();
    });

    formCallback = function(state, response){
        console.log("response : ",state,response);
        if(response.result === 1) {
            $('#formModal').modal('hide');
            $('table#jd-table').DataTable().ajax.reload();
        }else{

        }
    };

    edit = function(id){
        var baseurl = $('meta[name="baseurl"]').attr('content');
        var url = `${baseurl}/publisher/` + id;
       $.httpGet(url, {
            onSuccess: (d)=>{
                console.log('edit : ',url,d);
               // $('select[name=wilayah_id]').append(`<option value="${d.wilayah_id}">${d.wilayah} (${d.wilayah_id})</option>`);
                $.bindDataToForm('#form-data', d);
                $('#formModal').modal('show');
            }
       });
    };

    formValidator = function(){
        return {
            'rules' : {
                'publisher': { 'required' :true },
                'city': { 'required' :true },
            }
        };
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
                var url = `${baseurl}/publisher/delete`;
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
