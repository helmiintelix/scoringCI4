<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>monitoring</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
</head>

<body>
    <div class="row">
        <div class="col-sm-12">

            <!-- <ul class="list-group">
		<?php
		foreach ($aux as $key => $value) {
			// echo '<li class="list-group-item">'.$value['break'].'</li>';
		}
		?>
  	</ul> -->
            <!-- <i><small class="text-muted">*pastikan ecx terkoneksi dengan baik</small></i> -->
            <i class="fa fa-refresh pull-right" id="btn-refrash" onClick="onDataReceivedNew()"
                style="font-size:15px;cursor:pointer"></i>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Agent ID</th>
                        <th>Agent Name</th>
                        <th>Activity</th>
                        <th>Start Time</th>
                        <th>Duration</th>
                        <th>Loan No.</th>
                        <th style="text-align:center">Action</th>
                        <th style='display:none'>status</th>
                        <th style='display:none'>Reason</th>
                    </tr>
                </thead>
                <tbody id="list_agent">
                </tbody>
            </table>
        </div>
    </div>

    <?php
	// print_r($aux);
	?>
    <script>
        var isSpy = false;
        var agent = new Array();
        var agent_ori = new Array();

        // function onDataReceivedNew(data){
        // 	console.log('onDataReceivedNew',data);
        // }

        function onDataMonitoring(data) {
            console.log(data);

            let jam = new Date(data.payload.currentStatusTime).getHours();
            let menit = new Date(data.payload.currentStatusTime).getMinutes();
            let startTime = jam + ':' + menit;

            let agent_id = data.payload.id.replace('.', '_');
            let html = '';
            if (data.mode === "ADD") {
                agent.push(agent_id);
                agent_ori.push(data.payload.id);
                html += "<tr id='row" + agent_id + "'>";
                // html += "<td id='nmbr" + data.payload.id + "'></td>";
                html += "<td>" + data.payload.id + "</td>";
                html += "<td>" + data.payload.name + "</td>";
                html += "<td id=status_" + agent_id + ">" + data.payload.currentStatusText + "</td>";
                html += "<td id=start_time_" + agent_id + ">" + startTime + "</td>";
                html += "<td id=duration_" + agent_id + "></td>";
                html += "<td id=accountHandling_" + agent_id + "></td>";
                html += "<td style='text-align:center'>";
                let listen = "LISTEN";
                html +=
                    `<button type='button' onClick="doSpy('${data.payload.id}' ,'${listen}',this)" class='btn btn-success btn-sm'>`;
                html +=
                    "<span data-toggle='tooltip' title='spy' class='glyphicon glyphicon-headphones' aria-hidden='true' style='cursor: pointer;'></span>";
                html += "</button> | ";
                listen = "WHISPER";
                html +=
                    `<button type='button' onClick="doSpy('${data.payload.id}' ,'${listen}',this)" class='btn btn-success btn-sm'>`;
                html +=
                    "<span data-toggle='tooltip' title='coach' class='glyphicon glyphicon-earphone' aria-hidden='true' style='cursor: pointer;'></span>";
                html += "</button>";
                html += "</td>";
                html += "<td style='display:none'><input type='text' id='xstart_time_" + agent_id + "' value='" + data
                    .payload.currentStatusTime + "' /></td>";
                html += "<td style='display:none'></td>";
                html += "</tr>";

                $("#list_agent").append(html);
            } else if (data.mode === "UPDATE") {
                console.log('data.payload.currentStatusText', data.payload.currentStatusText);
                if (data.payload.currentStatusText === 'aux') {
                    $("#status_" + data.payload.id).hide().html(data.payload.currentStatusText + " ( " + data.payload
                        .reason + " )").fadeIn(200);
                } else {
                    $("#status_" + data.payload.id).hide().html(data.payload.currentStatusText).fadeIn(200);
                }
                $("#start_time_" + data.payload.id).html(startTime);
                $("#xstart_time_" + data.payload.id).val(data.payload.currentStatusTime);
            } else if (data.mode === "DELETE") {
                $("#row" + data.payload.id).remove();
            }
        }

        function onLogReceived(log) {
            if (log.level === "ERROR") {
                console.error("[ERROR] %s", log.message);
            } else if (log.level === "WARN") {
                console.warn("[WARN] %s", log.message);
            } else if (log.level === "INFO") {
                console.log("[INFO] %s", log.message);
            }
        }

        function onClosed(event) {
            console.error("[CLOSE] %o", event);
        }
    </script>

    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/ecentrix-deskcoll/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer1/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer2/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer3/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer4/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer5/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer6/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer7/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer8/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer9/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>
    <script
        src="https://<?=getenv('ECX8_URL');?>:8180/script/service/agent/context/dialer10/token/<?= session()->get("SPV_TOKEN"); ?>?debug=true&data_callback=onDataMonitoring&log_callback=onLogReceived&close_callback=onClosed">
    </script>

    <script type="text/javascript">
        var json_aux = JSON.parse('<?= $json_aux ?>');

        function toRefrash() {
            getDataAgent();
        }

        function doSpy(dst, action, elm) {
            if (!isSpy) {
                startSpying(dst, action);
                $(elm).removeClass('btn-success').addClass('btn-danger');
                isSpy = true;
            } else {
                $(elm).removeClass('btn-danger').addClass('btn-success');
                stopSpying();
                isSpy = false;
            }
        }

        function get_account_handling() {
            $.ajax({
                type: "GET",
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "agent_monitoring/agent_monitoring/get_account_handling",
                data: {
                    agent_list: agent_ori
                },
                async: true,
                dataType: "json",
                success: function (msg) {
                    $.each(msg.data, function (i, val) {
                        let agent_id = val.id.replace('.', '_');

                        let curhandle = $("#accountHandling_" + agent_id).html();
                        // console.log("current loan " + curhandle);
                        if (curhandle != val.contract_number_handling) {
                            $("#accountHandling_" + agent_id).html(val.contract_number_handling)
                                .hide().fadeIn(200);
                        }
                    });
                },
                error: function (e) {
                    // console.log(e)
                }
            });
        }
        setInterval(() => {
            // console.log('agent list',agent);
            get_second();
        }, 1000);

        function get_second() {
            try {
                $.each(agent, function (i, row) {

                    let startTime = $("#xstart_time_" + row).val();
                    var curTime = new Date().getTime();

                    let durasi = curTime - startTime;
                    let timeX = new Date(durasi);

                    let menit = timeX.getMinutes();
                    let detik = timeX.getSeconds();
                    if (detik < 10) {
                        detik = '0' + detik.toString();
                    }
                    if (menit < 10) {
                        menit = '0' + menit.toString();
                    }


                    $("#duration_" + row).html(menit + ':' + detik);

                });
            } catch (error) {
                console.log('error', error);
            }
        }

        GLOBAL_INTERVAL = setInterval(() => {
            get_account_handling();
        }, 5000);


        function convertHms(val) {
            // var hms = '02:04:33';   // your input string
            var hms = val; // your input string
            var a = hms.split(':'); // split it at the colons

            // minutes are worth 60 seconds. Hours are worth 60 minutes.
            var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);

            // console.log(seconds);
            return seconds;
        }

        function blink_text() {
            $('.blink').fadeOut(500);
            $('.blink').fadeIn(500);
        }
        setInterval(blink_text, 1000);
    </script>
</body>

</html>