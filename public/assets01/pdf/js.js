/*
*  costanti per i placaholder
*/
var maxPDFx = 595;
var maxPDFy = 842;
var offsetY = 7;

// The workerSrc property shall be specified.
//
var canvas;

function renderPlaceholderPanel(){
    console.log($('#paramplace').val());
    var parametri = placholderJson;
    $('#parametriContainer').empty();
    renderizzaPlaceholder(0, parametri);
}

function dopdf() {
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.worker.min.js';
    canvas = document.getElementById('the-canvas');

    var file = $('#pdf-file-input').prop('files')[0];

    if (file) {
        // Membaca file menggunakan FileReader
        var reader = new FileReader();
        reader.onloadend = function() {
            // Mengonversi hasil bacaan menjadi base64
            var base64Data = btoa(reader.result);

            // Membuka file PDF dalam jendela baru
            $("input#pdfBase64").val(base64Data);
        };
        reader.readAsBinaryString(file);
    } else {
        alert('Pilih file PDF terlebih dahulu.');
        return;
    }

    var pdfData = atob($('#pdfBase64').val());

    'use strict';

    $(document).bind('pagerendered', function (e) {
        $('#pdfManager').show();
    });


    //
    // Asynchronous download PDF
    //
    var loadingTask = pdfjsLib.getDocument({data: pdfData});
    loadingTask.promise.then(function (pdf) {
        //
        // Fetch the first page
        //
        pdf.getPage(1).then(function (page) {
            var scale = 2.0;
            var viewport = page.getViewport(scale);
            //
            // Prepare canvas using PDF page dimensions
            //

            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            page.render({
                canvasContext: context,
                viewport: viewport
            }).promise.then(function (tContext) {
                $(document).trigger("pagerendered");
            }, function () {
                console.log("ERROR");
            });

        });
    });


    /* The dragging code for '.draggable' from the demo above
     * applies to this demo as well so it doesn't have to be repeated. */

    // enable draggables to be dropped into this
    interact('.dropzone').dropzone({
        // only accept elements matching this CSS selector
        accept: '.drag-drop',
        // Require a 100% element overlap for a drop to be possible
        overlap: 1,

        // listen for drop related events:

        ondropactivate: function (event) {
            // add active dropzone feedback
            event.target.classList.add('drop-active');
        },
        ondragenter: function (event) {
            var draggableElement = event.relatedTarget,
                dropzoneElement = event.target;

            // feedback the possibility of a drop
            dropzoneElement.classList.add('drop-target');
            draggableElement.classList.add('can-drop');
            draggableElement.classList.remove('dropped-out');
            //draggableElement.textContent = 'Dragged in';
        },
        ondragleave: function (event) {
            // remove the drop feedback style
            event.target.classList.remove('drop-target');
            event.relatedTarget.classList.remove('can-drop');
            event.relatedTarget.classList.add('dropped-out');
            //event.relatedTarget.textContent = 'Dragged out';
        },
        ondrop: function (event) {
            //event.relatedTarget.textContent = 'Dropped';
        },
        ondropdeactivate: function (event) {
            // remove active dropzone feedback
            event.target.classList.remove('drop-active');
            event.target.classList.remove('drop-target');
        }
    });

    interact('.drag-drop')
        .draggable({
            inertia: true,
            restrict: {
                restriction: "#selectorContainer",
                endOnly: true,
                elementRect: {top: 0, left: 0, bottom: 1, right: 1}
            },
            autoScroll: true,
            // dragMoveListener from the dragging demo above
            onmove: dragMoveListener,
        });

    // this is used later in the resizing demo
    window.dragMoveListener = dragMoveListener;


}


function renderizzaPlaceholder(currentPage, parametri) {
    var maxHTMLx = $('#the-canvas').width();
    var maxHTMLy = $('#the-canvas').height();

    var paramContainerWidth = $('#parametriContainer').width();
    var yCounterOfGenerated = 0;
    var numOfMaxItem = 25;
    var notValidHeight = 30;
    var y = 0;
    var x = 6;
    var page = 0;


    var totalPages = Math.ceil(parametri.length / numOfMaxItem);

    for (i = 0; i < parametri.length; i++) {
        var param = parametri[i];
        var page = Math.floor(i / numOfMaxItem);
        var display = currentPage == page ? "block" : "none";

        if (i > 0 && i % numOfMaxItem == 0) {
            yCounterOfGenerated = 0;
        }

        var classStyle = "";
        var valore = param.valore;
        /*il placeholder non è valido: lo incolonna a sinistra*/

        if (i > 0 && i % numOfMaxItem == 0) {
            yCounterOfGenerated = 0;
        }

        var classStyle = "";
        var valore = param.valore;
        /*il placeholder non è valido: lo incolonna a sinistra*/
        y = yCounterOfGenerated;
        yCounterOfGenerated += notValidHeight;
        classStyle = "drag-drop dropped-out";


        $("#parametriContainer").append('<div class="' + classStyle + '" data-id="-1" data-page="' + page + '" data-toggle="' + valore + '" data-valore="' + valore + '" data-x="' + x + '" data-y="' + y + '" style="transform: translate(' + x + 'px, ' + y + 'px); display:' + display + '">  <span class="circle"></span><span class="descrizione">' + param.descrizione + ' </span></div>');
    }

    y = notValidHeight * (numOfMaxItem + 1);
    var prevStyle = "";
    var nextStyle = "";
    var prevDisabled = false;
    var nextDisabled = false;
    if (currentPage == 0) {
        prevStyle = "disabled";
        prevDisabled = true;
    }

    if (currentPage >= totalPages - 1 || totalPages == 1) {
        nextDisabled = true;
        nextStyle = "disabled";
    }

    //Aggiunge la paginazione
    $("#parametriContainer").append('<ul id="pager" class="pager" style="transform: translate(' + x + 'px, ' + y + 'px); width:200px;"><li onclick="changePage(' + prevDisabled + ',' + currentPage + ',-1)" class="page-item ' + prevStyle + '"><span>«</span></li><li onclick="changePage(' + nextDisabled + ',' + currentPage + ',1)" class="page-item ' + nextStyle + '" style="margin-left:10px;"><span>&raquo;</span></li></ul>');

}

function renderizzaInPagina(parametri) {
    var maxHTMLx = $('#the-canvas').width();
    var maxHTMLy = $('#the-canvas').height();

    var paramContainerWidth = $('#parametriContainer').width();
    var yCounterOfGenerated = 0;
    var numOfMaxItem = 26;
    var notValidHeight = 30;
    var y = 0;
    var x = 6;
    for (i = 0; i < parametri.length; i++) {
        var param = parametri[i];

        var classStyle = "drag-drop can-drop";
        var valore = param.valore;
        /*il placeholder non è valido: lo incolonna a sinistra*/

        var pdfY = maxPDFy - param.posizioneY - offsetY;
        y = (pdfY * maxHTMLy) / maxPDFy;
        x = ((param.posizioneX * maxHTMLx) / maxPDFx) + paramContainerWidth;

        $("#parametriContainer").append('<div class="' + classStyle + '" data-id="' + param.idParametroModulo + '" data-toggle="' + valore + '" data-valore="' + valore + '" data-x="' + x + '" data-y="' + y + '" style="transform: translate(' + x + 'px, ' + y + 'px);">  <span class="circle"></span><span class="descrizione">' + param.descrizione + ' </span></div>');
    }
}


function changePage(disabled, currentPage, delta) {
    if (disabled) {
        return;
    }

    /*recupera solo i parametri non posizionati in pagina*/
    var parametri = [];
    $(".drag-drop.dropped-out").each(function () {
        var valore = $(this).data("valore");
        var descrizione = $(this).find(".descrizione").text();
        parametri.push({valore: valore, descrizione: descrizione, posizioneX: -1000, posizioneY: -1000});
        $(this).remove();
    });

    //svuota il contentitore
    $('#pager').remove();
    currentPage += delta;
    renderizzaPlaceholder(currentPage, parametri);
    //aggiorna lo stato dei pulsanti
    //aggiorna gli elementi visualizzati
}

function dragMoveListener(event) {
    var target = event.target,
        // keep the dragged position in the data-x/data-y attributes
        x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
        y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

    // translate the element
    target.style.webkitTransform =
        target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';

    // update the posiion attributes
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
    $(target).data('x',x);
    $(target).data('y',y);

}

function savePDF(){
    window.jsPDF = window.jspdf.jsPDF;

    // Membuat objek pdf
    var pdf = new jsPDF({compress:true});
// Mendapatkan lebar dan tinggi halaman pdf
    var pageWidth = pdf.internal.pageSize.getWidth();
    var pageHeight = pdf.internal.pageSize.getHeight();
    console.log(pageHeight, pageWidth);

        var dataUrl = canvas.toDataURL('image/png');
        img = new Image();
        img.src = dataUrl;
        img.onload = function(){
            var aspectRatio = img.width / img.height;

            // Menentukan ukuran dan posisi gambar agar memenuhi halaman pdf
            var imgWidth, imgHeight, xPos, yPos;

            if (aspectRatio > 1) { // Gambar landscape
                imgWidth = pageWidth;
                imgHeight = pageWidth / aspectRatio;

                xPos = 0;
                yPos = (pageHeight - imgHeight) / 2;
            } else { // Gambar portrait atau persegi
                imgWidth = pageHeight * aspectRatio;
                imgHeight = pageHeight;

                xPos = (pageWidth - imgWidth) / 2;
                yPos = 0;
            }

            // Menambahkan gambar ke halaman pdf
            pdf.addImage(img, 'JPEG', xPos, yPos, imgWidth, imgHeight);
            // Menyimpan atau menampilkan pdf
            pdf.save('output.pdf');
        };



}


function showCoordinates() {
    var validi = [];
    var nonValidi = [];

    var maxHTMLx = $('#the-canvas').width();
    var maxHTMLy = $('#the-canvas').height();
    var paramContainerWidth = $('#parametriContainer').width();

    //recupera tutti i placholder validi
    $('.drag-drop.can-drop').each(function (index) {
        var x = parseFloat($(this).data("x"));
        var y = parseFloat($(this).data("y"));
        var valore = $(this).data("valore");
        var descrizione = $(this).find(".descrizione").text();

        var pdfY = y * maxPDFy / maxHTMLy;
        var posizioneY = maxPDFy - offsetY - pdfY;
        var posizioneX = (x * maxPDFx / maxHTMLx) - paramContainerWidth;

        var val = {
            "descrizione": descrizione,
            "posizioneX": posizioneX,
            "posizioneY": posizioneY,
            "valore": valore
        };
        validi.push(val);

    });

    if (validi.length == 0) {
        alert('No placeholder dragged into document');
    } else {
        alert(JSON.stringify(validi));
    }
}


function getBase64FromImageUrl(url) {
    return new Promise((resolve, reject) => {
        // Buat objek XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Atur tipe respons menjadi 'blob'
        xhr.responseType = 'blob';

        xhr.onload = function() {
            var reader = new FileReader();

            // Membaca data blob sebagai URL
            reader.onloadend = function() {
                // Mendapatkan data URL dari FileReader
                var base64data = reader.result;

                // Memanggil resolve dengan data URL yang dihasilkan
                resolve(base64data);
            }

            // Membaca blob sebagai data URL
            reader.readAsDataURL(xhr.response);
        };

        xhr.onerror = function() {
            // Memanggil reject jika terjadi kesalahan
            reject(new Error('Failed to fetch image'));
        };

        // Melakukan permintaan HTTP GET ke URL gambar
        xhr.open('GET', url);
        xhr.send();
    });
}

function renderCanvas(){
    canvas = document.getElementById('the-canvas');
    context = canvas.getContext('2d');
    context.font = '18px Arial';
    context.filllStyle = 'black';
    var maxHTMLx = 1217 * 2;
    var maxHTMLy = 790 * 2;
    console.log(maxHTMLy)
    var paramContainerWidth = $('#parametriContainer').width();

    $('.drag-drop.can-drop').each(function (index) {
        var x = parseFloat($(this).data("x"));
        var y = parseFloat($(this).data("y"));
        var value = $(this).data("valore");

        var posizioneY = ((y-6) / 900 * maxHTMLy) + 20;
        var posizioneX = ((x -paramContainerWidth) / 1200 * maxHTMLx)-30 ;

        console.log(x,y);
        console.log(posizioneX,posizioneY);
        if(value.substring(0,4) == 'img='){
            let imgurl = value.substring(4);
            getBase64FromImageUrl(imgurl)
                .then((base64data) => {
                    var img = new Image();
                    img.src = base64data;
                    img.onload = function(){

                        context.drawImage(img, posizioneX, posizioneY, 210,180);
                    };
                })
                .catch((error) => {
                    // Menangani kesalahan jika terjadi
                    console.error(error);
                });


        }else {
            context.fillText(value, posizioneX, posizioneY);
        }
    });
}

