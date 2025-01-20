

<div class="row" style="margin-top: -13px;">
<i class="bi bi-arrow-left-right text-center" ></i>
	<div class="col">
		<form role="form" class="needs-validation" id="form_edit_user" name="form_edit_user" novalidate>
			<input type="hidden" id="txt-user-id" name="txt-user-id" value="<?= $before["id_user"] ?>">

			<div class="card" style="background-color:#ededed">
				<div class="card-header text-end">
					BEFORE
				</div>
				<div class="card-body">
				<?php 
					if($NEED_TOKEN=="1"){
						if($before["bisnis_unit"] == "PREDICTIVEAUTODIALER"){
							echo '<span class="badge bg-sucess float-end">PREDICTIVE</span> ';
						}else{
							echo '<span class="badge bg-primary float-end">NON PREDICTIVE</span> ';
						}
					}
				?>
					<div>
						<div class="">
							<?php echo $before["flag_active"]=='1'?  '<span class="badge badge-sm text-bg-success">ACTIVE</span>' :  '<span class="badge badge-sm text-bg-secondary">INACTIVE</span>'; ?>
						</div>
					</div>
					<div>
						<center style="">
						<img src="<?=base_url()?>/assets/profilePicture/person-circle.svg" name="image" id="before_pic_profile_<?= $before["id_user"] ?>" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;"> 
						<center>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col col-sm-6">
					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">user id</label>
						<input type="text" id="txt-user-id-temp" name="id_user" class="form-control form-control-sm mandatory" required disabled value="<?= $before["id_user"] ?>" />
					</div>
					

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">user name</label>
						<input type="text" id="txt-user-name" name="name_user" class="form-control form-control-sm mandatory" required value="<?= $before["name_user"] ?>" />
					</div>

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Email</label>
						<input type="email" id="txt-email" name="email" class="form-control form-control-sm mandatory" required value="<?= $before["email"] ?>" />
					</div>

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Phone Number</label>
						<input type="text" id="txt-phone-number" name="handphone" class="form-control form-control-sm mandatory" required value="<?= $before["handphone"] ?>" />
					</div>

					<div class="mb-3 ">
						<label for="btnradio_typeCollection1" class="fs-6 text-capitalize">Type Collection</label><br>
						<div class="btn-group" role="group" id="radiotipecollectin" aria-label="Basic radio toggle button group" style="width: 100%;">
							<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection1" typeColl='TeleColl' autocomplete="off" <?php if($before["type_collection"]=='TeleColl'){echo "checked";} ?> >
							<label class="btn btn-outline-success" for="btnradio_typeCollection1">TELECOLL</label>

							<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection2" typeColl='FieldColl' autocomplete="off" <?php if($before["type_collection"]=='FieldColl'){echo "checked";} ?>>
							<label class="btn btn-outline-primary" for="btnradio_typeCollection2">FIELDCOLL</label>
						</div>
						<input type='hidden' name='opt-type_collection' id="" value='<?=$before["type_collection"]?>'/>
					
					</div>

					
				
					
				</div>
				<div class="col col-sm-6">
					<div class="mb-3 ">
						<label for="txt-group" class="fs-6 text-capitalize">Group</label>
						<input type="text" id="txt-group" name="user_level" class="form-control form-control-sm mandatory" required value="<?= $before['user_level']?>" />
					</div>
					<!-- <div class="mb-3 ">
						<label for="txt-group" class="fs-6 text-capitalize">Supervisor Name</label>
						<input type="text" id="txt-group" name="supervisor_name" class="form-control form-control-sm mandatory" required value="<?= $before['supervisor_name'] ?>" />
					</div> -->
					
					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Join Date</label>
						<div class="input-group input-group-sm has-validation  date" id="datepicker">
							<span class="input-group-text bg-light d-block">
									<i class="bi bi-calendar4-week"></i>
							</span>
							<input type="text" class="form-control form-control-sm mandatory" id="join_date" name="join_date" value="<?php if ($before["join_date"] != '0000-00-00') { echo $before["join_date"]; } else {echo date('Y-m-d');}  ?>"/>
						</div>

					</div>

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Password</label>
						<div class="input-group input-group-sm" style="cursor:pointer">
							<span class="input-group-text bg-light" id="showPass" onClick="showPass()"><i class="bi bi-eye"></i></span>
							<input type="password" name="txt-password" class="form-control form-control-sm " id="txt-password">
							<div class="invalid-feedback">Please enter your password.</div>
						</div>
					</div>

					<div class="mb-6 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Retype Password</label>
						
						<div class="input-group input-group-sm " style="cursor:pointer;margin-bottom: -7px;">
							<span class="input-group-text bg-light" id="showPass2" onClick="showPass2()"><i class="bi bi-eye"></i></span>
							<input type="password" name="txt-password2" class="form-control form-control-sm " id="txt-password2" disabled>
							<div class="invalid-feedback">unmatch password.</div>
						</div>
						<small class="text-muted" style="font-size:11px"> Leave blank if not changed</small>
					</div>
					<input style="display:none" type="text" name="PASSWORD_VALID"  class="form-control"  id="PASSWORD_VALID" value="true" required/>

					<div class="mb-3 " style="display:none">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Upload Picture</label>
						<input type="file" id="profile_image" name="profile_image" class="form-control form-control-sm " accept="image/png, image/jpeg,  image/jpg"/>
					</div>
					
					<div class="mb-3 " style="display:none">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
						<?php
						$options = array(
							'1' => 'ENABLE',
							'0'	=> 'DISABLE',
						);
						$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
						echo form_dropdown('opt-active-flag', $options, $before["flag_active"], $attributes);
						?>
					</div>
					
					<div class="mb-3" id="div_token">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Token</label>
						<div class="input-group has-validation" style="cursor:pointer">
							<input type="text" name="token" class="form-control form-control-sm " id="txt-token" required value="<?=$before['token']?>">
						</div>
						<small >
							<div class="text-danger" id="is_valid" style="display:none">
								Invalid
							</div>
						</small>
						<input type="hidden" name="bisnis_unit" id="bisnis_unit" value="<?=$before['bisnis_unit']?>" />
						<input style="display:none" type="text" name="TOKEN_VALID"  class="form-control"  id="TOKEN_VALID" value="" required/>
					</div>
					
				</div>
			</div>
		</form>
	</div>
	<div class="col">
		
		<form role="form" class="needs-validation" id="form_edit_user" name="form_edit_user" novalidate>
			<input type="hidden" id="txt-user-id" name="txt-user-id" value="<?= $after["id_user"] ?>">
			<div class="card" style="background-color:#ededed">
				<div class="card-header text-start">
					<b>AFTER</b>
				</div>
				<div class="card-body">
				<?php 
					if($NEED_TOKEN=="1"){
						if($after["bisnis_unit"] == "PREDICTIVEAUTODIALER"){
							echo '<span class="badge bg-sucess float-end">PREDICTIVE</span> ';
						}else{
							echo '<span class="badge bg-primary float-end">NON PREDICTIVE</span> ';
						}
					}
				?>
					<div>
						<div>
							<?php echo $before["flag_active"]=='1'?  '<span class="badge badge-sm text-bg-success">ACTIVE</span>' :  '<span class="badge badge-sm text-bg-secondary">INACTIVE</span>'; ?>
						</div>
					</div>
					<div>
						<center style="">
						<img src="<?=base_url()?>/assets/profilePicture/person-circle.svg" name="image" id="after_pic_profile_<?= $after["id_user"] ?>" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;"> 
						<center>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col col-sm-6">
					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">user id</label>
						<input type="text" id="txt-user-id-temp" name="id_user" class="form-control form-control-sm mandatory" required disabled value="<?= $after["id_user"] ?>" />
					</div>
					

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">user name</label>
						<input type="text" id="txt-user-name" name="name_user" class="form-control form-control-sm mandatory" required value="<?= $after["name_user"] ?>" />
					</div>

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Email</label>
						<input type="email" id="txt-email" name="email" class="form-control form-control-sm mandatory" required value="<?= $after["email"] ?>" />
					</div>

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Phone Number</label>
						<input type="text" id="txt-phone-number" name="handphone" class="form-control form-control-sm mandatory" required value="<?= $after["handphone"] ?>" />
					</div>

					<div class="mb-3 ">
						<label for="btnradio_typeCollection1" class="fs-6 text-capitalize">Type Collection</label><br>
						<div class="btn-group" role="group" id="radiotipecollectin" aria-label="Basic radio toggle button group" style="width: 100%;">
							<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection1" typeColl='TeleColl' autocomplete="off" <?php if($after["type_collection"]=='TeleColl'){echo "checked";} ?> >
							<label class="btn btn-outline-success" for="btnradio_typeCollection1">TELECOLL</label>

							<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection2" typeColl='FieldColl' autocomplete="off" <?php if($after["type_collection"]=='FieldColl'){echo "checked";} ?>>
							<label class="btn btn-outline-primary" for="btnradio_typeCollection2">FIELDCOLL</label>
						</div>
						<input type='hidden' name='opt-type_collection' id="" value='<?=$after["type_collection"]?>'/>
					
					</div>

					
				
					
				</div>
				<div class="col col-sm-6">
					<div class="mb-3 ">
						<label for="txt-group" class="fs-6 text-capitalize">Group</label>
						<input type="text" id="txt-group" name="user_level" class="form-control form-control-sm mandatory" required value="<?= $after['user_level']?>" />
					</div>
					<!-- <div class="mb-3 ">
						<label for="txt-group" class="fs-6 text-capitalize">Supervisor Name</label>
						<input type="text" id="txt-group" name="supervisor_name" class="form-control form-control-sm mandatory" required value="<?= $after['supervisor_name'] ?>" />
					</div> -->
					
					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Join Date</label>
						<div class="input-group input-group-sm has-validation  date" id="datepicker">
							<span class="input-group-text bg-light d-block">
									<i class="bi bi-calendar4-week"></i>
							</span>
							<input type="text" class="form-control form-control-sm mandatory" id="join_date" name="join_date" value="<?php if ($after["join_date"] != '0000-00-00') { echo $before["join_date"]; } else {echo date('Y-m-d');}  ?>"/>
						</div>

					</div>

					<div class="mb-3 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Password</label>
						<div class="input-group input-group-sm" style="cursor:pointer">
							<span class="input-group-text bg-light" id="showPass" onClick="showPass()"><i class="bi bi-eye"></i></span>
							<input type="password" name="txt-password" class="form-control form-control-sm " id="txt-password">
							<div class="invalid-feedback">Please enter your password.</div>
						</div>
					</div>

					<div class="mb-6 ">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Retype Password</label>
						
						<div class="input-group input-group-sm " style="cursor:pointer;margin-bottom: -7px;">
							<span class="input-group-text bg-light" id="showPass2" onClick="showPass2()"><i class="bi bi-eye"></i></span>
							<input type="password" name="txt-password2" class="form-control form-control-sm " id="txt-password2" disabled>
							<div class="invalid-feedback">unmatch password.</div>
						</div>
						<small class="text-muted" style="font-size:11px"> Leave blank if not changed</small>
					</div>
					<input style="display:none" type="text" name="PASSWORD_VALID"  class="form-control"  id="PASSWORD_VALID" value="true" required/>

					<div class="mb-3 " style="display:none">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Upload Picture</label>
						<input type="file" id="profile_image" name="profile_image" class="form-control form-control-sm " accept="image/png, image/jpeg,  image/jpg"/>
					</div>
					
					<div class="mb-3 " style="display:none">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
						<?php
						$options = array(
							'1' => 'ENABLE',
							'0'	=> 'DISABLE',
						);
						$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
						echo form_dropdown('opt-active-flag', $options, $after["flag_active"], $attributes);
						?>
					</div>
					
					<div class="mb-3" id="div_token2">
						<label for="form-field-select-2" class="fs-6 text-capitalize">Token</label>
						<div class="input-group has-validation" style="cursor:pointer">
							<input type="text" name="txt-token" class="form-control form-control-sm " id="token" required value="<?=$after['token']?>">
						</div>
						<small >
							<div class="text-danger" id="is_valid" style="display:none">
								Invalid
							</div>
						</small>
						<input type="hidden" name="bisnis_unit" id="bisnis_unit" value="<?=$after['bisnis_unit']?>" />
						<input style="display:none" type="text" name="TOKEN_VALID"  class="form-control"  id="TOKEN_VALID" value="" required/>
					</div>
					
				</div>
			</div>
		</form>
	</div>
</div>


<script type="text/javascript">
	var id_user_approval = "<?= $before["id_user"] ?>";
	var user_group_selected = "<?= $before["user_level"] ?>";
	var before_image_profile = "<?= $before["image"] ?>";
	var after_image_profile = "<?= $after["image"] ?>";
	var diff = JSON.parse('<?= $diff?>');
	var ISTOKEN ="<?= $NEED_TOKEN?>";
	

	$(document).ready(function() {
		
		if(ISTOKEN=='1'){ //butuh token , sehingga di set false supaya di lakukan pengecekan
			TOKEN_VALID = false; 
			$("#TOKEN_VALID").val('');
			$("#div_token").show();
			$("#div_token2").show();
		}else{
			$("#div_token").hide();
			$("#div_token2").hide();
			TOKEN_VALID = true;
			$("#TOKEN_VALID").val('true').hide();
			$("#txt-token").removeAttr('required');
		}

	
		if(after_image_profile!=''){
			$('#after_pic_profile_'+id_user_approval).attr('src', GLOBAL_MAIN_VARS["SITE_URL"]+'/uploads/user/'+after_image_profile);
		}
		if(before_image_profile!=''){
			$('#before_pic_profile_'+id_user_approval).attr('src', GLOBAL_MAIN_VARS["SITE_URL"]+'/uploads/user/'+before_image_profile);
		}

		$("input").attr('disabled',true);

		$.each(diff,(i,val)=>{
			$.each($("[name="+i),(ii,vall)=>{
				$(vall).addClass('border border-warning border-2');
			})
		})
		
	});



</script>