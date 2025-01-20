<div class="row mb-3">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <?php
				if($status=='NEW'){
					echo '<button type="button" class="btn btn-outline-primary" id="btn-request-only-flag">Search</button>';
					echo '<button type="button" class="btn btn-outline-success" id="btn-assign-to-tl">Assign to TL</button>';
				}
				else if($status=='ASSIGNED'){
					echo '<button type="button" class="btn btn-outline-primary" id="btn-request-frmagt">Request</button>';
				}
				else if($status=='APPROVAL'){
					echo '<button type="button" class="btn btn-outline-danger" id="btn-approval">Approval</button>';
					
				}else{
					?>
            <?php
				}
			?>
        </div>
    </div>
</div>
<div class="card">

    <div class="card-header">
        Assignment Restructure
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridAr" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
    var status = "<?=$status?>";
</script>
<script src="<?= base_url(); ?>modules/workflow_pengajuan/js/assignment_restructure.js?v=<?= rand() ?>">
</script>