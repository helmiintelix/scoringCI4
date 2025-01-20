<form role="form" id="form_add_team" name="form_add_team">
	<input type="hidden" name="team_id" id="team_id" value="<?=$team_id?>" />
	<div>
		<label for="form-field-select-2">Team Name</label>
		<input type="text" name="team_name" id="team_name"  class="form-control" />
	</div>
	
	<br />
	<div>
		<label for="form-field-select-2">Team Leader</label>
		<?
		foreach($coord_list as $value){
				$arrData[$value["value"]] = $value["item"];
			}
			$attributes = 'class="form-control" id="coord_list"';
			echo form_dropdown('coord_list', $arrData, "", $attributes);
		?>
	</div>
	<br />

	<div>
		<label for="form-field-select-2">Supervisor Name</label>
		<?
			$attributes = 'class="form-control" id="spv_list"';
			echo form_dropdown('spv_list', $spv_list, "", $attributes);
		?>
	</div>
	<br />
	
	<div class="form-group" >
		<label class="col-sm-3 control-label no-padding-right" for="opt_coll_type">Collection Type</label>
			
		<div class="col-sm-9">
			<?
			
				$option=array(
							'1'=>'Telecoll',
							'2'=>'FieldColl'
				);
				$attributes = 'class="col-xs-10 col-sm-8 " id="opt_coll_type"';
				echo form_dropdown('opt_coll_type',$option, @$data_team['flag_team'], $attributes);
			?>
		</div>
	</div>
	
	<br />
	<div id="assign_tele">
		<label for="opt-agent-assigned">Telecoll Assignment to Team</label>
		<br>
		<?
			$attributes = ' id="from_agent" multiple="multiple" style="width:170px;height:85px;"';
			echo form_dropdown('from_agent[]', $agent_list, "", $attributes);
		?>
		&nbsp;
		<input id="moveleft_agent" type="button" value=" < ">
		&nbsp;
		<input id="moveright_agent" type="button" value=" > " >
		&nbsp;
		<?
			$attributes = ' id="tom_agent" multiple="multiple" style="width:170px;height:85px;"';
			echo form_dropdown('tom_agent[]', array(), "", $attributes);
		?>
	</div>
	<br />
	<div id="assign_fc">
		<label for="opt-agent-assigned_fc">FieldColl Assignment to Team</label>
		<br>
		<?
			$attributes = ' id="from_agent_fc" multiple="multiple" style="width:170px;height:85px;"';
			echo form_dropdown('from_agent_fc[]', $fildcoll_list, "", $attributes);
		?>
		&nbsp;
		<input id="moveleft_agent_fc" type="button" value=" < ">
		&nbsp;
		<input id="moveright_agent_fc" type="button" value=" > " >
		&nbsp;
		<?
			$attributes = ' id="tom_agent_fc" multiple="multiple" style="width:170px;height:85px;"';
			echo form_dropdown('tom_agent_fc[]', array(), "", $attributes);
		?>
	</div>
	<div>
		<label for="form-field-select-2">Description</label>
		<textarea class="form-control" id="description" name="description" ></textarea>
	</div>
</form>
<script type="text/javascript">
	var team_id = '<?=$team_id?>';
	
	function first_load(){
		var type_coll = $('#opt_coll_type').val();
		if(type_coll=='1'){
			$("#assign_tele").show();
			$("#assign_fc").hide();
		}else{
			$("#assign_tele").hide();
			$("#assign_fc").show();
		}
	
	}

setTimeout(first_load,300);
$("#opt_coll_type").on("change", function() {
					
		if($('#opt_coll_type').val()=="1"){
		$("#assign_fc").hide();
		$("#assign_tele").show();
		
		
		}else{
		$("#assign_fc").show();
		$("#assign_tele").hide();
		
		}
});
	
</script>
<script src="<?=base_url();?>modules/team_management/js/edit_team_work.js"></script>