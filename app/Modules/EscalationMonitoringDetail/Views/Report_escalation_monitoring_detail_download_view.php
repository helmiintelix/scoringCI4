<?
	ini_set('memory_limit', '4096M');
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=reportEscalationMonitoringDetail.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .num {
            mso-number-format: General;
        }

        .text {
            mso-number-format: "\@";
            /*force text*/
        }
    </style>
</head>

<body style="background-color: #fff;">
    <h4><?=$title?></h4>
    <h4>Period From : <?=$start==''?'Start Date':$start?></h4>
    <h4>Period To : <?=$end==''?'End Date':$end?></h4>
    <table class="table table-bordered">
        <tr>
            <th>TeamleaderID</th>
            <th>Teamleader Name</th>
            <th>Status</th>
            <th>Contract No</th>
            <th>Customer Name</th>
            <th>Product</th>
            <th>Bucket</th>
            <th>Class</th>
            <th>Layer 1 Result</th>
            <th>Layer 3 Result</th>
            <th>Remark</th>
            <th>Agent Name</th>
            <th>Date Time</th>
            <th>Time Duration From Escalation</th>

        </tr>


        <?php
		$html = '';
		if(count($arrData) > 0){
			foreach($arrData as $val){
		
			 $html .= '<tr>
							<td>'.$val['spv_id'].'</td>
							<td>'.$val['spv_name'].'</td>
							<td>'.$val['spcl_status'].'</td>
							<td>'.$val['contract_number'].'</td>
							<td>'.$val['customer_name'].'</td>
							<td>-</td>
							<td>'.$val['bucket'].'</td>
							<td>'.$val['class_name'].'</td>
							<td>'.$val['contact_status'].'</td>
							<td>'.$val['call_result'].'</td>
							<td>'.$val['notepad1'].'</td>
							<td>'.$val['first_name'].'</td>
							<td>'.$val['call_time'].'</td>
							<td>'.$val['duration_escalation'].'</td>
						</tr>';
			}
		}else{
			$html .= '<tr>
							<td colspan="14" align="center">no data</td>
						</tr>';
		}
		echo $html;
		?>

    </table>
</body>

</html>