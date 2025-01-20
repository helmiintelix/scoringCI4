<form role="form" id="form_add_team" class="needs-validation" name="form_add_team" novalidate>
	<div>
		<label for="form-field-select-2">Team Name</label>
		<input type="text" name="team_name" id="team_name" class="form-control mandatory" required/>
	</div>
	<input class="form-control datetimepicker" id="datetimepicker" type="text" placeholder="dd/mm/yyyy hour : minute" data-options='{"enableTime":true,"dateFormat":"d/m/y H:i","disableMobile":true}' />
	<br />
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
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="opt_code_thirt">Collection Type</label>

		<div class="col-sm-9">
			<?
				$option=array(
							'1'=>'Telecoll',
							'2'=>'FieldColl'
				);
				$attributes = 'class="col-xs-10 col-sm-8 " id="opt_code_thirt" required';
				echo form_dropdown('opt_coll_type',$option, "", $attributes);
			?>
		</div>
	</div>
	

	<div id ='agent-assigned'>
		<label for="opt-agent-assigned">Telecoll Assignment to Team</label>
		<br>
		<?
		if(!empty($agent_list)){
			echo '<p>agent available</p>';
		}else{
			echo '<p style="color:red">agent not available. all agent assignment to team</p>';
		}
			$attributes = ' id="from_agent" multiple="multiple" style="width:170px;height:85px;"';
			echo form_dropdown('from_agent[]', $agent_list, "", $attributes);
			
		?>
		&nbsp;
		<input id="moveleft_agent_tele" type="button" value=" < ">
		&nbsp;
		<input id="moveright_agent_tele" type="button" value=" > " >
		&nbsp;
		<?
			$attributes = ' id="tom_agent" multiple="multiple" style="width:170px;height:85px;"';
			echo form_dropdown('tom_agent[]', array(), "", $attributes);
		?>
	</div>
	<br />
	<div id = 'fc-assigned'>
		<label for="opt-fc-assigned">FieldColl Assignment to Team</label>
		<br>
		<?
		if(!empty($fildcoll_list)){
			echo '<p>FieldColl available</p>';
		}else{
			echo '<p style="color:red">FieldColl not available. all FieldColl assignment to team</p>';
		}
			$attributes = ' id="from_agent_fc" multiple="multiple" style="width:170px;height:85px;"';
			echo form_dropdown('from_agent_fc[]', $fildcoll_list, "", $attributes);
			
		?>
		&nbsp;
		<input id="moveleft_agent" type="button" value=" < ">
		&nbsp;
		<input id="moveright_agent" type="button" value=" > " >
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
<script src="<?=base_url();?>modules/team_management/js/add_team_work.js"></script>
<script type="text/javascript">

$("#opt_code_thirt").on("change", function() {
					
					if($('#opt_code_thirt').val()=="1"){
					$("#fc-assigned").hide();
					$("#agent-assigned").show();
					
					
					}else{
					$("#fc-assigned").show();
					$("#agent-assigned").hide();
					
					}
				});

				$('#datepicker').datepicker({
            uiLibrary: 'bootstrap5'
        });
	
</script>