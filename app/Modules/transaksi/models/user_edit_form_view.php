<form role="form" class="form-horizontal" id="form_edit_user" name="form_edit_user">
	<input type="hidden" id="txt-user-id" name="txt-user-id" value="<?=$user_data["id_user"]?>">
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="txt-user-id"> NIP </label>

		<div class="col-sm-4">
			<input type="text" id="txt-user-id-temp" name="txt-user-id-temp" class="width-100 mandatory" disabled="disabled" value="<?=$user_data["id_user"]?>" required />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="txt-user-name"> NAMA </label>

		<div class="col-sm-9">
			<input type="text" id="txt-user-name" name="txt-user-name" class="col-xs-10 col-sm-8 mandatory" value="<?=$user_data["name_user"]?>" required />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> ID CWX </label>

		<div class="col-sm-9">
			<input type="text" id="txt-cwx" name="txt-cwx"  class="col-xs-10 col-sm-8 mandatory" value="<?=$user_data["agent_bucket"]?>" required />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> PASSWORD </label>

		<div class="col-sm-9">
			<input type="password" id="txt-password" name="txt-password" class="col-xs-10 col-sm-5" value="" />
			<span class="help-inline col-xs-12 col-sm-7">
				<div class="help-block col-xs-12 col-sm-reset inline blue"> Leave blank if not changed	 </div>
			</span>
		</div>
	</div>

	<div class="form-group"  id="div_bisnis_unit">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> BISNIS UNIT </label>
		
		<div class="col-sm-9">
			<?
        
        $attributes = 'class="col-xs-10 col-sm-4 " id="opt-product-group"';
				echo form_dropdown('opt-product-group', $bisnis_unit, $user_data["bisnis_unit"], $attributes);
			?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> LEVEL </label>
		
		<div class="col-sm-9">
			<?
				$attributes = 'class="col-xs-10 col-sm-5 mandatory" id="opt-user-level" required';
				echo form_dropdown('opt-user-level', $group_id_list, $user_data["user_level"], $attributes);
			?>
		</div>
	</div>

	<div class="form-group" id="id_group_fungsi">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> FUNGSI AGENT </label>
		
		<div class="col-sm-9">
			<?
				$options = array(
					'' => "--PILIH--",
          'ALLBUCKET' => 'ALL BUCKET',
          'FRONTEND'	=> 'FRONT END',
          'BACKEND'	=> 'BACK END',
          
        );
        
        $attributes = 'class="col-xs-10 col-sm-4 " id="opt-fungsi"';
				echo form_dropdown('opt-fungsi', $options, $user_data["agent_bucket"], $attributes);
			?>
		</div>
	</div>

	<div class="form-group" id="id_group_kcu">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> KCU GROUP</label>
		
		<div class="col-sm-9">
			<?
				$attributes = 'class="col-xs-10 col-sm-5 " id="opt-kcu"';
				echo form_dropdown('opt-kcu', $kcu_list, $user_data["kcu"], $attributes);
			?>
		</div>
	</div>
	
	<div class="form-group" id="id_kcu">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> KCU LIST </label>
		
		<div class="col-sm-9" id="kcu_cabang" name="kcu_cabang">
			<?=$kcu_cabang_list ?>
		</div>
	</div>

	<div class="form-group" id="id_group_area">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> AREA</label>
		
		<div class="col-sm-9">
			<?
				$attributes = 'class="col-xs-10 col-sm-5 " id="opt-area"';
				echo form_dropdown('opt-area', $area_list, $user_data["area"], $attributes);
			?>
		</div>
	</div>
	
	<div class="form-group" id="div_spv">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> SUPERVISOR </label>
		<input type="hidden" id="txt_spv" name="txt-spv" />
		<div class="col-sm-9">
			<?
				$attributes = 'id="opt-spv" class="col-xs-10 col-sm-5"';
				echo form_dropdown("opt-spv", $spv_id_list, $user_data["spv_id"], $attributes);
			?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="txt-user-id"> IMEI </label>

		<div class="col-sm-4">
			<input type="text" id="txt-imei" name="txt-imei" class="width-100" onkeypress="return isNumber(event)" required onkeypress="return noSpace(event)" value="<?=$user_data["imei"]?>" />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="txt-user-id"> ICCD </label>

		<div class="col-sm-4">
			<input type="text" id="txt-iccd" name="txt-iccd" class="width-100" required onkeypress="return noSpace(event)" value="<?=$user_data["iccd"]?>" />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="opt-active-flag"> FLAG AKTIF </label>
		
		<div class="col-sm-9">
			<?
				$options = array(
          '1' => 'ENABLE',
          '0'	=> 'DISABLE',        );
        
        $attributes = 'class="col-xs-10 col-sm-4 " id="opt-active-flag" required';
				echo form_dropdown('opt-active-flag', $options, $user_data["flag_active"], $attributes);
			?>
		</div>
	</div>
</form>

<script type="text/javascript">

	$("#txt-cwx").on('change', function(){
		var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/cek_idcwx/"
			$.ajax(
			{
				type		: "POST",
				url			: ci_controller,
				data		: {idcwx: $('#txt-cwx').val()},
				async		: false,
				dataType: "json",
				success	: function(msg){
					if(msg.success == true)
					{
						
					}else
					{
						showWarning("Failed: IDCWX sudah terdaftar", 1500);
					}
				},
				error: function()
				{
					showWarning("Failed: pengecekan IDCWX gagal, mohon ulangi lagi", 1500);
				}
				
			});
				
	});

	$("#opt-product-group").on('change', function(){
		
			var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/cek_assignment/"
			$.ajax(
			{
				type		: "POST",
				url			: ci_controller,
				data		: {bisnis_unit: $('#opt-product-group').val()},
				async		: false,
				dataType: "json",
				success	: function(msg){
					if(msg.success == true)
					{
						
						
						$('#opt-user-level option').remove();
						$('#opt-user-level').append($('<option></option>').val("").html("--PILIH--"));
						$.each(msg.user_group_list, function(val,text)
						{	
							$('#opt-user-level').append($('<option></option>').val(text.id).html(text.value));
						});


					}
					else
						{
						showWarning("Failed: " + ci_controller, 1500);
					}
				},
				error: function()
				{
					showWarning("Failed: " + ci_controller, 1500);
				}
			});
			
			/*
			var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/cek_area/"
			$.ajax(
			{
				type		: "POST",
				url			: ci_controller,
				data		: {bisnis_unit: $('#opt-product-group').val()},
				async		: false,
				dataType: "json",
				success	: function(msg){
					if(msg.success == true)
					{
						
						
						$('#opt-area option').remove();
						$('#opt-area').append($('<option></option>').val("").html("--PILIH--"));
						$.each(msg.user_area_list, function(val,text)
						{	
							$('#opt-area').append($('<option></option>').val(text.id).html(text.value));
						});


					}
					else
						{
						showWarning("Failed: " + ci_controller, 1500);
					}
				},
				error: function()
				{
					showWarning("Failed: " + ci_controller, 1500);
				}
			});
			*/
	});

	$("#opt-kcu").on('change', function(){
			var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/area_list/"
			$.ajax(
			{
				type		: "POST",
				url			: ci_controller,
				data		: {kcu: $('#opt-kcu').val()},
				async		: false,
				dataType: "json",
				success	: function(msg){
					if(msg.success == true)
					{
						$('#opt-area option').remove();
						$('#opt-area').append($('<option></option>').val("").html("--PILIH--"));
						$.each(msg.data, function(val,text)
						{	
							$('#opt-area').append($('<option></option>').val(text.id).html(text.value));
						});
						
						$('#kcu_cabang').html("");
						$('#kcu_cabang').append("<ul>");
						$('#kcu_cabang').append(msg.list_kcu);
						$('#kcu_cabang').append("</ul>");
						
					}
					else
						{
						showWarning("Failed: " + ci_controller, 1500);
					}
				},
				error: function()
				{
					showWarning("Failed: " + ci_controller, 1500);
				}
			});

	});
	
	$("#opt-area").on('change', function(){
				var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/cek_spv/"
				$.ajax(
				{
					type		: "POST",
					url			: ci_controller,
					data		: {area: $(this).val()},
					async		: false,
					dataType: "json",
					success	: function(msg){
						if(msg.success == true)
						{
							$('#opt-spv option').remove();
							$('#opt-spv').append($('<option></option>').val("").html("--PILIH--"));
							$.each(msg.user_spv_list, function(val,text)
							{	
								$('#opt-spv').append($('<option></option>').val(text.id).html(text.value));
							});
							
						}
						else
							{
							showWarning("Failed: " + ci_controller, 1500);
						}
					},
					error: function()
					{
						showWarning("Failed: " + ci_controller, 1500);
					}
				});
	});
	
	$("#opt-user-level").on('change', function(){
		
		if($("#opt-user-level").val()=='0100007'){
				$("#div_spv").show();	
				$('#opt-spv option').remove();
				var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/cek_area/"
				$.ajax(
				{
					type		: "POST",
					url			: ci_controller,
					data		: {bisnis_unit: $('#opt-product-group').val()},
					async		: false,
					dataType: "json",
					success	: function(msg){
						if(msg.success == true)
						{
							
							
							$('#opt-area option').remove();
							$('#opt-area').append($('<option></option>').val("").html("--PILIH--"));
							$.each(msg.user_area_list, function(val,text)
							{	
								$('#opt-area').append($('<option></option>').val(text.id).html(text.value));
							});


						}
						else
							{
							showWarning("Failed: " + ci_controller, 1500);
						}
					},
					error: function()
					{
						showWarning("Failed: " + ci_controller, 1500);
					}
				});
				
		}else{
				$("#div_spv").hide();
				$('#opt-spv option').remove();
				
		}
		
		
		
		
		/*
			var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_level/"
			$.ajax(
			{
				type		: "POST",
				url			: ci_controller,
				data		: {level: $('#opt-user-level').val()},
				async		: false,
				dataType: "json",
				success	: function(msg){
					if(msg.success == true)
					{
						$('#txt_level').val(msg.data);
						
					}
					else
					{
						//showWarning("Failed: " + ci_controller, 1500);
						$('#opt-area option').remove();
						$('#opt-spv option').remove();
						$('#div_spv').hide();
					}
				},
				error: function()
				{
					//showWarning("Failed: " + ci_controller, 1500);
					$('#opt-area option').remove();
					$('#opt-spv option').remove();
					$('#div_spv').hide();
				}
			});
		*/	

	});
	
	function first_load(){
		
		//$("#id_group_area").hide();
		$("#id_group_fungsi").hide();
		$("#id_group_kcu").hide();
		$("#id_kcu").hide();
		
		$("#div_bisnis_unit").hide();
		$("#opt-product-group").change();	
		
		$('#opt-user-level').val("<?=$user_data['user_level'] ?>");	
		$("#opt-user-level").change();	
		
		$('#opt-area').val("<?=$user_data['area'] ?>");
		$("#opt-area").change();
		
		$('#opt-spv').val("<?=$user_data['spv_id'] ?>");
		$("#opt-spv").change();	
									
	}

	first_load();
	
	
	
	

</script>
