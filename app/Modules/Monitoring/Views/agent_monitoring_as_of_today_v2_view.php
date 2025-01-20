<div class="row">
    <div class="col-sm-12">
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
            <label class="col-sm-4 control-label no-padding-right" for="date_ptp"><small>FILTER BY AGENT</small></label>
            <div class="col-sm-8">
                <?php 
					$atribute = 'id="agent_id" class="col-sm-5"';
					echo form_dropdown('agent_id', array(""=>"[SELECT DATA]")+$agent_data, '',$atribute);
				  ?>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <div class="col-sm-2">
                <button type="button" id="_search" onClick="get_data_monitoring_agent()"
                    class="btn btn-success">SEARCH</button>
            </div>
        </div>
    </div>

    <div class="col-sm-12" style="overflow-x: scroll;margin-bottom: 60px;">
        <!--<span>update</span>-->
        <table class="table table-hover table-bordered" border='0'>
            <thead>

                <tr align="center">
                    <th scope="col" rowspan="2">
                        <center>AGENT_ID </center>
                    </th>
                    <th scope="col" rowspan="2" >
                        <center>AGENT_NAME </center>
                    </th>
                    <th scope="col" rowspan="2" style='display:none'>
                        <center>Bucket </center>
                    </th>
                    <th scope="col" colspan="8">
                        <center>WORKING_TIME </center>
                    </th>
                    <th scope="col" colspan="2">
                        <center>CALL_ACTIVITY </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>DATA_CALLED </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>CONTACT </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>NOT_CONTACT </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>APPOINTMENT </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>OTHER_STATUS </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>PROSPECT </center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>NO_PROSPECT </center>
                    </th>
                    <!-- <th scope="col" rowspan="2"><center>Special Case			</center></th> -->
                    <th scope="col" rowspan="2">
                        <center>% CONTACT_FROM_DATA_CALLED</center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% NOT_CONTACT_FROM_DATA_CALLED</center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% PROSPECT_FROM_DATA_CONTACT</center>
                    </th>
                    <th scope="col" rowspan="2">
                        <center>% PROSPECT_FROM_DATA_CALLED</center>
                    </th>
                    <!-- <th scope="col" rowspan="2"><center>Outgoing Call Score	</center></th>	 
			  <th scope="col" rowspan="2"><center>PTP Score	</center></th>	 
			  <th scope="col" rowspan="2"><center>Keep PTP Score	</center></th>	 
			  <th scope="col" rowspan="2"><center>Balance Saving Score	</center></th>	 
			  <th scope="col" rowspan="2"><center>Amount Collect Score	</center></th>	 
			  <th scope="col" rowspan="2"><center>Total Score	</center></th>	  -->
                </tr>
                <tr>
                    <th scope="col">
                        <center>LAST_LOGOUT </center>
                    </th>
                    <th scope="col">
                        <center>FIRST_LOGIN </center>
                    </th>
                    <th scope="col">
                        <center>BREAK </center>
                    </th>
                    <th scope="col">
                        <center>PRAY </center>
                    </th>
                    <th scope="col">
                        <center>COACHING </center>
                    </th>
                    <th scope="col">
                        <center>TRAINING </center>
                    </th>
                    <th scope="col">
                        <center>MEETING </center>
                    </th>
                    <th scope="col">
                        <center>NET_WORKING_TIME </center>
                    </th>
                    <th scope="col">
                        <center>AVG_TALKTIME </center>
                    </th>
                    <th scope="col">
                        <center>AVG_ACW </center>
                    </th>
                </tr>
            </thead>
            <tbody id="content_agent_monitoring">

            </tbody>
            <!-- <tbody id="content_agent_monitoring_2" style="display:none">
			
		  </tbody>-->
        </table>
    </div>
</div>

<script>
    function get_data_monitoring_agent() {
        $('#content_agent_monitoring').html('');
        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "/dashboard/get_agent_monitoring_as_of_today",
            data: { agent_id: $('#agent_id').val() ,team_id: $('#team_id').val()},
            type: 'post',
            async: false,
            dataType: 'json',
            success: function (msg) {
                // console.log(msg);
                var content = '';
                if (msg.length == 0) {
                    content = "<td colspan='24'><center><i>[ empty ]</i></center></td>";
                }

                $.each(msg, function (i, val) {
                    // console.log(val['team_name']);
                    content += "<tr style='text-align: center;'>";
                    content += "<td>" + val['agent_id'] + "</td>";
                    content += "<td>" + val['name'] + "</td>";
                    content += "<td style='display:none'>" + val['bucket'] + "</td>";
                    content += "<td>" + val['last_logout'] + "</td>";
                    content += "<td>" + val['first_login'] + "</td>";
                    content += "<td>" + val['break'] + "</td>";
                    content += "<td>" + val['pray'] + "</td>";
                    content += "<td>" + val['coaching'] + "</td>";
                    content += "<td>" + val['training'] + "</td>";
                    content += "<td>" + val['meeting'] + "</td>";
                    content += "<td>" + val['not_work_time'] + "</td>";
                    content += "<td>" + val['average_talktime'] + "</td>";
                    content += "<td>" + val['average_acw'] + "</td>";
                    content += "<td>" + val['total_call'] + "</td>";
                    content += "<td>" + val['contact'] + "</td>";
                    content += "<td>" + val['not_contact'] + "</td>";
                    content += "<td>" + val['appointment'] + "</td>";
                    content += "<td>" + val['no_status'] + "</td>";
                    content += "<td>" + val['ptp'] + "</td>";
                    content += "<td>" + val['no_ptp'] + "</td>";
                    // content +="<td>"+val['special_case']+"</td>";
                    content += "<td>" + val['contact_from_data_called'] + " %</td>";
                    content += "<td>" + val['not_contact_from_data_called'] + " %</td>";
                    content += "<td>" + val['ptp_from_contact'] + " %</td>";
                    content += "<td>" + val['ptp_from_data_called'] + " %</td>";
                    // content +="<td>"+val['score_called2']+" %</td>";
                    // content +="<td>"+val['score_ptp2']+" %</td>";
                    // content +="<td>"+val['score_keep_ptp2']+" %</td>";
                    // content +="<td>"+val['score_balance_save2']+" %</td>";
                    // content +="<td>"+val['score_amount2']+" %</td>";
                    // content +="<td>"+val['total_score']+" %</td>";
                    content += "<tr>";
                });

                // if(nilai%2==0)
                // {
                // $('#content_agent_monitoring_2').html(content);
                // // document.write(nilai+" adalah bilangan genap <br>" );
                // } else
                // {
                // // document.write(nilai+" adalah bilangan ganjil <br>" );
                $('#content_agent_monitoring').html(content);
                // }

            }
        });
    }
    // alert('test');

    $(document).ready(function () {
        get_data_monitoring_agent();
        var interval = 60 * 3; //detik
        var nilai = 1;
        setInterval(function () {
            get_data_monitoring_agent();
            nilai++;
            // console.log('get monitoring..');
        }, interval * 1000);
    });

</script>

<!-- <script type="text/javascript" src="<?=base_url();?>modules/dashboard/js/agent_monitoring_as_of_today_v2.js"></script> -->