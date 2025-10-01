<style>
	.no-search .select2-search {
		display:none
	}	
</style>

<div class="vspace-xs-4"></div>

<div class="row">
	<div class="col-xs-12">
		<div class="table-responsive">
			<table id="sample-table-1" class="table table-striped table-bordered table-hover" style="width: 1000px">
				<thead>
					<tr>
						<th>Tiering ID</th>
						<th>Tiering Label</th>
						<!-- <th>Bucket</th>
						<th>LOB</th> -->
						<th>Score Tiering</th>
						<th>Score Type</th>
						<!--
						<th>Product</th>
						<th>Bucket</th>
						<th>DPD</th>
						<th>Assign To</th>
						-->
						<th>Cycle</th>
						<th>Bucket</th>
						<th>LOB</th>
						<th>Total Data</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					<?
						if (!empty($tiering_list)) {
							foreach ($tiering_list AS $row){
						
						
					?>
							<tr>
								<td><?=$row['tiering_id']?></td>
								<td><?=$row['tiering_name']?></td>
								<!-- <td><?=$row['bucket']?></td>
								<td><?=$row['lob']?></td> -->
								<td><?=$row['score_tiering']?></td>
								<td><?=$row['score_type']?></td>
								<!--
								<td><?//=$row['product']?></td>
								<td><?//=$row['bucket']?></td>
								<td><?//=$row['dpd']?></td>
								<td><?//=$row['assign_to']?></td>
								-->
								<td><?=$row['cycle_name']?></td>
								<td><?=$row['bucket']?></td>
								<td><?=$row['lob']?></td>
								<td align="right"><?=$row['total_data']?></td>
								<td>
									<button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center" onclick="loadScoreTieringEditForm('<?=$row['tiering_id']?>')">
										<i class="icon-edit bigger-110 me-1" aria-hidden="true"></i>
										Edit
									</button>
								</td>
							</tr>
					<?
							}
						} 	
					?>
				</tbody>
			</table>
		</div><!-- /.table-responsive -->
	</div><!-- /span -->
</div><!-- /row -->

<div class="vspace-xs-8"></div>

<script>
	var loadScoreTieringEditForm = function(scheme_id){ 
	//$("#page").html(GLOBAL_MAIN_VARS["progress_indicator"]);
	// load content
	//$("#page").load(GLOBAL_MAIN_VARS["SITE_URL"] + 'scoring/tiering/' + scheme_id);
	
	var link = GLOBAL_MAIN_VARS["SITE_URL"] + 'scoring/tiering/' + scheme_id;
	
	$("#admin-wrapper").slideUp("fast", function(){
		$("#admin-wrapper").html(GLOBAL_MAIN_VARS["SPINNER"]).load(link, function(){
			$("#admin-wrapper").slideDown("slow");
		})
	})
};
</script>