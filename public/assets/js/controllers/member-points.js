document.addEventListener("DOMContentLoaded", function () {
    let table = $("#member-points-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: memberPointsDataSourceUrl,
        columns: [
            { data: "id", name: "id" },
            { data: "first_name", name: "first_name" },
            { data: "email", name: "email" },
            { data: "points", name: "points" },
            {
                data: "id",
                name: "action",
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `<button class="btn btn-sm btn-info" onclick="showMemberDetail(${data})">Detail</button>`;
                },
            },
        ],
    });

    // Function to fetch and show member detail
    window.showMemberDetail = function (id) {
        fetch(`/api/member-points/${id}`)
            .then((response) => response.json())
            .then((data) => {
                displayMemberDetail(data);
                $("#modal-detail").modal("show");
            })
            .catch((error) => console.error("Error fetching details:", error));
    };

    // Function to populate the modal with fetched data
    function displayMemberDetail(pointsData) {
        let tableBody = document.querySelector("#modal-detail tbody");
        tableBody.innerHTML = ""; // Clear previous content

        pointsData.forEach((point, index) => {
            let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${point.received_at}</td>
                    <td>${
                        point.transaction ? point.transaction.invoice_no : "-"
                    }</td>
                    <td>${point.points}</td>
                    <td>${point.notes}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }
});
