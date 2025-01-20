<!-- <div class="row">
	<div class="col-xs-12">
		<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
			<button type="button" class="btn btn-outline-primary" id="btn-add-team">add</button>
			<button type="button" class="btn btn-outline-success" id="btn-edit-team">edit</button>
			<button type="button" class="btn btn-outline-danger" id="btn-del-team">delete</button>
		</div>
		<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
			<button type="button" class="btn btn-outline-success" id="btn-export-excel">download excel</button>
		</div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-xs-12"  id="parent">
		<div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
	</div>
</div> -->

<div class="row">
<div class="col col-xs-11">
	<button type="button" class="btn btn-outline-primary" id="btn-add-team">add</button>
</div>
  <div class="col col-xs-1">
  <input class="form-control form-control-sm float-end" style="width: 200px;" id="search_approval" type="text" placeholder="search" aria-label=".form-control-sm">
  </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-3" id="list_approval">

</div>

<button  id="test" hidden type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">AGENT INFORMATION</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<center>
						<div class="row row-cols-12 row-cols-md-12 g-12" id="list_approval_user">

						</div>
						<div class="row row-cols-1 row-cols-md-3 g-3" id="list_approval_user_view_all">

						</div>
						<div class='spinner-grow text-secondary' id="loading" style="display:flex;" role='status'>
							<!-- <span class='sr-only'>Loading...</span> -->
						</div>
					</center>
			</div>
		</div>
	</div>
</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

<script type="text/javascript">
	var user = new Array();
	user = JSON.parse('<?= json_encode($avatar) ?>');

	function onmouse(agentid){
		html = '';
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/get_user?uid="+agentid,
			dataType: "json",
			type: "get",
			async:true,
			success: function (msg) {
				// console.log(msg);
				html += '<div class="col">';
				html += ' <div class="card">';
					html += ' <div style="background-color:#ededed" class="p-2">';

					if (msg.type_collection.toUpperCase() == 'TELECOLL') {
					html += '<span class="badge badge-sm text-bg-success float-end  me-1">TELECOLL</span><br></br>';
					} else if (msg.type_collection.toUpperCase() == 'FIELDCOLL') {
					html += '<span class="badge badge-sm text-bg-primary float-end  me-1">FIELDCOLL</span><br></br>';
					} else {
					html += '<span class="badge badge-smfloat-end me-1"></span><br></br>';
					}

					html += '<center>';
					html += '<img src="' + msg.image + '" id="pic_profile" onClick="selectImage()" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;cursor:pointer"> ';
					html += '</center>';
					html += '</div>';

					html += '<div class="card-body text-center">';
					html += '<b class="card-title text-center">' + msg.name + ' ('+msg.id+')</b>';
					html += '<br>';
					// html += '<i class="card-subtitle mb-2 text-muted " style="font-size: 10px;">' + val.group_id + '</i>';
					html += '<br>';

					html += '<i><small class="text-muted ">' + msg.handphone + '</small></i>';
					html += '<span class="text-muted "> | </span>';
					html += '<i><small class="text-muted ">' + msg.email + '</small></i>';

					html += '<div class="btn-group" role="group" aria-label="Basic example">';
					html += '</div>';
					html += '</div>';
					html += '</div>';
					html += '</div>';

					setTimeout(() => {
						// $("#list_approval_user").fadeIn(3000);
						$("#list_approval_user").html(html);
						$("#loading").hide(); 
					}, 1500);
					
			},
			dataType: 'json',
		});
		document.getElementById("test").click();
		$("#list_approval_user").html('');
		$("#list_approval_user_view_all").html('');
		$("#loading").show();
	}

	function onmousealluser(agentid){
		html = '';
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/get_all_user?uid="+agentid,
			dataType: "json",
			type: "get",
			async:true,
			success: function (msg) {
				console.log(msg);
				$.each(msg, function (i, val) {
					html += '<div class="col">';
					html += ' <div class="card">';
					html += ' <div style="background-color:#ededed" class="p-2">';

					if (val.type_collection.toUpperCase() == 'TELECOLL') {
					html += '<span class="badge badge-sm text-bg-success float-end  me-1">TELECOLL</span><br></br>';
					} else if (val.type_collection.toUpperCase() == 'FIELDCOLL') {
					html += '<span class="badge badge-sm text-bg-primary float-end  me-1">FIELDCOLL</span><br></br>';
					} else {
					html += '<span class="badge badge-smfloat-end me-1"></span><br></br>';
					}

					html += '<center>';
					html += '<img src="' + val.image + '" id="pic_profile" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;cursor:pointer"> ';
					html += '</center>';
					html += '</div>';

					html += '<div class="card-body text-center">';
					html += '<b class="card-title text-center">' + val.name + ' ('+val.id+')</b>';
					html += '<br>';
					// html += '<i class="card-subtitle mb-2 text-muted " style="font-size: 10px;">' + val.group_id + '</i>';
					html += '<br>';

					html += '<i><small class="text-muted ">' + val.handphone + '</small></i>';
					html += '<span class="text-muted "> | </span>';
					html += '<i><small class="text-muted ">' + val.email + '</small></i>';

					html += '<div class="btn-group" role="group" aria-label="Basic example">';
					html += '</div>';
					html += '</div>';
					html += '</div>';
					html += '</div>';
				});

				setTimeout(() => { 
					// $("#list_approval_user_view_all").fadeIn(3000);
					$("#list_approval_user_view_all").html(html).fadeIn("slow");
					$("#loading").hide(); 
				}, 1500);
			},
			dataType: 'json',
		});
		document.getElementById("test").click();
		$("#list_approval_user_view_all").html('');
		$("#list_approval_user").html('');
		$("#loading").show();
	}
</script>
<!-- <script src="http://malsup.github.com/jquery.form.js"></script> -->
<script src="<?=base_url();?>modules/team_management/js/team_work.js"></script>
<script src="<?=base_url();?>modules/team_management/js/team_work_new.js"></script>