<div class="row">
    <div class="col-sm-5">
        <div class="form-group col-sm-12">
            <label class="col-sm-4 control-label no-padding-right" for="date_ptp"><small>FILTER BY TEAM</small></label>
            <div class="col-sm-8">
                <?php 
					$atribute = 'id="team_id" class="col-sm-5"';
					echo form_dropdown('team_id', $team_data, '',$atribute);
				  ?>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <div class="col-sm-2">
                <button type="button" id="_search" onClick="get_data_monitoring()"
                    class="btn btn-success">SEARCH</button>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <table class="table table-hover table-bordered" border='0'>
            <thead>

                <tr align="center">
                    <!-- <th scope="col" rowspan="2"><center>TeamId			</center></th> -->
                    <th scope="col" rowspan="2">
                        <center>TEAM_NAME </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>TOTAL_AGENT  </center>
                    </th>
                    <th scope="col" colspan="2">
                        <center>CALL_ACTIVITY  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>DATA_CALLED  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>CONTACT  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>NOT_CONTACT  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>APPOINTMENT  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>PROSPECT  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>NO_PROSPECT  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>SPECIAL_CASE  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>OTHER_STATUS  </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% CONTACT_FROM_DATA_CALLED </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% NOT_CONTACT_FROM_DATA_CALLED </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% PROSPECT_FROM_DATA_CONTACT </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% PROSPECT_FROM_DATA_CALLED </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% NO_STATUS_FROM_DATA_CALLED </center>
                    </th>
                </tr>
                <tr>
                    <th scope="col">
                        <center>AVG_TALKTIME  </center>
                    </th>
                    <th scope="col">
                        <center>AVG_ACW  </center>
                    </th>
                </tr>
            </thead>
            <tbody id="content_team_monitoring">

            </tbody>
        </table>
    </div>
</div>

<script>
    function get_data_monitoring() {
        $('#content_team_monitoring').html('');
        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "/dashboard/get_team_monitoring_as_of_today",
            data: { team_id: $('#team_id').val() },
            type: 'post',
            dataType: 'json',
            success: function (msg) {
                console.log(msg);
                var content = '';
                if (msg.length == 0) {
                    content = "<td colspan='18'><center><i>[ empty ]</i></center></td>";
                }

                $.each(msg, function (i, val) {
                    // console.log(val['team_name']);
                    content += "<tr style='text-align: center;'>";
                    // content += "<td>" + val['team_id'] + "</td>";
                    content += "<td>" + val['team_name'] + "</td>";
                    content += "<td>" + val['total_agent'] + "</td>";
                    content += "<td>" + val['average_talktime'] + "</td>";
                    content += "<td>" + val['average_acw'] + "</td>";
                    content += "<td>" + val['total_called'] + "</td>";
                    content += "<td>" + val['contact'] + "</td>";
                    content += "<td>" + val['not_contact'] + "</td>";
                    content += "<td>" + val['appointment'] + "</td>";
                    content += "<td>" + val['ptp'] + "</td>";
                    content += "<td>" + val['no_ptp'] + "</td>";
                    content += "<td>" + val['special_case'] + "</td>";
                    content += "<td>" + val['no_status'] + "</td>";
                    content += "<td>" + val['contact_from_data_called'] + " %</td>";
                    content += "<td>" + val['not_contact_from_data_called'] + " %</td>";
                    content += "<td>" + val['ptp_from_contact'] + " %</td>";
                    content += "<td>" + val['ptp_from_data_called'] + " %</td>";
                    content += "<td>" + val['no_status_from_contact'] + " %</td>";
                    content += "</tr>";
                });
                $('#content_team_monitoring').html(content);


            }
        });
    }
    // alert('test');
    get_data_monitoring();
    var interval = 60 * 5; //detik
    setInterval(function () {
        get_data_monitoring();
        console.log('get monitoring..');
    }, interval * 1000);


    function download_report() {
        location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "/dashboard/download_team_of_today?dteReport=" + yesterday;
    }
</script>
<!-- <script type="text/javascript" src="<?=base_url();?>modules/dashboard/js/team_monitoring_as_of_today.js"></script> -->