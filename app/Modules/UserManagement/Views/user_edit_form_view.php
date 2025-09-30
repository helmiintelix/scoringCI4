<form role="form" class="needs-validation" id="form_edit_user" name="form_edit_user" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<input type="hidden" id="txt-user-id" name="txt-user-id" value="<?= $user_data["id"] ?>">
	<div>
		<div class="form-check form-switch">
			<label class="form-check-label" for="flexSwitchCheckChecked">is Active</label>
			<input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo $user_data["is_active"]=='1'?  'checked' :  ''; ?>>
		</div>
	</div>
	<div class="card" style="background-color:#ededed;display:none">
		<div class="card-body">
			<div>
				<center style="">
					<img src="<?=base_url()?>/assets/profilePicture/person-circle.svg" id="pic_profile" onClick="selectImage()" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;cursor:pointer"> 
					<small><a  href="#" class="text-decoration-none" onClick="removeImage()">remove</a></small>
				</center>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-sm-6">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">user id</label>
				<input type="text" id="txt-user-id-temp" name="txt-user-id-temp" class="form-control form-control-sm mandatory" disabled value="<?= $user_data["id"]?>" required/>
			</div>
			

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">user name</label>
				<input type="text" id="txt-user-name" name="txt-user-name" class="form-control form-control-sm mandatory" required value="<?= $user_data["name"] ?>"/>
			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Email</label>
				<input type="email" id="txt-email" name="txt-email" class="form-control form-control-sm mandatory" value="<?= $user_data["email"] ?>"/>
				<input type="text" id="txt-email-validate" name="txt-email-validate" class="form-control form-control-sm mandatory" value="<?= $user_data["email"] ?>" style="display:none" required/>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Group</label>
				<?
				$opt = array('' => '--PILIH--');
				$attributes = 'class="form-control form-control-sm mandatory" id="opt-user-level" data-placeholder ="[select]" required';
				echo form_dropdown('opt-user-level', $opt,$user_data["group_id"], $attributes);
				?>
			</div>
		</div>
		<div class="col col-sm-6">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">User LDAP</label>
				<div class="input-group input-group-sm" style="cursor:pointer">
					<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
						<input type="radio" ldapFlag='0' class="btn-check" value='0' name="btnLdap" id="btnLdap2" autocomplete="off" >
						<label class="btn btn-sm btn-outline-primary" for="btnLdap2">NO</label>

						<input type="radio" ldapFlag='1' class="btn-check" value='1' name="btnLdap" id="btnLdap1" autocomplete="off" >
						<label class="btn btn-sm btn-outline-primary" for="btnLdap1">YES</label>

					</div>
				</div>
			</div>

			<div class="mb-3 inputPassword ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Password</label>
				<!-- <input type="password" id="txt-password" name="txt-password" class="form-control" /> -->
				<div class="input-group input-group-sm" style="cursor:pointer">
					<span class="input-group-text bg-light" id="showPass" onClick="showPass()"><i class="bi bi-eye"></i></span>
					<input type="password" name="txt-password" class="form-control form-control-sm " id="txt-password">
					<div class="invalid-feedback">Please enter your password.</div>
				</div>
			</div>

			<div class="mb-6 inputPassword">
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
				echo form_dropdown('opt-active-flag', $options, '1', $attributes);
				?>
			</div>
			
		
			
		</div>
	</div>
	
	

	
</form>

<script type="text/javascript">
	var group_id_telecoll_list = {};
	var group_list = JSON.parse('<?= json_encode($group_list); ?>');
	var image_profile = "";
	var ldapFlag = '<?=$user_data["ldap"]?>';
	
	


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



	var move_list_items = function(sourceid, destinationid) {
		$("#" + sourceid + "	option:selected").appendTo("#" + destinationid);
	}

	//this will move all selected items from source list to destination list
	var move_list_items_all = function(sourceid, destinationid) {
		$("#" + sourceid + " option").appendTo("#" + destinationid);
	}

	$(document).ready(function() {
		$("[name=btnLdap]").change();
		$('input[name="btnLdap"][value="'+ldapFlag+'"]').prop('checked', true).change();
		let opt_html='';
			$.each(group_list, function(i, val) {
				opt_html += "<option value='" + i + "'>" + val + " </option> ";
			});
			$("#opt-user-level").html(opt_html);
	

		if(image_profile!=''){
			$('#pic_profile').attr('src', GLOBAL_MAIN_VARS["SITE_URL"]+'/uploads/user/'+image_profile);
		}
		
		

		$('#join_date').daterangepicker({
			"singleDatePicker": true, 
			"autoApply": true,
			// "startDate": "09/21/2023",
			"locale": {
        		"format": "YYYY-MM-DD",
			},
		}, function(start, end, label) {
		console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
		});

		// setTimeout(() => {
		// 	$("#txt-password ,#txt-password2").val(''); 
		// 	console.log('clear');
			
		// }, 1000);
	
	});


	$("#opt-product-group").change();



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
		emailChecking();
	});


	function emailChecking(elm){
		var email = $("#txt-email").val();
		
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
	}

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

	$("[name=btnLdap]").change(function(e) {
	
		let tipe = $(this).attr('ldapFlag');

		if(tipe=='0'){
			$('.inputPassword').show();
		}else{
			$('.inputPassword').hide();
			$('#txt-password,#txt-password2').val('');
		}
		
	});


	

</script>