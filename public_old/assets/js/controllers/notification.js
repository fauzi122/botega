var txtCountNotif;
var txtUnread;
var listNotif;

document.addEventListener('DOMContentLoaded', function(){
    txtCountNotif = $('#txt-countnotif');
    txtUnread = $('#txt-unread');
    listNotif = $('#list-notif');

    txtCountNotif.html('');
    txtUnread.html('');
    listNotif.html('');

    _schedulerNotif();
});

function _schedulerNotif(){
    _loadNotif();
    setTimeout(_schedulerNotif, 10000);
}

function initWS(){
    const ws = new WebSocket('');
}

function _loadNotif(){
    $.get(baseurl()+'/admin/notification').done(function(e){
        let data = e.data;
        txtUnread.html(`Unread (${data.length})`);
        txtCountNotif.html(data.length > 0 ? data.length : '');
        html = '';
        for(var idx in data){
            let item = data[idx];
            let gambar = item.foto_path;
            let payload = $.parseJSON(item.payload);
            let now = new Date();
            let awal = new Date(item.created_at);

            let diff = formatDateDiff(awal, now);

            html += ` <a href="${item.link}" class="text-reset notification-item">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <img src="${gambar}" class="rounded-circle avatar-sm" alt="No Pic">
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.first_name} ${item.last_name}</h6>
                        <div class="font-size-13 text-muted">
                            <p class="mb-1">${payload.description}</p>
                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>${diff}</span></p>
                        </div>
                    </div>
                </div>
            </a>`;
        }
        let oldhtml = listNotif.html();
        // console.log(oldhtml);
        if(oldhtml === html){}else{
            listNotif.html(html);
        }
    });
}
