//require('./bootstrap');


class App {

    static tableCheckID(data){
        return `<div class="icheck-primary d-inline">
                    <input id="chk_${data}" class="check" type="checkbox" data-checkbox="icheckbox_flat-red" name="id[]" value="${data}" />
                    <label for="chk_${data}"></label>
                </div>`;
    }
}

(function($){

    $(document).ready(function(){
        $.select2Bind();
        $('select[data-widget=select2]').each(function(i,o){

            $(o).select2({
                dropdownParent: $($(o).data('parent'))
            });

        });
       // $.dataTableBind();
        $.btnHapusDataTable();
        $.formSubmitSubClass();
        $(document).tooltip({
            selector: '[data-toggle=tooltip]'
        })
    });

    $.implode = function(delimiter, data){
        js = {};
        try{
            js = $.parseJSON(data);
        }catch (e){}
        var t = '';
        for(var nn in js){
            var val = js[nn];
            console.log("isi nn ", val);
            t += (t === '' ? '' : delimiter) + val;
        }
        return t;
    };

    $.htmlentitiesdecode = function(text){
        var entities = [
            ['amp', '&'],
            ['apos', '\''],
            ['#x27', '\''],
            ['#x2F', '/'],
            ['#39', '\''],
            ['#47', '/'],
            ['lt', '<'],
            ['gt', '>'],
            ['nbsp', ' '],
            ['quot', '"']
        ];

        try {
            for (var i = 0, max = entities.length; i < max; ++i)
                text = text.replace(new RegExp('&' + entities[i][0] + ';', 'g'), entities[i][1]);

            return text;
        }catch (e){}
        return text;
    };

    $.btnHapusDataTable = function(){
        $('.btn-hapus-datatable').each(function(i,o){
            var url = $(o).data('url');
            var title = $(o).data('title');
            var selectorDataTable = $(o).data('datatable');

            $(o).click(function(e){
                swal({
                    title: `Perhatian!!! Hapus ${title}`,
                    text: `Menghapus data ${title} mengakibatkan data yang sudah dihapus tidak dapat di kembalikan!`,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true
                }).then(function(r){
                    if(r){
                        $.httpPost(url,
                            $(selectorDataTable).getFormData(),
                            {
                                onSuccess:function(r){
                                    if(r.status){
                                        $(selectorDataTable).DataTable().ajax.reload();
                                    }else{
                                        swal({
                                            title:'Gagal Hapus',
                                            text:`Maaf, ${title} gagal dihapus`,
                                            icon:'danger'
                                        });
                                    }
                                },
                                onFailed: function(e){
                                    swal({
                                        title:'Koneksi ke server',
                                        text: 'Maaf, terjadi kesalahan di sisi server',
                                        icon: 'warning'
                                    });
                                }
                            }
                        );
                    }
                });
                e.preventDefault();
                return false;

            });

        });
    };

    $.dataTableBind = function(){

        $('.datatablebind').each(function(i,o){
            var url = $(o).data('url');
            var func = $(o).data('cols');
            var col = [];

            if(typeof  window[func] === 'function'){
                col = window[func]();
            }

            $(o).DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url:url,
                    method: 'GET'
                },
                order: [[1, 'asc']],
                columns: col
            });

        });
    };

    $.fn.validation = function(rules){
        var r = rules['rules'];
        var msgs = rules['messages'] ?? {};
        var defaultMsg = '';
        var invalid = 0;
        var form = $(this);

        form.find('*').filter(':input').each(function(i,o){
            try{
                var rule = r[o.name];
            }catch(e){
            }


            if(rule !== undefined){
                var lbl = $(o).parent().find('span.e-validate');
                var msg = '';

                var req = o.value;
                if(o.type === 'radio' || o.type === 'checkbox'){
                    req = $('input[name=' + o.name + ']:checked').val() ?? '';
                }

                if(rule['required'] === true && req === '' ){
                    invalid++;
                    defaultMsg = 'Bidang ini harus diisikan';
                    msg = msgs['required'] ?? defaultMsg;
                    if(typeof msg === 'object'){
                        msg = msg[o.name] ?? defaultMsg;
                    }

                    $(o).on('keyup',function(e){
                        var isi = $(this).val().trim();
                        if(isi !== ''){
                            $(o).removeClass('is-invalid');
                        }else{
                            $(o).addClass('is-invalid');
                            lbl = $(o).next('span.e-validate');
                            if(lbl.length <= 0) {
                                $(o).after(`<span class="error invalid-feedback e-validate">${msg}</span>`);
                            }else{
                                $(lbl).html(msg);
                            }
                        }
                    });
                }

                let minLen = rule['minLength'] ?? 0;
                if(minLen > 0 && o.value.length < minLen  ){
                    invalid++;
                    defaultMsg = `Harus diisikan minimal ${minLen} karakter `;
                    var tmsg = msgs['minLength'] ?? defaultMsg;
                    if(typeof tmsg === 'object'){
                        tmsg = tmsg[o.name] ?? defaultMsg;
                    }
                    msg += (msg===''?'':', ') + tmsg;

                    $(o).on('keyup',function(e){
                        var isi = $(this).val().trim().length;
                        if(isi >= minLen ){
                            $(o).removeClass('is-invalid');
                        }else{
                            $(o).addClass('is-invalid');
                            lbl = $(o).next('span.e-validate');
                            if(lbl.length <= 0) {
                                $(o).after(`<span class="error invalid-feedback e-validate">${msg}</span>`);
                            }else{
                                $(lbl).html(msg);
                            }
                        }
                    });
                }

                let maxLen = rule['maxLength'] ?? 0;
                if(maxLen > 0 && o.value.length > maxLen  ){
                    invalid++;
                    defaultMsg = `Harus diisikan maksimal ${maxLen} karakter `;
                    var tmsg = msgs['maxLength'] ?? defaultMsg;
                    if(typeof tmsg === 'object'){
                        tmsg = tmsg[o.name] ?? defaultMsg;
                    }
                    msg += (msg===''?'':', ') + tmsg;
                    $(o).addClass('is-invalid');

                    $(o).on('keyup',function(e){
                        var isi = $(this).val().trim().length;
                        if(isi <= maxLen ){
                            $(o).removeClass('is-invalid');
                        }else{
                            $(o).addClass('is-invalid');
                            lbl = $(o).next('span.e-validate');
                            if(lbl.length <= 0) {
                                $(o).after(`<span class="error invalid-feedback e-validate">${msg}</span>`);
                            }else{
                                $(lbl).html(msg);
                            }
                        }
                    });
                }

                let email = rule['valid_email'] ?? false;
                if(email === true){
                    let t = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(o.value);
                    if(t === false && o.value !== ''){

                        invalid++;
                        defaultMsg = `Format email tidak benar`;
                        var tmsg = msgs['valid_email'] ?? defaultMsg;
                        if(typeof tmsg === 'object'){
                            tmsg = tmsg[o.name] ?? defaultMsg;
                        }
                        msg += (msg===''?'':', ') + tmsg;
                    }
                }

                let match = rule['match'] ?? '';
                if( match !== '' ){
                    var v1 = $(o).val();
                    defaultMsg = `Bidang ini sama dengan ${match} `;
                    var tmsg = msgs['match'] ?? defaultMsg;
                    if(typeof tmsg  === 'object'){
                        tmsg  = tmsg[o.name] ?? defaultMsg;
                    }

                    form.find('[name='+match+']').each(function(i,os){
                        if(os.value !== v1){
                            invalid++;
                            msg += (msg===''?'':', ') + tmsg;
                        }
                    });
                }

                if(msg !== ''){
                    $(o).addClass('is-invalid');

                    if(lbl.length <= 0) {
                        $(o).parent().append(`<span class="error invalid-feedback e-validate">${msg}</span>`);
                    }else{
                        $(lbl).html(msg);
                    }
                }else{
                    $(o).removeClass('is-invalid');
                    if(lbl.length > 0) lbl.remove();
                }
            }
        });

        return invalid == 0;
    };

    $(document).on('click','.btn-submit', function(){
        var target = $(this).data('target');
        $(target).submit();
    });

    $.fn.getFormData = function(){
        var formData = new FormData();
        var form = $(this);
        form.find('*').filter(':input').each(function(i,o){
            if(o.type === 'file'){
                var file = $(o)[0].files[0];
                formData.append(o.name, file);
            }else if(o.type === 'checkbox' || o.type === 'radio'){
                if(o.checked === true){
                    formData.append( o.name, o.value);
                }
            }else{
                if($(o).data('widget') === 'price'){
                    console.log('price ' + $(o).unmask());
                    formData.append(o.name, $(o).unmask());
                }else {
                    formData.append(o.name, o.value);
                }
            }

        });

        return formData;
    };


    /**
     * example
     *  <select name="pemilik_tb_pengguna_id"
     *   data-url="{{ url('/') }}"
     *   data-template="${r.text}<br/>Kelurahan ${r.wil} Provinsi ${r.prov}<br/><small>No. HP : ${r.hp}</small>"
     *   class="select2bind"></select>
     */
    $.select2Bind = function(){
        $('select.select2bind').each(function(i,o){
            let obj = o;
            let fnc = $(obj).data('fnc');
            let url = $(obj).data('url');
            let template = $(obj).data('template') ?? '';

            let parent = $(o).data('parent');

            $(o).select2({
                width:'100%',
                dropdownParent: $(parent),
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
    };

    $.formSubmitSubClass = function(){
        $('form.async').each(function(i,o){
            $(o).on('submit', function(e){
                var sRules = $(o).data('rule');
                var sonSuccess = $(o).data('success');
                var sonFailed = $(o).data('failed');
                var rule = {};
                var onSuccess, onFailed;

                if(typeof window[sRules] === 'function'){ rule = window[sRules](); }
                if(typeof window[sonSuccess] === 'function'){ onSuccess = window[sonSuccess]; }
                if(typeof window[sonFailed] === 'function'){ onFailed = window[sonFailed]; }

                $.formDoSubmit(o, {
                    rulesValidate:rule,
                    onSuccess:onSuccess,
                    onFailed: onFailed
                })
                e.preventDefault();
                return false;
            });
        });
    };

    $.formDoSubmit = function(selectorForm, {rulesValidate, onSuccess, onFailed}){
        var isValid = true;

        var typerule = typeof rulesValidate;
        if(typerule === 'object' || typerule === 'function'){
            isValid = $(selectorForm).validation(rulesValidate);
        }
        if(!isValid) return false;

        var id = $(selectorForm).attr('id');
        var btn = $(`.btn-submit[data-target="#${id}"]`);
        var form = $(selectorForm);
        var fnCallBack = $(selectorForm).data('fncallback');
        var formData = $(form).getFormData();

        btn.attr('disabled','disabled');//matikan tombol simpan
        var pgbar = form.closest('div').find('.pg-bar .pgbar-value');
        var pgbCtrl = form.closest('div').find('.pg-bar');

        if(pgbar.length <= 0){
            form.closest('div').prepend(`<div class="progress pg-bar col-md-12" style="display:block ;">
                                            <div  class="progress-bar pgbar-value bg-info" style="width:10%;height: 15px;">10%</div>
                                        </div>`);
            pgbar = form.closest('div').find('.pg-bar .pgbar-value');
            pgbCtrl = form.closest('div').find('.pg-bar');
        }


        pgbCtrl.show();
        form.hide();

        console.log('Pgbar : ')
        console.log(pgbar);

        $.httpReq(
            form.attr('action'),
            form.attr('method') ?? 'POST',
            'multipart/form-data',
            'text',
            formData,
            function(response){
                form.show();
                pgbCtrl.hide();
                btn.removeAttr('disabled');
                console.log(response);
                console.log(onSuccess);

                if(typeof window[fnCallBack] === 'function'){
                    window[fnCallBack](1,response );
                }
                if(typeof onSuccess === 'function'){
                    onSuccess(response);
                }else{
                //    toastr.options = {"positionClass": "toast-bottom-right"};
                 //   toastr.success(response.msg);
                }
            },
            function(err){

                form.show();
                pgbCtrl.hide();
                btn.removeAttr('disabled');

                if(typeof window[fnCallBack] === 'function'){
                    window[fnCallBack](-1, err);
                }
                if(typeof onFailed === 'function'){
                    onFailed(err);
                }else{
                    swal({
                        title: 'Gagal Simpan',
                        text: 'Terjadi kesalahan koneksi ke server',
                        icon: 'warning'
                    });
                }
            },
            function(percent){
                // console.log('isi percent ' + percent);
                pgbar.css('width', `${percent}%`);
                pgbar.html(`${percent} %`);
            }
        );

        return false;
    };

    $.formSubmit = function(selectorForm, {rulesValidate, onSuccess, onFailed}){

        $(document).on('submit', selectorForm, function(e){
            $.formDoSubmit(selectorForm, {
                rulesValidate:rulesValidate,
                onSuccess:onSuccess,
                onFailed: onFailed
            });
            e.preventDefault();
            return false;
        });

    };

    $.httpGet = function(url, {formData, onSuccess, onFailed, onProgress}){
        if(formData === undefined){
            formData = new FormData();
        }
        $.httpReq(url, 'GET', 'multipart/form-data', 'text', formData, onSuccess, onFailed, onProgress);
    };

    $.httpPost = function(url, formData, {onSuccess, onFailed, onProgress}){
        $.httpReq(url, 'POST', 'multipart/form-data', 'text', formData, onSuccess, onFailed, onProgress);
    };

    $.httpDelete = function(url, formData, {onSuccess, onFailed, onProgress}){
        $.httpReq(url, 'DELETE', 'application/x-www-form-urlencoded', 'text', formData, onSuccess, onFailed, onProgress);
    };

    $.httpPatch = function(url, formData, {onSuccess, onFailed, onProgress}){
        $.httpReq(url, 'PATCH', 'multipart/form-data', 'text', formData, onSuccess, onFailed, onProgress);
    };


    $.httpReq = function(url, method, enctype, dataType, formData, onSuccess, onFailed, onProgress){

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: method,
            dataType: dataType,
            cache: false,
            async: true,
            data: formData,
            processData: false,
            contentType: false,
            enctype: enctype,
            success:function(response, stts, xhr){
                try{response = JSON.parse(response);}catch (e) {}
                if(typeof onSuccess === 'function'){
                    onSuccess(response);
                }
            },
            error:function(xhr, stts, errThrow){
                if(typeof onFailed === 'function'){
                    onFailed(xhr);
                }
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
    };

    $.dropifySetImage = function( selector, file ){
        var ev = $(selector).dropify({
            defaultFile: ''
        });
        ev = ev.data('dropify');
        ev.resetPreview();
        ev.clearElement();
        ev.settings.defaultFile = file;
        ev.destroy();
        ev.init();
    };

    $.bindDataToForm = function(selectorForm, data){
        var r = data;
        $(selectorForm).find('*').filter(':input').each(function(i,o){
            try{
             //   if(o.name === '_key'){
             //       $(o).val(r['id']);
             //   }else {
                    if(o.type === 'radio' || o.type === 'checkbox'){
                        $(o).filter('[value='+r[o.name]+']').prop('checked', true);
                    }else if(o.type === 'file'){

                    }else if(o.name === '_token') {
                        $(o).val( $('meta[name="csrf-token"]').attr('content') );
                    }else{
                        $(o).val(r[o.name]);
                    }
              //  }

            }catch (e) { }

            $(o).trigger('focus');
            $(o).trigger('change');
            $(o).trigger('focusout');

        });
    };

    $.baseURL = function(){
        var baseurl = $('meta[name="baseurl"]').attr('content');
        return baseurl;
    };

    $.loadDataToForm = function(selectorAnchor, selectorForm, {onResponse}){
        $(document).on('click', selectorAnchor, function(){
            var href = $(this).attr('href');
            $.get(href).done(function(r){
                try{r = JSON.parse(r)}catch (e) {}



                if(typeof onResponse === 'function'){
                    onResponse(r);
                }
            });
        });
    };

    $.clearForm = function(selectorForm){
        $(selectorForm).find('*').filter(':input').each(function(i,o){
//console.log(o.name);
            var isdisabled = ($(o).is(':read-only') && $(o).is(':visible')  )  || $(o).is(':disabled');
            var locked = $(o).data('locked') === 'true';
            if(o.type === 'radio' || o.type === 'checkbox' || isdisabled || locked ){}
            else if(o.name === '_token'){
                $(o).val( $('meta[name="csrf-token"]').attr('content') );
            }else{
                $(o).val('');
            }
            $(o).trigger('change');
            $(o).trigger('focusout');

        });
    };

    $.linkClearForm = function(selectorAnchor, selectorForm, {onResponse}){
        $(selectorAnchor).on('click',function(e){
           $.clearForm(selectorForm);

            if(typeof onResponse === 'function'){
                onResponse();
            }
        });
    };



    $(document).on('click', '.link-tambah', function(){
        var modaltarget = $(this).data('target');
        var form = $(modaltarget).find('form');
        $(form).find('*').filter(':input').each(function(i,o){
            var locked = $(o).data('locked') === 'true';
            var isdisabled = ($(o).is(':read-only') && $(o).is(':visible')  )  || $(o).is(':disabled') || $(o).attr('name') === '_key';
            if(o.type === 'radio' || o.type === 'checkbox' || isdisabled || locked ){}else{
                $(o).val('');
            }
            $(o).trigger('change');
        });
    });


})(jQuery);
