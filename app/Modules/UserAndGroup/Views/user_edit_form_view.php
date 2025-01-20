


<form role="form" class="needs-validation" id="form_edit_user" name="form_edit_user" novalidate>
	<input type="hidden" id="txt-user-id" name="txt-user-id" value="<?= $user_data["id_user"] ?>">

	<div class="card" style="background-color:#ededed">
		<div class="card-body">

			<div>
				<div class="form-check form-switch">
					<label class="form-check-label" for="flexSwitchCheckChecked">is Active</label>
					<input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo $user_data["flag_active"]=='1'?  'checked' :  ''; ?>>
				</div>
			</div>
			<div>
				<center style="">
				<img src="<?=base_url()?>/assets/profilePicture/person-circle.svg" id="pic_profile" onClick="selectImage()" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;cursor:pointer"> 
				<small><a  href="#" class="text-decoration-none" onClick="removeImage()">remove</a></small>
				<center>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-sm-6">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">user id</label>
				<input type="text" id="txt-user-id-temp" name="txt-user-id-temp" class="form-control form-control-sm mandatory" required disabled value="<?= $user_data["id_user"] ?>" />
			</div>
			

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">user name</label>
				<input type="text" id="txt-user-name" name="txt-user-name" class="form-control form-control-sm mandatory" required value="<?= $user_data["name_user"] ?>" />
			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Email</label>
				<input type="email" id="txt-email" name="txt-email" class="form-control form-control-sm" value="<?= $user_data["email"] ?>" />
				<input type="text" id="txt-email-validate" name="txt-email-validate" class="form-control form-control-sm mandatory" style="display:none" required/>
			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Phone Number</label>
				<input type="text" id="txt-phone-number" name="txt-phone-number" class="form-control form-control-sm mandatory" required value="<?= $user_data["handphone"] ?>" />
			</div>

			<div class="mb-3 ">
				<label for="btnradio_typeCollection1" class="fs-6 text-capitalize">Type Collection</label><br>
				<div class="btn-group" role="group" id="radiotipecollectin" aria-label="Basic radio toggle button group" style="width: 100%;">
					<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection1" typeColl='TELECOLL' autocomplete="off" <?php if($user_data["type_collection"]=='TELECOLL'){echo "checked";} ?> >
					<label class="btn btn-outline-success" for="btnradio_typeCollection1">TELECOLL</label>

					<input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection2" typeColl='FIELDCOLL' autocomplete="off" <?php if($user_data["type_collection"]=='FIELDCOLL'){echo "checked";} ?>>
					<label class="btn btn-outline-primary" for="btnradio_typeCollection2">FIELDCOLL</label>
				</div>
				<input type='hidden' name='opt-type_collection' id="opt-type_collection" value='<?=$user_data["type_collection"]?>'/>
			
			</div>

			
			
			
		</div>
		<div class="col col-sm-6">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Group</label>
				<?
				$opt = array('' => '--PILIH--');
				$attributes = 'class="form-control form-control-sm mandatory" id="opt-user-level" data-placeholder ="[select]" required';
				echo form_dropdown('opt-user-level', $opt, $user_data["user_level"], $attributes);
				?>
			</div>
			<!-- <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Supervisor Name</label>
				<?php
				$attributes = 'class="form-control form-control-sm mandatory" id="txt-supervisor-name" data-placeholder ="[select]" required';
				echo form_dropdown('txt-supervisor-name', $spv_list, $user_data["supervisor_name"], $attributes);
				?>
			</div> -->
			
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Join Date</label>
				<div class="input-group input-group-sm has-validation  date" id="datepicker">
					<span class="input-group-text bg-light d-block">
							<i class="bi bi-calendar4-week"></i>
					</span>
					<input type="text" class="form-control form-control-sm mandatory" id="join_date" name="join_date" value="<?php if ($user_data["join_date"] != '0000-00-00') { echo $user_data["join_date"]; } else {echo date('Y-m-d');}  ?>"/>
				</div>

			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Password</label>
				<!-- <input type="password" id="txt-password" name="txt-password" class="form-control" /> -->
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
				echo form_dropdown('opt-active-flag', $options, $user_data["flag_active"], $attributes);
				?>
			</div>
			
		
			
		</div>
	</div>
	
	

	
</form>

<script type="text/javascript">
	var group_id_telecoll_list = JSON.parse('<?= json_encode($group_id_telecoll_list); ?>');
	var group_id_fieldcoll_list = JSON.parse('<?= json_encode($group_id_fieldcoll_list); ?>');
	var user_group_selected = "<?= $user_data["user_level"] ?>";
	var image_profile = "<?= $user_data["image"] ?>";
	
	


	function showPass() {
		let yourPassword = $("#txt-password").attr('type');

		if (yourPassword == 'password') {
			$("#showPass").html('<i class="bi bi-eye-slash"></i>');
			$("#txt-password").attr('type', 'text');
		} else {
			$("#showPass").html('<i class="bi bi-eye"></i>');
			$("#txt-password").attr('type', 'password');
		}
	}

	function showPass2() {
		let yourPassword = $("#txt-password2").attr('type');

		if (yourPassword == 'password') {
			$("#showPass2").html('<i class="bi bi-eye-slash"></i>');
			$("#txt-password2").attr('type', 'text');
		} else {
			$("#showPass2").html('<i class="bi bi-eye"></i>');
			$("#txt-password2").attr('type', 'password');
		}
	}


	$("[name=btnradio_typeCollection]").change(function(e) {
	

		let tipe = $(this).attr('typeColl');
		$("#opt-type_collection").val(tipe);
		
		var opt_html = '';
		$("#opt-user-level").html('');
		if (tipe == 'TELECOLL') {
			$.each(group_id_telecoll_list, function(i, val) {
				// if(i == '' || i == 'COLLECTOR' || i == 'SUPERADMIN' || i == 'SUPERVISOR' || i == 'TEAM_LEADER' ){
				opt_html += "<option value='" + i + "'>" + val + " </option> ";
				// }
			});
		} else if (tipe == 'FIELDCOLL') {
			$.each(group_id_fieldcoll_list, function(i, val) {
				opt_html += "<option value='" + i + "'>" + val + " </option> ";
			});
		}
		$("#opt-user-level").html(opt_html);
	});

	var move_list_items = function(sourceid, destinationid) {
		$("#" + sourceid + "	option:selected").appendTo("#" + destinationid);
	}

	//this will move all selected items from source list to destination list
	var move_list_items_all = function(sourceid, destinationid) {
		$("#" + sourceid + " option").appendTo("#" + destinationid);
	}

	$(document).ready(function() {
		
		
		if(image_profile!=''){
			$('#pic_profile').attr('src', GLOBAL_MAIN_VARS["SITE_URL"]+'/uploads/user/'+image_profile);
		}
		
		$("#txt-email").trigger('keyup');
		
		
		$('#join_date').daterangepicker({
			"singleDatePicker": true, 
			"autoApply": true,
			// "startDate": "09/21/2023",
			"locale": {
				"format": "YYYY-MM-DD",
			},
		}, function(start, end, label) {
			
		});

		let tipe = $("#opt-type_collection").val();
		var opt_html='';
		if (tipe == 'TELECOLL') {
			$.each(group_id_telecoll_list, function(i, val) {
				// if(i == '' || i == 'COLLECTOR' || i == 'SUPERADMIN' || i == 'SUPERVISOR' || i == 'TEAM_LEADER' ){
				opt_html += "<option value='" + i + "'>" + val + " </option> ";
				// }
			});
		} else if (tipe == 'FIELDCOLL') {
			$.each(group_id_fieldcoll_list, function(i, val) {
				opt_html += "<option value='" + i + "'>" + val + " </option> ";
			});
		}
		$("#opt-user-level").html(opt_html).val(user_group_selected).change();
	});



	

	function isActive(elm){
		if($(elm)[0].checked){
			$("#opt-active-flag").val('1').change();
		}else{
			$("#opt-active-flag").val('0').change();
		}
	}

	function selectImage(){
		$("#profile_image").click();
	}
	$("#profile_image").change(function(){
		const file = this.files[0];
        // console.log(file);
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            // console.log(event.target.result);
            $('#pic_profile').attr('src', event.target.result);
          }
		  $("#pic_profile").show();
          reader.readAsDataURL(file);
        }
	})

	function removeImage(){
		$('#pic_profile').attr('src', GLOBAL_MAIN_VARS["SITE_URL"]+'/assets/profilePicture/person-circle.svg');
		$("#profile_image").val('');
	}

	$("#txt-phone-number").keyup(function() {
		var curchr = this.value.replaceAll(' ','').length;
		var curval = $(this).val().replace(/[^0-9]/g, '');
		
		$("#txt-phone-number").val(curval);

	});
	$("#txt-password").keyup(function() {
		var curchr = this.value.replaceAll(' ','').length;
		if(curchr>0){
			$("#txt-password2").attr('disabled',false);
			$("#PASSWORD_VALID").val(''); //dikosongkan , di isi ketika password valid
		}else{
			$("#txt-password2").attr('disabled',true);
		}
	});
	$("#txt-password2").keyup(function() {
		var repass = $(this).val();
		var pass = $("#txt-password").val();
		if(repass!=pass){
			$("#txt-password2").removeClass('is-invalid');
			$("#txt-password2").addClass('is-invalid');
			$("#PASSWORD_VALID").val(''); //dikosongkan karena password tidak valid
		}else{
			$("#txt-password2").removeClass('is-invalid');
			$("#PASSWORD_VALID").val('true'); //di isi karena password valid
			
		}
	});

	$("#txt-email").keyup(function() {
		var email = $(this).val();
		
		let checkcom = validateEmailCom(email);
		let checkcoid = validateEmailCoId(email);
		if(checkcom || checkcoid){
			$('#txt-email-validate').val('VALID');

			$("#txt-email").removeClass('is-invalid');
			$("#txt-email").removeClass('is-valid');
			$('#txt-email').addClass('is-valid');

		}else{
			$('#txt-email-validate').val(''); //INVALID

			$("#txt-email").removeClass('is-invalid');
			$("#txt-email").removeClass('is-valid');
			$('#txt-email').addClass('is-invalid');
		}
	});

	function validateEmail(email) {
		// Melakukan validasi format email
		const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
		return regex.test(email);
	}

	// Validasi email .com
	function validateEmailCom(email) {
		// Melakukan validasi format email
		const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/;
		return regex.test(email);
	}

	// Validasi email .co.id
	function validateEmailCoId(email) {
		// Melakukan validasi format email
		const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.co\.id$/;
		return regex.test(email);
	}

</script>