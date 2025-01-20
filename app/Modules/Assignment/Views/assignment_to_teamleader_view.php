<div class="row row-cols-12 row-cols-md-12 g-12" id="list_approval">
	
</div>

<div class="card">

	<div class="card-header">
		User Assignment To Team Leader
	</div>

	<div class="card-body">
		<div class="col-xs-12" id="parent">
			<div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
    jQuery(function ($) {
	
	
        function getData() {
            $.ajax({
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/view_class_work_assignment/",
                type: "get",
                success: function (msg) {
                    // console.log(msg);
                    var header = Array();
                    var test = Array();
                    var temp = Array();
                    console.log(msg.data.header[0])
                    console.log(msg.data.data)
                    $.each(msg.data.data, function (i, val) {
                        temp = Object.entries(msg.data.data[i])
                    });
                    
                    $.each(temp, function (i, val) {
                        
                        if (val[0] == 'user_id' || val[0] == 'first_name') {
                            const colDef = msg.data.header[i];

                            //next need to move this def to controller
                            colDef.comparator = (valueA, valueB) => {
                                return valueA.toLowerCase().localeCompare(valueB.toLowerCase());
                            }
                            colDef.filter = 'agTextColumnFilter',
                            header.push(msg.data.header[i]);
                        } else {
                            test = {
                                'field': val[0],
                                'filter': false,
                                'cellRenderer': ragRenderer
                            }

                            header.push(test);
                        }
                    });

                    gridOptions.api.setColumnDefs(header);
                    gridOptions.api.setRowData(msg.data.data);
                },
                dataType: 'json',
            });
        }
        getData();

        function ragRenderer(params) {
            return '<span class="rag-element">' + params.value + '</span>';
        }

        const gridOptions = {
            
            // rowData: getData(),
            columnDefs: [
                { field: 'user_id', hidden: true },
                {
                    field: 'User_id',
                    filter: 'agTextColumnFilter',
                    comparator: (valueA, valueB) => {
                        return valueA.toLowerCase().localeCompare(valueB.toLowerCase());
                    }
                },
                {
                    field: 'First_name',
                    filter: 'agTextColumnFilter',
                    comparator: (valueA, valueB) => {
                        return valueA.toLowerCase().localeCompare(valueB.toLowerCase());
                    }
                },
                { field: '' },
                { field: '' },
                { field: '' }
            ],
        
            // default col def properties get applied to all columns
            // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
            defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
        
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
                console.log('cell was dbclicked', params.data.user_id)
                selr = params.data.user_id;
                selected_data = params.data;
                var buttons = {
                    "success":
                    {
                        "label": "<i class='icon-ok'></i> Save",
                        "className": "btn-sm btn-success",
                        "callback": function () {
                            var options = {
                                url: GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/update_agent_assignment",
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
            
            showCommonDialog(500, 300, 'ASSIGN USER TO TEAM LEADER', GLOBAL_MAIN_VARS["SITE_URL"] + 'assignment/assignment_to_teamleader_action/?id=' + selr, buttons);
            }
        };

        const eGridDiv = document.getElementById("myGrid");

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
    })
    // console.log();

</script>
<!-- <script src="<?=base_url();?>modules/assignment/js/assignment_to_teamleader.js"></script> -->