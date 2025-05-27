<?
	$card_numbers = "";
	$i = 1;
	foreach($contracts as $row){
?>
<div id="connected_result_<?=$row["CM_CARD_NMBR"];?>" class="connected_result">
    <hr class="col-sm-12"/>
    <? if($row["CF_AGENCY_STATUS_ID"] != "2"){ ?>
    <div id="ptp_input<?=$row["CM_CARD_NMBR"];?>">
        <div class="row mb-2">
            <label class="form-label fs-6" for="txt_ptp_date"><small>PTP DATE :</small></label>
            <div class="col-sm-12">
                <input type="text" id="txt_ptp_date" name="txt_ptp_date" class="form-control"
                    readonly>
            </div>
        </div>
        <div class="row mb-2">
            <label class="form-label fs-6" for="txt_ptp_date"><small>MIN PAYMENT :</small></label>
            <div class="col-sm-12">
                <span><?=$min_payment;?><span>
            </div>
        </div>
        <div class="row mb-2">
            <label class="form-label fs-6" for="txt_ptp_amount"><small>PTP AMOUNT :</small></label>
            <div class="col-sm-12">
                <input type="text" id="txt_ptp_amount" name="txt_ptp_amount" class="form-control"
                    style="text-align: right;" onKeypress="return numbersOnly(this, event);" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <label class="form-label fs-6" for="select_cara_bayar"><small>CARA BAYAR :</small></label>
            <div class="col-sm-12">
                <?
                        $attributes = 'class="form-control mandatory" id="select_cara_bayar"';
                        echo form_dropdown('select_cara_bayar', $cara_bayar, "", $attributes);
                    ?>
            </div>
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
<script type="text/javascript">
    var ptp_grace_period = '<?=$grace_period?>';
    var endDate = moment(ptp_grace_period, "YYYY-MM-DD");
</script>
<script src="<?= base_url(); ?>modules/detail_account/js/connected_result.js?v=<?= rand() ?>">
</script>