<form role="form" class="needs-validation" id="form_edit_user" name="form_edit_user" novalidate>
	<input type="hidden" id="txt-user-id" name="txt-user-id" value="<?= $user_data["id_user"] ?>">

	<div class="card" style="background-color:#ededed">
		<div class="card-body">
			<?php 
			if($NEED_TOKEN=="1"){
				if($user_data["bisnis_unit"] == "PREDICTIVEAUTODIALER"){
					echo '<span class="badge bg-sucess float-end">PREDICTIVE</span> ';
				}else{
					echo '<span class="badge bg-primary float-end">NON PREDICTIVE</span> ';
				}
			}
		?>
			<div>
				<div class="form-check form-switch">
					<label class="form-check-label" for="flexSwitchCheckChecked">is Active</label>
					<input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
						id="flexSwitchCheckChecked" <?php echo $user_data["flag_active"]=='1'?  'checked' :  ''; ?>>
				</div>
			</div>
			<div>
				<center style="">
					<img src="<?=base_url()?>/assets/profilePicture/person-circle.svg" id="pic_profile"
						class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;">
					<center>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-sm-6">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">user id</label>
				<input type="text" id="txt-user-id-temp" name="txt-user-id-temp"
					class="form-control form-control-sm mandatory" required disabled
					value="<?= $user_data["id_user"] ?>" />
			</div>


			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">user name</label>
				<input type="text" id="txt-user-name" name="txt-user-name"
					class="form-control form-control-sm mandatory" required value="<?= $user_data["name_user"] ?>" />
			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Email</label>
				<input type="email" id="txt-email" name="txt-email" class="form-control form-control-sm mandatory"
					required value="<?= $user_data["email"] ?>" />
			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Phone Number</label>
				<input type="text" id="txt-phone-number" name="txt-phone-number"
					class="form-control form-control-sm mandatory" required value="<?= $user_data["handphone"] ?>" />
			</div>

			<div class="mb-3 ">
				<label for="btnradio_typeCollection1" class="fs-6 text-capitalize">Type Collection</label><br>
				<div class="btn-group" role="group" id="radiotipecollectin" aria-label="Basic radio toggle button group"
					style="width: 100%;">
					<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection"
						id="btnradio_typeCollection1" typeColl='TeleColl' autocomplete="off"
						<?php if($user_data["type_collection"]=='TeleColl'){echo "checked";} ?>>
					<label class="btn btn-outline-success" for="btnradio_typeCollection1">TELECOLL</label>

					<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection"
						id="btnradio_typeCollection2" typeColl='FieldColl' autocomplete="off"
						<?php if($user_data["type_collection"]=='FieldColl'){echo "checked";} ?>>
					<label class="btn btn-outline-primary" for="btnradio_typeCollection2">FIELDCOLL</label>
				</div>
				<input type='hidden' name='opt-type_collection' id="" value='<?=$user_data["type_collection"]?>' />

			</div>



		</div>
		<div class="col col-sm-6">
			<!-- <div class="mb-3 ">
				<label for="txt-group" class="fs-6 text-capitalize">Supervisor Name</label>
				<input type="text" id="txt-group" name="txt-phone-number" class="form-control form-control-sm mandatory" required value="<?= $supervisor_name ?>" />
			</div> -->
			<div class="mb-3 ">
				<label for="txt-group" class="fs-6 text-capitalize">Group</label>
				<input type="text" id="txt-group" name="txt-phone-number" class="form-control form-control-sm mandatory"
					required value="<?= $group_name?>" />
			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Join Date</label>
				<div class="input-group input-group-sm has-validation  date" id="datepicker">
					<span class="input-group-text bg-light d-block">
						<i class="bi bi-calendar4-week"></i>
					</span>
					<input type="text" class="form-control form-control-sm mandatory" id="join_date" name="join_date"
						value="<?php if ($user_data["join_date"] != '0000-00-00') { echo $user_data["join_date"]; } else {echo date('Y-m-d');}  ?>" />
				</div>

			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Password</label>
				<div class="input-group input-group-sm" style="cursor:pointer">
					<span class="input-group-text bg-light" id="showPass" onClick="showPass()"><i
							class="bi bi-eye"></i></span>
					<input type="password" name="txt-password" class="form-control form-control-sm " id="txt-password">
					<div class="invalid-feedback">Please enter your password.</div>
				</div>
			</div>

			<div class="mb-6 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Retype Password</label>

				<div class="input-group input-group-sm " style="cursor:pointer;margin-bottom: -7px;">
					<span class="input-group-text bg-light" id="showPass2" onClick="showPass2()"><i
							class="bi bi-eye"></i></span>
					<input type="password" name="txt-password2" class="form-control form-control-sm " id="txt-password2"
						disabled>
					<div class="invalid-feedback">unmatch password.</div>
				</div>
				<small class="text-muted" style="font-size:11px"> Leave blank if not changed</small>
			</div>
			<input style="display:none" type="text" name="PASSWORD_VALID" class="form-control" id="PASSWORD_VALID"
				value="true" required />

			<div class="mb-3 " style="display:none">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Upload Picture</label>
				<input type="file" id="profile_image" name="profile_image" class="form-control form-control-sm "
					accept="image/png, image/jpeg,  image/jpg" />
			</div>

			<div class="mb-3 " style="display:none">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
				<?php
				$options = array(
					'1' => 'ENABLE',
					'0'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, $user_data["flag_active"], $attributes);
				?>
			</div>

			<div class="mb-3" id="div_token">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Token</label>
				<div class="input-group has-validation" style="cursor:pointer">
					<input type="text" name="txt-token" class="form-control form-control-sm " id="txt-token" required
						value="<?=$user_data['token']?>">
				</div>
				<small>
					<div class="text-danger" id="is_valid" style="display:none">
						Invalid
					</div>
				</small>
				<input type="hidden" name="bisnis_unit" id="bisnis_unit" value="<?=$user_data['bisnis_unit']?>" />
				<input style="display:none" type="text" name="TOKEN_VALID" class="form-control" id="TOKEN_VALID"
					value="" required />
			</div>

		</div>
	</div>




</form>

<script type="text/javascript">
	var user_group_selected = "<?= $user_data["user_level"] ?>";
	var image_profile = "<?= $user_data["image"] ?>";
	var ISTOKEN = "<?= $NEED_TOKEN?>";


	$(document).ready(function () {

		if (ISTOKEN == '1') { //butuh token , sehingga di set false supaya di lakukan pengecekan
			TOKEN_VALID = false;
			$("#TOKEN_VALID").val('');
			$("#div_token").show();
		} else {
			$("#div_token").hide();
			TOKEN_VALID = true;
			$("#TOKEN_VALID").val('true').hide();
			$("#txt-token").removeAttr('required');
		}


		if (image_profile != '') {
			$('#pic_profile').attr('src', GLOBAL_MAIN_VARS["SITE_URL"] + '/uploads/user/' + image_profile);
		}

		$("input").attr('disabled', true);

	});
</script>