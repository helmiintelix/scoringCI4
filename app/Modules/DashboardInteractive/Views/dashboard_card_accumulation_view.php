<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        
        .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.7);
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
            padding: 2%;
            border-radius:  5px 5px 0px 0px;
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
        .class_label {
            /* color: #555454; */
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

        @keyframes blink {
        100% { transform: scale(2, 2); 
                opacity: 0;
                }
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
    <!-- <input type="text" id="update_time" value="<?=date('Y-m-d H:i:s')?>" /> -->
    <!-- <i style="color:darkgray;font-size: 12px;font-weight: 100;">update time:</i><i style="color:darkgray;font-size: 12px;font-weight: 100;" id="update_time"></i> -->
    <div class="col-sm-12 bg-body-secondary" style="margin-top:1px;border-radius: 7px;padding:1%;">
        <div class="row">

            <div class="col-sm-3" >
                <div class="card"  data-toggle="tooltip" title="Total Data dalam antrian call" data-placement="top" >
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
                                    <i class="total_loan text-primary-emphasis" id="total_data"><?=$total_data?></i>
                            </td>                   
                            <td>
                                <i class="fa far fa-credit-card pull-right" style="font-size:30px;color:<?= $hijau_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3" >
                <div class="card"  data-toggle="tooltip" title="Total dial dalam hari ini" data-placement="top">
                    <div style="width:100%;height:80%;">
                        <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">dialed</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_dialed"><?=$total_dialed?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-phone pull-right" style="font-size:30px;color:<?=$biru_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Phone Number</span>
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
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">connect</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_connect"><?=$total_connect?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-phone pull-right" style="font-size:30px;color:<?=$biru_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Phone Number</span>
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
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">unconnect</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_unconnect"><?=$total_unconnect?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-phone pull-right" style="font-size:30px;color:<?=$biru_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Phone Number</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- end col sm 12 -->
    </div> 
    
    <div class="col-sm-12 bg-body-secondary" style="margin-top:15px;border-radius: 7px;padding:1%; " >
        <div class="row">

            <div class="col-sm-4" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                    <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis" >contacted</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_contact"><?=$total_contact?></i>
                            </td>                   
                            <td>
                                <i class="fa far fa-credit-card pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                    <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">uncontact</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis"  id="total_ncontact"><?=$total_ncontact?></i>
                            </td>                   
                            <td>
                                <i class="fa far fa-credit-card pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                    <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">untouch</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_untouch"><?=$total_untouch?></i>
                            </td>                   
                            <td>
                                <i class="fa far fa-credit-card pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 bg-body-secondary" style="margin-top:15px;border-radius: 7px;padding:1%; " >
        <div class="row">

            <div class="col-sm-6" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                    <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>    
                                <b class="class_label text-secondary-emphasis">prospect</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_ptp"><?=$total_ptp?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-handshake-o pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                    <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:<?=$biru_neon?>;"></i>
                                <b class="class_label text-secondary-emphasis">%prospect</b>
                                <div class="online-indicator pull-right">
                                    <span class="blink"></span>
                                </div>
                                </hr>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_persen_ptp"><?=$total_persen_ptp?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-handshake-o pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 bg-body-secondary" style="margin-top:15px;border-radius: 7px;padding:1%; " >
        <div class="row">

            <div class="col-sm-3" >
                <div class="card" >
                    <div style="width:100%;height:80%;">
                    <div class="card_header bg-secondary">
                            <td>
                                <i class="fa fa-bookmark" style="font-size:14px;color:#848683;"></i>
                                <b class="class_label text-secondary-emphasis">prospect_active</b>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_ptp_active"><?=$total_ptp_active?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-handshake-o pull-right" style="font-size:30px;color:<?=$hijau_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
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
                                <i class="fa fa-bookmark" style="font-size:14px;color:#848683;"></i>
                                <b class="class_label text-secondary-emphasis">prospect_keep</b>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_keep_ptp"><?=$total_keep_ptp?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-handshake-o pull-right" style="font-size:30px;color:<?=$hijau_neon?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
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
                                <i class="fa fa-bookmark" style="font-size:14px;color:#848683;"></i>
                                <b class="class_label text-secondary-emphasis">prospect_broken</b>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_broken_ptp"><?=$total_broken_ptp?></i>
                            </td>                   
                            <td>
                                <i class="fa fa-handshake-o pull-right" style="font-size:30px;color:<?=$merah?>;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
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
                                <i class="fa fa-bookmark" style="font-size:14px;color:#848683;"></i>
                                <b class="class_label text-secondary-emphasis">repitition</b>
                            </td>
                        </div>
                        <div style="padding:3%;">
                            <td>
                                    <i class="total_loan text-primary-emphasis" id="total_repetition">0</i>
                            </td>                   
                            <td>
                                <i class="fa fa-handshake-o pull-right" style="font-size:30px;color:#3C486B;margin-top: 5px;"></i>
                            </td>
                        </div>
                    </div>
                    <div class="card_footer bg-secondary">
                        <div>
                            <td>
                                <span class='text-secondary-emphasis'>Loan</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
    <script type="text/javascript">
        
        function get_data(){
            $.ajax({
                type: "POST",
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard_interactive/get_data_summary_json",
                async: false,
                dataType: "json",
                success: function (msg) {
                   console.log('msg',msg);
                    $("#total_data").html(msg.total_data);
                    $("#total_dialed").html(msg.total_dialed);
                    $("#total_connect").html(msg.total_connect);
                    $("#total_unconnect").html(msg.total_unconnect);
                    $("#total_contact").html(msg.total_contact);
                    $("#total_ncontact").html(msg.total_ncontact);
                    $("#total_untouch").html(msg.total_untouch);
                    $("#total_ptp").html(msg.total_ptp);
                    $("#total_persen_ptp").html(msg.total_persen_ptp);

                    $("#total_ptp_active").html(msg.total_ptp_active);
                    $("#total_keep_ptp").html(msg.total_keep_ptp);
                    $("#total_broken_ptp").html(msg.total_broken_ptp);
                    $("#total_repetition").html(msg.total_repetition);

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