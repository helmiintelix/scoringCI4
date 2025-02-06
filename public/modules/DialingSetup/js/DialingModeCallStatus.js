
var header = Array();
var test = Array();
var temp = Array();

function getData() {
    $.ajax({
        url: GLOBAL_MAIN_VARS["SITE_URL"] + "dialingSetup/dialingModeCallStatus/getDialingModeCallStatusList",
        type: "get",
        success: function (msg) {
            console.log(msg)
            gridOptions.api.setRowData(msg.data);
        },
        dataType: 'json',
    });
}


function ragRenderer(params) {
    return '<span class="rag-element">' + params.value + '</span>';
}

var gridOptions = {

    // rowData: getData(),
    columnDefs: [
        {
            headerName: 'Classification Name',
            field: 'classification_name',
            width: 200,
        },
        {
            headerName: 'Can Call',
            field: 'can_call',
            width: 150,
            cellRenderer: ragRenderer
        },
        {
            headerName: 'Dialing Mode Id',
            field: 'dialing_mode_id',
            width: 150,
            hide: true
        },
        {
            headerName: 'Auto Dial',
            field: 'auto_dial',
            width: 200,
            cellRenderer: ragRenderer
        },
        {
            headerName: 'Semi Auto',
            field: 'semi_auto',
            width: 200,
            cellRenderer: ragRenderer
        },
        {
            headerName: 'Manual',
            field: 'manual',
            width: 150,
            cellRenderer: ragRenderer
        },
        {
            headerName: 'Formula Factor',
            field: 'formula_factor',
            width: 150,
        },
        {
            headerName: 'Auto Disconnect',
            field: 'call_timeout',
            width: 200,
        },
        {
            headerName: 'Try again after',
            field: 'try_again_after',
            width: 200,
        },
        {
            headerName: 'Dialy Dial Limiter',
            field: 'max_call_attempt',
            width: 150,
        },
        {
            headerName: 'Call priority 1',
            field: 'call_priority_1',
            width: 150,
        },
        {
            headerName: 'Call priority 2',
            field: 'call_priority_2',
            width: 200,
        },
        {
            headerName: 'Call priority 3',
            field: 'call_priority_3',
            width: 150,
        },
        {
            headerName: 'Call priority 4',
            field: 'call_priority_4',
            width: 200,
        },
        {
            headerName: 'Call priority 5',
            field: 'call_priority_5',
            width: 150,
        },
        {
            headerName: 'Call priority 6',
            field: 'call_priority_6',
            width: 200,
        },
        {
            headerName: 'Call priority 7',
            field: 'call_priority_7',
            width: 150,
        },
        {
            headerName: 'Call priority 8',
            field: 'call_priority_8',
            width: 200,
        },
    ],

    // cellRenderer : ragRenderer

    // default col def properties get applied to all columns
    // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
    defaultColDef: { sortable: true, floatingFilter: true, resizable: true },

    rowSelection: 'multiple', // allow rows to be selected
    animateRows: true, // have rows animate to new positions when sorted
    paginationAutoPageSize: true,
    pagination: true,

    // example event handler
    // onCellClicked: params => {
    //     console.log('cell was clicked', params.data.class_mst_id)
    //     selr = params.data.id;
    //     selected_data = params.data;
    // },

    onRowDoubleClicked: params => {
        console.log('cell was dbclicked', params.data.id)
        selr = params.data.id;
        selected_data = params.data;
        var buttons = {
            "success":
            {
                "label": "<i class='icon-ok'></i> Save",
                "className": "btn-sm btn-success",
                "callback": function () {
                    var options = {
                        url: GLOBAL_MAIN_VARS["SITE_URL"] + "dialingSetup/dialingModeCallStatus/update_dialing_mode_call_status",
                        type: "post",
                        // beforeSubmit: jqformValidate,
                        success: showFormResponse,
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

        showCommonDialog(1000, 400, 'Dialing Mode Call Status', GLOBAL_MAIN_VARS["SITE_URL"] + 'dialingSetup/dialingModeCallStatus/updateDialingModeCallStatus?id=' + selr, buttons);
    }
};

var eGridDiv = document.getElementById("myGrid");

new agGrid.Grid(eGridDiv, gridOptions);

var showFormResponse = function (responseText, statusText) {
    if (responseText.success) {
        showInfo(responseText.message);
        getData();
        // getDataApproval();

        if (responseText.notification_id) {
            sendNotification(responseText.notification_id);
        }
    } else {
        showInfo(responseText.message);
        return false;
    }
}

$(document).ready(() => {
    getData();
})
// console.log();