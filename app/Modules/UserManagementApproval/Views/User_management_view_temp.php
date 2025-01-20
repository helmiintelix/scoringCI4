<div class="row">
	<div class="col col-xs-11">
	</div>
	<div class="col col-xs-1">
		<input class="form-control form-control-sm float-end" style="width: 200px;" id="search_approval" type="text"
			placeholder="search" aria-label=".form-control-sm">
	</div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-3" id="list_approval">

</div>
<script type="text/javascript">
	var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/user_management_approval/js/user_management_approval.js?v=<?= rand() ?>"></script>