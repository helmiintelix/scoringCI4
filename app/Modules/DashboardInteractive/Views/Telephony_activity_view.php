<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        
        .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }
        .card_footer  {
            width:100%;
            height:80%;
            border-radius: 0px 0px 5px 5px;
            border-top: 1px dashed #413543;
            /* background-color: #e0e0e0; */
            opacity: 0.8;
            /* background-image:  repeating-radial-gradient( circle at 0 0, transparent 0, #363649 4px ), repeating-linear-gradient( #87878755, #878787 ); */
            padding-left : 3%;
        }
        .card_header {
            /* background-color:#f6f6f6; */
            padding: 2%;border-radius:  5px 5px 0px 0px;
        }
        .progress_2{
            border-radius: 40px;
            margin-bottom :10px;
        }
        .progress_3{
            background-color: #689550;
        }
        .total_loan {
            /* color: palevioletred; */
            /* color: #3C486B; */
            font-weight: bold;
            font-size : 50px;
        }
        .total_time{
            /* color: #3C486B; */
            font-weight: bold;
            font-size : 33px;
        }
        .total_ptp{
            /* color: #0096FF; */
            font-weight: bold;
            font-size : 20px;
        }
        .class_label text-secondary-emphasis{
            color: #555454;
            font-size:14px;
            font-family: sans-serif;
        }
        div.online-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #16FF00;
        border-radius: 50%;
        position: relative;
        }
        span.blink {
        display: block;
        width: 10px;
        height: 10px;
        background-color: #16FF00;
        opacity: 0.7;
        border-radius: 50%;
        animation: blink 1s linear infinite;
        }

        div.offline-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            position: relative;
        }

        @keyframes blink {
        100% { transform: scale(2, 2); 
                opacity: 0;
                }
        }

        .wtd_no{
            width: 10%;
        }
        .wtd_photo{
            width: 8%;
            font-size: 20px;
        }
        .wtd_name_id{
            width: 40%;
        }
        .wtd_id{
            font-size: 14px;
            color: darkgrey;
        }
    </style>
</head>
<body>
    <?php
        $color1 = "white";
        $color2 = "#474E68";
        $color3 = "#50577A";
        $color4 = "#6B728E"; 
        
        $hijau_neon = "#16FF00"; 
        $biru_neon = "#0096FF"; 
        $merah = "#e44141"; 
    ?>
<div class="row" >

    <div class="col-sm-12 bg-body-secondary" style="border-radius: 7px;padding:1%">
        <div class="row">

            <div class="col-sm-3" >
                <div class="card" data-toggle="tooltip" title="Total Data dalam antrian call" data-placement="top" >
                    <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?= $hijau_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">TOTAL</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_agent"><?=$total_agent?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-users pull-right" style="font-size:30px;color:<?= $hijau_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Agent</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2" >
                <div class="card" data-toggle="tooltip" title="Total dial dalam hari ini" data-placement="top">
                    <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">CONNECT</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_agent_login">0</i>
                            </td>                   
                            <td>
                                <i class="fa fa-user pull-right" style="font-size:30px;color:<?=$biru_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Agent</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">TALKING</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_talking">0</i>
                            </td>                   
                            <td>
                                <i class="fa fa-user pull-right" style="font-size:30px;color:<?=$biru_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Agent</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">AUX</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis"  id="total_aux">0</i>
                            </td>                   
                            <td>
                                <i class="fa fa-user pull-right" style="font-size:30px;color:<?=$biru_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Agent</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$merah?>;"></i>
                                <b class="class_label text-secondary-emphasis">DISCONNECT</b>
                                <div class="offline-indicator pull-right">
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis"  id="total_agent_logout"><?=$total_agent_logout?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-user pull-right" style="font-size:30px;color:<?=$merah?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Agent</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- end col sm 12 -->
    </div> 

    <div class="col-sm-12 bg-body-secondary" style="margin-top:15px;border-radius: 7px;padding:1%" >
        <div class="row">

            <div class="col-sm-6" >
                <div style="border-radius:8px">

                    <h4><span class="fa fa-reorder" style="color:<?=$biru_neon?>;">&nbsp; </span><b>TOP 10</b> - agent prospect</h4>
                    <table class="table table-hover" style="font-size: 20px;">
                        <tbody id="top_agent">
                            <?php
                                $i=1;
                                foreach ($top_ptp_agent as $key => $value) {
                            ?>
                                    <tr>
                                        <!-- <td class="wtd_no">#<?=$i?></td> -->
                                        <td style="wtd_photo"><span class="fa fa-user-circle-o"></td>
                                        <td style="wtd_name_id"><b><?=$value['name'];?></b><br><small class="wtd_id"><?=$value['id'];?></small></td>
                                        <td><center class="total_ptp"><?=$value['total_ptp'];?></center></td>
                                        <td><?php if($value['total_ptp']!=0 && $i==1){ echo '<span class="fa fa-star" style="color:gold"></span>';} ?></td>
                                    </tr>
                            <?php
                                $i++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <div  style="padding-top: 10px;" >
                    <div class="card" >
                        <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                                <td>
                                    <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                    <b class="class_label text-secondary-emphasis" >avg talk time</b>
                                    <div class="online-indicator pull-right">
                                        <span class="blink"></span>
                                    </div>
                                    </hr>
                                </td>
                            </div>
                            <div style="padding:3%;">
                                <td>
                                        <i class="total_time text-primary-emphasis" id="avg_talktime"><?=$avg_talktime?></i>
                                </td>                   
                                <td>
                                    <i class="fa fa-clock-o pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                                </td>
                            </div>
                        </div>
                        <div class="card_footer bg-secondary">
                            <div>
                                <td>
                                    <span class='text-secondary-emphasis'>Time</span>
                                </td>
                            </div>
                        </div>
                    </div>
                </div>
                <div  style="padding-top:10px">
                    <div class="card" >
                        <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                                <td>
                                    <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                    <b class="class_label text-secondary-emphasis" >avg acw time</b>
                                    <div class="online-indicator pull-right">
                                        <span class="blink"></span>
                                    </div>
                                    </hr>
                                </td>
                            </div>
                            <div style="padding:3%;">
                                <td>
                                        <i class="total_time text-primary-emphasis" id="avg_acw"><?=$avg_acw?></i>
                                </td>                   
                                <td>
                                    <i class="fa fa-clock-o pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                                </td>
                            </div>
                        </div>
                        <div class="card_footer bg-secondary">
                            <div>
                                <td>
                                    <span class='text-secondary-emphasis'>Time</span>
                                </td>
                            </div>
                        </div>
                    </div>
                </div>
                <div  style="padding-top:10px">
                    <div class="card" >
                        <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                                <td>
                                    <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                    <b class="class_label text-secondary-emphasis" >avg wrapup time</b>
                                    <div class="online-indicator pull-right">
                                        <span class="blink"></span>
                                    </div>
                                    </hr>
                                </td>
                            </div>
                            <div style="padding:3%;">
                                <td>
                                    <i class="total_time text-primary-emphasis" id="avg_WRAPUP"><?=$avg_wrp?></i>
                                </td>                   
                                <td>
                                    <i class="fa fa-clock-o pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                                </td>
                            </div>
                        </div>
                        <div class="card_footer bg-secondary">
                            <div>
                                <td>
                                    <span class='text-secondary-emphasis'>Time</span>
                                </td>
                            </div>
                        </div>
                    </div>
                </div>
                <div  style="padding-top:10px">
                    <div class="card" >
                        <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                                <td>
                                    <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                    <b class="class_label text-secondary-emphasis" >avg prospect time</b>
                                    <div class="online-indicator pull-right">
                                        <span class="blink"></span>
                                    </div>
                                    </hr>
                                </td>
                            </div>
                            <div style="padding:3%;">
                                <td>
                                        <i class="total_time text-primary-emphasis" id="avg_ptp_time">0</i>
                                </td>                   
                                <td>
                                    <i class="fa fa-clock-o pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                                </td>
                            </div>
                        </div>
                        <div class="card_footer bg-secondary">
                            <div>
                                <td>
                                    <span class='text-secondary-emphasis'>Time</span>
                                </td>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

</div>
    <script type="text/javascript">
        var ecentrixGroupAgent = "predictive1";	
        var agent_list = <?=$LIST_AGENT?>;	
        var agent = new Array();

        var jumlah_aux = 0;
        var jumlah_talking = 0;
        var jumlah_connected = 0;

        try {
            websocket.on('agentMonitoring',function(event){
                console.log('agentMonitoring = ',event);
            })
            
            ecxSocket.getSocket().emit('agentMonitoring',{"username": GLOBAL_VARS, "key": ecxSocket.getPrivateKey(), "site":GLOBAL_CONTEXT, "group": ecentrixGroupAgent, "action": "read"}, function(data){
				console.log('arr data',data);
                counting_cti(data);
				try {
					
				} catch (error) {
					
				}
				try{
					$.each(data.message, function(a, b){
						ecxSocket.agentMonitoringCrm(b.crm[0]);
					});
					// MonitoringAgent.countingAgentMonitoring();
				}catch(e){
					console.log(e)
				}
			}); 
            
        } catch (error) {
            
        }



            function counting_cti(data_cti){
                console.log('data_cti',data_cti);
                $.each(data_cti.cti_clients, function(a, b){
						console.log('b',b[0])
                        try {
                            let ctidata = b[0];
                            let agentId ='';
                            try {
                                agentId = ctidata[2].split(' ')[1];
                            } catch (error) {
                                agentId = '';
                            }
                            if(agent_list[agentId]){
                                jumlah_connected++;
                                console.log('masukkk',agent_list[agentId]);
                                let status='';
                                try {
                                     status = ctidata[8].split(' ')[1];
                                } catch (error) {
                                     status = '';
                                }
                                console.log('status',status);
                                if(status!=''){
                                    if(status==14){
                                        jumlah_aux++;
                                        console.log('14',agent_list[agentId]);
                                    }else if(status == 7){
                                        jumlah_talking++;
                                    }
                                }

                            }
                            
                        } catch (error) {
                            
                        }
				});
                $("#total_aux").html(jumlah_aux);
                $("#total_talking").html(jumlah_talking);
                $("#total_agent_login").html(jumlah_connected);
            }
   
        function get_data(){
            $.ajax({
                type: "POST",
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard_interactive/get_data_telephony_activity_json",
                async: false,
                dataType: "json",
                success: function (msg) {
                   console.log('msg',msg);
                   
                    $("#total_agent").val(msg.total_agent);
                    $("#avg_talktime").val(msg.avg_acw);
                    $("#avg_acw").val(msg.avg_talktime);
                    
                    let html_top_agent = '';
                    var i=0;
                    $.each(msg.top_ptp_agent, function(i,val){
                       
                   
                        html_top_agent += '<tr>';
                        html_top_agent += '<td style="wtd_photo"><span class="fa fa-user-circle-o"></td>';
                        html_top_agent += '<td style="wtd_name_id"><b>'+val['name']+'</b><br><small class="wtd_id">'+val['id']+'</small></td>';
                        html_top_agent += '<td><center class="total_ptp">'+val['total_ptp']+'</center></td>';

                        if(val['total_ptp']!=0 && i==1){
                            html_top_agent += '<td><span class="fa fa-star" style="color:gold"></span></td>';
                        }else{
                            html_top_agent += '<td></td>';
                        } 
                        html_top_agent += '</tr>';

                        i++;
                    });
                   if(html_top_agent!=''){
                        $("#top_agent").html(html_top_agent);
                   }

                    let update_time = $("#update_time").val();
                    let dt_new = new Date(msg.update_time);
                    let dt_old = new Date(update_time);
                    dt_new = dt_new.getTime();
                    dt_old = dt_old.getTime();
                    
                    console.log('dt_new',dt_new);
                    console.log('dt_old',dt_old);
                },
                error: function () {

                  
                }
            });
        
        }

        setInterval(() => {
            get_data();
        }, 1000*90); //10
    </script>
</body>
</html>