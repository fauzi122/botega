var wire;
var wireGaleries;
var multipleSelect;

document.addEventListener('DOMContentLoaded', function () {
    setSelectedMenu('event');
    buildTable();
});

document.addEventListener('livewire:initialized', function () {
    wire = Livewire.getByName('admin.event.form')[0];
    wireGaleries = Livewire.getByName('admin.event.detailgaleries')[0];

    wire.on('refresh', function () {
        $('#jd-table').DataTable().ajax.reload();
    });

    wireGaleries.on('refresh', function () {
        $('input#path_file').val(null);
    });

    initializeChoices();

    // Listen for changes to member_id from Livewire
    wire.on('updated', (name, value) => {
        if (name === 'member_id') {
            updateChoices(value);
        }
    });
});

function initializeChoices() {
    multipleSelect = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
    });

    // Add event listener for Choices.js change event
    multipleSelect.passedElement.element.addEventListener('change', function (event) {
        let selectedValues = multipleSelect.getValue(true);
        wire.set('member_id', selectedValues);
    });
}

function updateChoices(values) {
    if (multipleSelect) {
        multipleSelect.removeActiveItems();
        if (Array.isArray(values)) {
            multipleSelect.setChoiceByValue(values.map(String));
        } else if (typeof values === 'string') {
            try {
                let parsedValues = JSON.parse(values);
                if (Array.isArray(parsedValues)) {
                    multipleSelect.setChoiceByValue(parsedValues.map(String));
                }
            } catch (e) {
                console.error('Error parsing member_id:', e);
            }
        }
    }
}

function buildTable() {
    $('#jd-table').DataTable({
        dom: 'Bfrtip', buttons: [

            {
                text: '<i class="mdi mdi-plus-circle"></i> Tambah', action: (e, dt, node, c) => {
                    $('#modalform').modal('show');
                    wire.newForm().then(() => {
                        updateChoices([]);
                    });
                }, className: 'btn-success'
            }, 'csv', 'copy', 'excel', 'pdf', 'print', {
                text: '<i class="mdi mdi-trash-can-outline"></i> Hapus', action: (e, dt, node, c) => {
                    let url = $('#jd-table').data('urlaction');
                    showConfirmHapus('jd-table', url, () => {
                        $('table#jd-table').DataTable().ajax.reload();
                    });
                }, className: 'btn-danger'
            },

        ], initComplete: function (settings, json) {
            $(".dt-button").addClass("btn btn-sm btn-primary");
            $(".dt-button").removeClass("dt-button");
        }, processing: true, serverSide: true, ajax: {
            url: $('table#jd-table').data('datasource'), method: 'GET'
        }, order: [[1, 'asc']], columns: [{
            data: 'id',
            sortable: false,
            width: '20px',
            target: 0,
            searchable: false,
            render: function (data, type, row, meta) {
                return App.tableCheckID(data) + (meta.row + 1 + meta.settings._iDisplayStart);
            }
        }, {data: 'judul'}, {
            data: 'start', render: (data, type, row, meta) => {
                return `${data}<br/>s/d<br/> ${row['end'] ?? '-'}`;
            }
        }, {data: 'member_names'},

            {
                data: 'publish', render: (data, type, row, meta) => {
                    let checked = data === 1 ? 'checked' : '';

                    return ` <input onchange="toggleaktif(${row['id']})" ${checked} type="checkbox" id="switch_${row['id']}" switch="none">
                            <label for="switch_${row['id']}" data-on-label="Ya" data-off-label="Tidak"></label>`;
                }
            },{
                data: 'id', render: (data, type, row, meta) => {
                    return `
            <button class='btn btn-sm btn-rounded btn-info' onclick="editdata('${row['id']}')">
                <i class="mdi mdi-pencil"></i> Edit
            </button>
            ${row['total_members'] > 0 ? `
            <a href="/admin/event/konfirmasi/${row['id']}" class="btn btn-sm btn-rounded btn-primary">
               <i class="mdi mdi-account-multiple-check-outline"></i> Konfirmasi (${row['total_members']})
            </a>` : `
            <button class="btn btn-sm btn-rounded btn-secondary" disabled>
               <i class="mdi mdi-account-multiple-check-outline"></i> Konfirmasi (0)
            </button>`}
        `;
                }
            }



        ]
    });
}

function toggleaktif(id) {
    wire.toggleAktif(id);
}

function save() {
    wire.set('publish', $('input[name=publish]').is(':checked'), false);
    wire.save().then(() => {
        updateChoices([]);
    });
}

function editdata(id) {
    $('#modalform').modal('show');
    wireGaleries.newData(id);
    wire.edit(id).then(() => {
        let publish = wire.get('publish');
        $('input#publish').prop('checked', publish === 1);
        let memberIds = wire.get('member_id');
        updateChoices(memberIds);
    });
}

async function saveGaleri() {
    let img = await getBase64File('input#path_file');

    wireGaleries.set('path_file', img, false);
    wireGaleries.save().then(() => {

    });

}

function hapusGaleri(id) {
    Swal.fire({
        title: 'Hapus Galeri',
        text: 'Gambar yang dihapus tidak dapat dikembalikan, apakah tetap dilanjutkan?',
        type: 'warning',
        showCancelButton: true
    }).then((e) => {
        if (e.value) {
            wireGaleries.delete(id);
        }
    });
}
