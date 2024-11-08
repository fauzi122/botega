$(function () {
    'use strict';

    $(document).on('click', '.password-indicator', function () {
        var PASSWORD_FIELD = $(this).closest('.password-wrapper').find('input');
        $(this).toggleClass('fa-eye-slash');
        var attrType = PASSWORD_FIELD.attr('type');
        if(attrType == 'password') {
            PASSWORD_FIELD.attr('type', 'text');
        } else {
            PASSWORD_FIELD.attr('type', 'password');
        }
    })

    $('.login-popover').popover({
        trigger: 'hover',
        container: 'body'
    })

    $("#commonForm").validate({
        submitHandler: function(form){
            $(form).hide();
            $('div.loading-svg').show();

            var formData = new FormData();
            var baseuri = $('meta[name=base-uri]').attr('content');
            var uri = baseuri + '/login';

            formData.append('nip', $('input[name=nip]').val());
            formData.append('password', $('input[name=password]').val());

            $.ajax({
                url: uri,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                dataType: 'text',
                cache: false,
                async: true,
                data: formData,
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                success:function(response, stts, xhr){
                    $(form).show();
                    $('div.loading-svg').hide();

                    try{response = JSON.parse(response);}catch (e) {}

                    if(response.result === 1){
                        window.location = baseuri + '/dashboard';
                    }else {
                        swal({
                            title: 'Gagal Login',
                            text: response.message,
                            icon: 'warning'
                        });
                    }
                },
                error:function(xhr, stts, errThrow){
                    $(form).show();
                    $('div.loading-svg').hide();
                    swal({
                        title: 'Gagal Login',
                        text: 'Terjadi kesalahan koneksi ke server',
                        icon: 'warning'
                    });
                },
                xhr:function(x){
                    console.log('xhr - ', x);

                    var _xhr = $.ajaxSettings.xhr();
                    if(_xhr.upload){
                        _xhr.upload.addEventListener('progress', function(e){
                            var percent = 0;
                            var pos = e.loaded || e.position;
                            var total = e.total;

                            if(e.lengthComputable){
                                percent = Math.ceil(pos/total * 100);
                            }
                            if(typeof onProgress === 'function'){
                                onProgress(percent);
                            }
                        });
                    }
                    return _xhr;
                }
            });
        },
        rules: {
            nip: { required: true, },
            password: { required: true, }
        },
        messages: {
            nip: {
                required: "NIP Pengguna harap diisikan",
                number: "ID Pengguna berupa angka"
            },
            password: {
                required: "Password harap diisikan",
            }
        }
    });

})
