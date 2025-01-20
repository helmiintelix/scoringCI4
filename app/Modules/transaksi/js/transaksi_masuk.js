function currencyFormatter(currency, sign) {
    var sansDec = currency.toFixed(0);
    var formatted = sansDec.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return sign + `${formatted}`;
}

function dateFormatter(date, time) {
    let datex = date.split('-');
    let bulan = datex[1];
    if (datex[1] == 1) bulan = 'Jan';
    if (datex[1] == 2) bulan = 'Feb';
    if (datex[1] == 3) bulan = 'Mar';
    if (datex[1] == 4) bulan = 'Apr';
    if (datex[1] == 5) bulan = 'Mei';
    if (datex[1] == 6) bulan = 'Jun';
    if (datex[1] == 7) bulan = 'Jul';
    if (datex[1] == 8) bulan = 'Agust';
    if (datex[1] == 9) bulan = 'Sept';
    if (datex[1] == 10) bulan = 'Okt';
    if (datex[1] == 11) bulan = 'nov';
    if (datex[1] == 12) bulan = 'Des';
    return datex['2'] + ' ' + bulan + ' ' + datex['0'] + ' ' + time;
}
// Grid Options are properties passed to the grid
var gridOptions = {

    columnDefs: [

        { field: "id", hide: true },
        { field: "label", headerName: "Jenis" },
        { field: "keterangan" },
        {
            field: "nominal", valueFormatter: params => {

                return currencyFormatter(parseFloat(params.data.nominal), "Rp ");
            },
            // filter: "agNumberColumnFilter",
            filterParams: {
                suppressAndOrCondition: true,
                filterOptions: ["greaterThan"]
            }
        },
        {
            field: "tipe_transaksi", headerName: "Jenis", hide: true
        },
        {
            field: "tanggal_transaksi", headerName: "tanggal transaksi", valueFormatter: params => {

                return dateFormatter(params.data.tanggal_transaksi, '');
            },
        },
        { field: "full_name", headerName: "Dibuat oleh" },
        {
            field: "created_time", headerName: "waktu input", valueFormatter: params => {

                return dateFormatter(params.data.created_time.split(' ')[0], params.data.created_time.split(' ')[1]);
            },
        }
    ],

    // default col def properties get applied to all columns
    // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
    defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true, wrapText: true, autoHeight: true },

    rowSelection: 'multiple', // allow rows to be selected
    animateRows: true, // have rows animate to new positions when sorted
    paginationAutoPageSize: true,
    pagination: true,

    // example event handler
    onCellClicked: params => {
        console.log('cell was clicked', params)
        selr = params.data.id;
        selected_data = params.data;
    }
};

function getData() {
    $.ajax({
        url: GLOBAL_MAIN_VARS["SITE_URL"] + "transaksi/get_transaksi?tipe=" + tipe,
        type: "get",
        success: function (msg) {
            console.log('msg', msg)
            // gridOptions.api.setColumnDefs(msg.data.header);
            gridOptions.api.setRowData(msg.data.data);
            $("#total_nominal").html(msg.total_nominal);
        },
        dataType: 'json',
    });
}


$("#btn-add-transaksi").click(function () {
    var buttons = {
        "success":
        {

            "label": "<i class='icon-ok'></i> Save",
            "className": "btn-sm btn-success btn-save-transaksi",
            "callback": function () {
                var options = {
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "transaksi/save_transaksi?tipe=" + tipe,
                    type: "post",
                    // beforeSubmit: jqformValidate,
                    success: function (msg) {
                        if (msg.success) {
                            showInfo(msg.message);
                            getData();
                        } else {
                            showWarning(msg.message);
                        }

                    },
                    dataType: 'json',
                };

                $('form').ajaxSubmit(options);
            }
        },
        "button":
        {
            "label": "Close",
            "className": "btn-sm"
        }
    }

    showCommonDialog(600, 400, 'TAMBAH ' + tipe, GLOBAL_MAIN_VARS["SITE_URL"] + 'transaksi/add_transaksi?tipe=' + tipe, buttons);
});

function download_laporan() {
    let bulan = $("#bulan").val();
    let tahun = $("#tahun").val();
    location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "transaksi/create_laporan?tipe=" + tipe + "&bulan=" + bulan + "&tahun=" + tahun;
}
function download_all_laporan() {
    let bulan = $("#bulan").val();
    let tahun = $("#tahun").val();
    location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "transaksi/create_all_laporan?tipe=" + tipe + "&bulan=" + bulan + "&tahun=" + tahun;
}

$(document).ready(function () {
    // get div to host the grid
    const eGridDiv = document.getElementById("myGrid");

    // new grid instance, passing in the hosting DIV and Grid Options
    new agGrid.Grid(eGridDiv, gridOptions);
    getData(); // untuk menampilkan data di table nya
})