<style>
  
    .shdw:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.7);
    }
    .progress_2{
        border-radius: 40px;
        margin-bottom :10px;
    }
    .progress_3{
        background-color: #689550;
    }
    .total_loan{
        /* color: #3C486B; */
        font-weight: bold;
        font-size : 32px;
    }
    .class_label{
        /* color: #3C486B; */
        font-weight: 100;
        font-size:14px;
        font-family: sans-serif;
    }
    div.online-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: #0fcc45;
    border-radius: 50%;
    position: relative;
    }
    span.blink {
    display: block;
    width: 10px;
    height: 10px;
    background-color: #0fcc45;
    opacity: 0.7;
    border-radius: 50%;
    animation: blink 1s linear infinite;
    }

    @keyframes blink {
    100% { transform: scale(2, 2); 
            opacity: 0;
            }
    }
</style>
<div class="row">
    <div class="col-sm-12" style="border-radius: 7px;padding:1%;">
        <div class="row" >

        <?php foreach ($data_class_wallboard as $key => $value) { ?>
            <div class="col-sm-3 m-2" >
                <div class="card shdw" >
                <div class="card-body">
                    <table style="width:100%">
                        <tr>
                            <td>
                                <i class="fa fa-bar-chart" style="font-size:14px;color:#3C486B"></i>
                                <span class="class_label text-primary-emphasis"><?=$value['classification_id']?> - <?=$value['classification_name']?></span>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="total_loan text-primary-emphasis"><?=$value['total']?></i>
                                <i class="fa far fa-credit-card pull-right" style="font-size:30px;color:#3C486B;margin-left: 5px;"></i>
                            </td>
                        </tr>
                    </table>
                    
                
                    <div class="progress progress_2" >
                        <div id="progress_<?=$value['classification_id']?>" class="progress-bar progress_3" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:<?=$value['progress']?>%">
                            <?=$value['worked']?> (<?=$value['progress']?>%)
                        </div>
                    </div>
                    <table class="table table-striped table-hover" style="width:100%;color:#3C486B;font-size: 16px;">
                        <tr>
                            <td><b>PTP</b></td>
                            <td style="text-align: end;" id="ptp_<?=$value['classification_id']?>"><?=$value['ptp']?></td>
                        </tr>
                        <!-- <tr>
                            <td><b>Voice Mail</b></td>
                            <td style="text-align: end;" id="voice_mail_<?=$value['classification_id']?>"><?=$value['voice_mail']??''?></td>
                        </tr> -->
                        <tr>
                            <td><b>Not Answer</b></td>
                            <td style="text-align: end;" id="no_answer_<?=$value['classification_id']?>"><?=$value['no_answer']?></td>
                        </tr>
                        <tr>
                            <td><b>Not PTP</b></td>
                            <td style="text-align: end;" id="not_promise_<?=$value['classification_id']?>"><?=$value['no_ptp']?></td>
                        </tr>
                    </table>
                </div>
                </div>
            </div>
        <?php } ?>
        </div>

    </div>
</div>
<script type="text/javascript">
        
        function get_data(){
            $.ajax({
                type: "get",
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard_interactive/gat_data_class_wallboard_json",
                async: false,
                dataType: "json",
                success: function (msg) {
                   console.log('msg',msg);
                  
                    $.each(msg,function(i,val){
                        $("#progress_"+val['classification_id']).css('width',val.progress+'%');
                        $("#progress_"+val['classification_id']).html(val.worked+' ('+val.progress+'%)');
                        $("#ptp_"+val['classification_id']).html(val.ptp);
                        // $("#voice_mail_"+val['classification_id']).html(val.voice_mail);
                        $("#no_answer_"+val['classification_id']).html(val.no_answer);
                        $("#not_promise_"+val['classification_id']).html(val.no_ptp);
                    });

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
