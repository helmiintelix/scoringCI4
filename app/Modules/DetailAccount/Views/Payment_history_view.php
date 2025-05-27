<div class="card">
	<div class="card-body" id="call_history" style="display: block;">
		<div class="col-xs-12" id="parent">
			<div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var currCustId = '<?=$customer_id;?>';
	var currCardNo = '<?=$card_no;?>';
</script>
<script src="<?= base_url(); ?>modules/detail_account/js/payment_history.js?v=<?= rand() ?>">
</script>