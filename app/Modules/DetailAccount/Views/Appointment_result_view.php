<?
	$card_numbers = "";
	$i = 1;
	foreach($contracts as $row){
?>
<div id="appointment_result_<?=$row["CM_CARD_NMBR"];?>" class="appointment_result">
	<hr class="col-sm-12" />
	<? if($row["CF_AGENCY_STATUS_ID"] != "2"){ ?>
	<div id="ptp_input<?=$row["CM_CARD_NMBR"];?>">
		<label class="form-label fs-6" for="txt_ptp_date"><small>APPOINTMENT
				TIME :</small></label>
		<div class="col-sm-12">
			<input type="time" class="form-control" id="appt" name="appt">
		</div>
	</div>
	<? } ?>

</div>
<?
		$card_numbers .= "|".$row["CM_CARD_NMBR"];
		$i++;
	}
?>
<input type="hidden" id="txt_card_numbers" name="txt_card_numbers" value="<?=$card_numbers;?>">