<form role="form" id="form_add_team" class="needs-validation" name="form_add_team" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div>
		<label for="form-field-select-2">Team Name</label>
		<input type="text" name="team_name" id="team_name" class="form-control mandatory" required/>
	</div>
	<!-- <input class="form-control datetimepicker" id="datetimepicker" type="text" placeholder="dd/mm/yyyy hour : minute" data-options='{"enableTime":true,"dateFormat":"d/m/y H:i","disableMobile":true}' /> -->
	<br />
	<div class="mb-3 ">
				<label for="btnradio_typeCollection1" class="fs-6 text-capitalize">Type Collection</label><br>
				<div class="btn-group" role="group" id="radiotipecollectin" aria-label="Basic radio toggle button group" style="width: 100%;">
					<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection1" typeColl='TeleColl' autocomplete="off" checked>
					<label class="btn btn-outline-success" for="btnradio_typeCollection1">TELECOLL</label>

					<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection2" typeColl='FieldColl' autocomplete="off" >
					<label class="btn btn-outline-primary" for="btnradio_typeCollection2">FIELDCOLL</label>
				</div>
				<input type='hidden' name='opt-type_collection' id="" value='TeleColl'/>
			
			</div>
			<br/>
	<div>
		<label for="form-field-select-2">Team Leader</label>
		<?
			$arrData = [];
			foreach($coord_list as $value){
				$arrData[$value["value"]] = $value["item"];
			}
			$attributes = 'class="form-control mandatory" id="coord_list" data-placeholder ="[select]" required';
			echo form_dropdown('coord_list', $arrData, "", $attributes);
		?>
	</div>
	
	<br />
	<div>
		<label for="form-field-select-2">Supervisor Name</label>
		<?
			$attributes = 'class="form-control mandatory" id="spv_list" required';
			echo form_dropdown('spv_list', $spv_list, "", $attributes);
		?>
	</div>
	<br />
	
	<div class="row">
		<div class="col-md-6">
			<div class="card" style="background-color:#ededed">
				<div class="card-body">
					<div class="hs-searchbox"style="display:flex;">
						
						<input type="text" class="form-control" id="search" placeholder="Search Agent For Assign To Team ..." autocomplete="off">
						<!-- <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span> -->
						
					</div>
					<?
						$attributes = ' id="from_agent" multiple="multiple" style="width:170px;height:85px;display:none;"';
						echo form_dropdown('from_agent[]', $agent_list, "", $attributes);
						
					?>
					<br/>
					<div class="row overflow-auto" id="list_agent">
					
					</div>
					
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card" style="background-color:#ededed">
				<div class="card-body">
					<div class="hs-searchbox"style="display:flex;">
						
						<input type="text" class="form-control" id="search2" placeholder="Search Agent For Unassign From Team ..." autocomplete="off">
						<!-- <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span> -->
						
					</div>
					<?
						$attributes = ' id="tom_agent" multiple="multiple" style="width:170px;height:85px;display:none;"';
						echo form_dropdown('tom_agent[]', array(), "", $attributes);
					?>
					<br/>
					<div class="row overflow-auto" id="list_agent_assigned">
					
					</div>
				</div>
			</div>
		</div>
	</div>
	<br/>
	<div>
		<label for="form-field-select-2">Description</label>
		<textarea class="form-control" id="description" name="description" ></textarea>
	</div>
</form>

<style>
	</style>
<!-- <script src="<?=base_url();?>modules/team_management/js/add_team_work.js"></script> -->
<script type="text/javascript">

	var agentAssign = new Array();
	var agentAssignList = new Array(); // for dropdown
	var agentList = JSON.parse('<?= json_encode($agent_list)?>');
	console.log(agentList);

	function getAgent(agentList) {
		html = '';
		$.each(agentList, function (i, val) {
			if (typeof val.item !== 'undefined') {
				val = val.item;
			}
			html += '<div class="card mb-1" style="max-width: 540px;" onClick="doAssign(\'' + val.id + '\',\'' + i + '\')">';
			html += '<div class="row g-0">';
			html += '<div class="col-md-4">';
			html += '<center>';
			html += '<img src="' + val.image + '" class="img-fluid rounded-start" alt="..." style="margin-top:10%;width:50px;height:50px;display:block;cursor:pointer">';
			html += '</center>';
			html += '</div>';
			html += '<div class="col-md-8">';
			html += '<div class="card-body">';
			
			if (val.type_collection.toUpperCase() == 'TELECOLL') {
					html += '<h5 class="card-title badge badge-sm text-bg-success">TELECOLL</h5>';	
					// html += '<span class="badge badge-sm text-bg-success float-end  me-1">TELECOLL</span><br></br>';
					} else if (val.type_collection.toUpperCase() == 'FIELDCOLL') {
						html += '<h5 class="card-title badge badge-sm text-bg-primary">FIELDCOLL</h5>';
						// html += '<span class="badge badge-sm text-bg-primary float-end  me-1">FIELDCOLL</span><br></br>';
					} else {
						html += '<span class="badge badge-smfloat-end me-1"></span><br></br>';
					}
			html += '<p class="card-text">' + val.name + '</p>';
			// html += '<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
		});

		$("#list_agent").html(html);
	};
	
	
	getAgent(agentList);

	function getAgentAssigned(agentAssign) {
		console.log(agentAssign)
		html = '';
		$.each(agentAssign, function (i, val) {
			if (typeof val.item !== 'undefined') {
				val = val.item;
			}
			html += '<div class="card mb-1" style="max-width: 540px;" onClick="doUnAssign(\'' + val.id + '\',\'' + i + '\')">';
			html += '<div class="row g-0">';
			html += '<div class="col-md-4">';
			html += '<center>';
			html += '<img src="' + val.image + '" class="img-fluid rounded-start" alt="..." style="margin-top:10%;width:50px;height:50px;display:block;cursor:pointer">';
			html += '</center>';
			html += '</div>';
			html += '<div class="col-md-8">';
			html += '<div class="card-body">';
			
			if (val.type_collection.toUpperCase() == 'TELECOLL') {
					html += '<h5 class="card-title badge badge-sm text-bg-success">TELECOLL</h5>';	
					// html += '<span class="badge badge-sm text-bg-success float-end  me-1">TELECOLL</span><br></br>';
					} else if (val.type_collection.toUpperCase() == 'FIELDCOLL') {
						html += '<h5 class="card-title badge badge-sm text-bg-primary">FIELDCOLL</h5>';
						// html += '<span class="badge badge-sm text-bg-primary float-end  me-1">FIELDCOLL</span><br></br>';
					} else {
						html += '<span class="badge badge-smfloat-end me-1"></span><br></br>';
					}
			html += '<p class="card-text">' + val.name + '</p>';
			// html += '<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
		});

		$("#list_agent_assigned").html(html);
	};

	$("#search").keyup(function () {
		let item = $(this).val();
		console.log('item', item);
		cari2(item);
	});

	$("#search2").keyup(function () {
		let item = $(this).val();
		console.log('item', item);
		cariunassign(item);
	});

	var move_list = function (destinationid) {
		$("#from_agent	option:selected").appendTo("#" + destinationid);
	}

	function doAssign(id,index) {
		$.each(agentList, function(i,val) {
			if(val.id == id)
			{
				$('#tom_agent').append(`<option value="${val.id}" selected>
				${val.name}
					</option>`);
				$('#tom_agent').trigger("chosen:updated");
				agentAssignList[val.id] = val.name;
				agentAssign.push(val);
			}
		});

		console.log(agentAssignList);
		getAgentAssigned(agentAssign);
		
		agentList.splice(index,1);
		
		getAgent(agentList);
	}

	function doUnAssign(id,index) {
		$.each(agentAssign, function(i,val) {
			if(val.id == id)
			{
				// $('#tom_agent').append(`<option value="${val.id}">
				// ${val.name}
				// 	</option>`);
				// $('#tom_agent').trigger("chosen:updated");
				agentList.push(val);
			}
		});

		getAgent(agentList);
		

		agentAssign.splice(index,1);

		getAgentAssigned(agentAssign);
		
	}

	function cari2(val) {
		var fuseOptions = {
		threshold: 0.2,
		keys: [
			// "id",
			"id",
			"name"
		]
		};
		if (val == '') {
			getAgent(agentList);
		} else {
			const fusex = new Fuse(agentList, fuseOptions);
			let hasil = fusex.search(val);
			getAgent(hasil);
		}
	};

	function cariunassign(val) {
		var fuseOptions = {
		// isCaseSensitive: false,
		// includeScore: false,
		// shouldSort: true,
		// includeMatches: false,
		// findAllMatches: false,
		// minMatchCharLength: 1,
		// location: 0,
		threshold: 0.2,
		// distance: 100,
		// useExtendedSearch: false,
		// ignoreLocation: false,
		// ignoreFieldNorm: false,
		// fieldNormWeight: 1,
		keys: [
			// "id",
			"id",
			"name"
		]
		};
		if (val == '') {
			getAgentAssigned(agentAssign);
		} else {
			const fusex = new Fuse(agentAssign, fuseOptions);
			let hasil = fusex.search(val);
			getAgentAssigned(hasil);
		}
	};

	$("[name=btnradio_typeCollection]").change(function(e) {
		let tipe = $(this).attr('typeColl');
		console.log('tipe',tipe);
		$("#opt-type_collection").val(tipe);

		console.log(tipe);

		var agentListopt = new Array();
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/get_all_user_nofilter?type_collection="+tipe,
			dataType: "json",
			type: "get",
			success: function (msg) {
				var agentListopt = msg;
					
					agentAssign = new Array();
					agentList = agentListopt;

					$("#tom_agent").empty();

					getAgent(agentList);
					getAgentAssigned();
			},
			dataType: 'json',
		});
	});

	// function test(){

	// 	var options = {
	// 					url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/save_team",
	// 					type: "post",
	// 					// beforeSubmit: jqformValidate,
	// 					// success: showFormResponse,
	// 					dataType: 'json',
	// 				};
	// 				// if (checkValidate() == false) { return false; }
					
	// 				$('form').ajaxSubmit(options);
	// }

	// $('#form_add_team').submit(function ( e ) {
	// 	var data;

	// 	data = new FormData();
	// 	console.log(data);
	// 	// data.append( 'file', $( '#file' )[0].files[0] );

	// 	// $.ajax({
	// 	// 	url: 'http://hacheck.tel.fer.hr/xml.pl',
	// 	// 	data: data,
	// 	// 	processData: false,
	// 	// 	type: 'POST',
	// 	// 	success: function ( data ) {
	// 	// 		alert( data );
	// 	// 	}
	// 	// });

	// 	e.preventDefault();
	// });
	
</script>