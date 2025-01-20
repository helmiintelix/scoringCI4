<div class="card">
    <div class="card-header">
        Test Data SMS
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridTds" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var classification = '<?= $classification ?>';
    jQuery(function ($) {
        var selr;
        var selected_data;
        var TOKEN_VALID = false;

        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] +
                "voiceblast/campaign/get_test_data" +
                classification,
            type: "get",
            success: function (msg) {
                console.log("test branch");
                console.log(msg);
                gridOptions.api.setGridOption("columnDefs", msg.data.header);
                gridOptions.api.setGridOption("rowData", msg.data.data);
            },
            dataType: "json",
        });

        var gridOptions = {
            columnDefs: [{
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
                {
                    field: ""
                },
            ],

            // default col def properties get applied to all columns
            // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
            defaultColDef: {
                sortable: true,
                filter: "agSetColumnFilter",
                floatingFilter: true,
                resizable: true,
            },

            rowSelection: "multiple", // allow rows to be selected
            animateRows: true, // have rows animate to new positions when sorted
            paginationAutoPageSize: true,
            pagination: true,

            // example event handler
            onCellClicked: (params) => {
                console.log("cell was clicked", params);
                selr = params.data.id;
                selected_data = params.data;
            },
        };
        var eGridDiv = document.getElementById("myGridTds");
        new agGrid.Grid(eGridDiv, gridOptions);
    })
</script>