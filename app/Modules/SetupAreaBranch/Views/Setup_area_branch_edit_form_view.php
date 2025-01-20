


<form role="form" class="needs-validation" id="form_edit" name="form_edit" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" id="txt-id" name="txt-id" value="<?=$id?>">
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch ID</label>
				<input type="text" id="txt-branch-id" name="txt-branch-id" class="form-control form-control-sm mandatory" value="<?=$branch_data["area_id"]?>" required/>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch Name</label>
				<input type="text" id="txt-branch-name" name="txt-branch-name" class="form-control form-control-sm mandatory" value="<?=$branch_data["area_name"]?>" required />
			</div>
            
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch Provinsi</label>
				<?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-area_branch-province" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-area_branch-province', $province,'', $attributes);
				?>
			</div>
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch Kota/Kabupaten</label>
				<?php
                    $options = array(
                        '' => 'SELECT DATA');
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-area_branch-city" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-area_branch-city', $city, "", $attributes);
				?>
			</div>
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch Kecamatan</label>
				<?php
                    $options = array(
                        '' => 'SELECT DATA');
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-area_branch-kecamatan" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-area_branch-kecamatan', $kecamatan, "", $attributes);
				?>
			</div>
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch Keluarahan</label>
				<?php
                    $options = array(
                        '' => 'SELECT DATA');
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-area_branch-kelurahan" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-area_branch-kelurahan', $kelurahan, "", $attributes);
				?>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">List Cabang</label>
				<?php
                    $attributes = 'class="form-control form-control-sm mandatory " multiple id="opt-area_branch-branch_list[]" data-placeholder ="[select]" required';
                    echo form_dropdown("opt-area_branch-branch_list[]", $branch_list, $data_branch_list, $attributes);
				?>
			</div>
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch Address</label>
				<input type="text" id="txt-branch-address" name="txt-branch-address" class="form-control form-control-sm " value="<?=$branch_data["area_address"]?>"/>
			</div>
            
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch No.Telp</label>
				<input type="text" id="txt-branch-telp" name="txt-branch-telp" class="form-control form-control-sm " value="<?=$branch_data["area_no_telp"]?>"/>
			</div>
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Area Branch Manager Name</label>
				<?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-area_branch-manager" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-area_branch-manager', $manager,'', $attributes);
				?>
			</div>
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
				<div class="form-check form-switch">
					<label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
					<input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
				</div>
			</div>


            <div class="mb-3 " style="display:none">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
				<?php
				$options = array(
					'1' => 'ENABLE',
					'0'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, $branch_data["is_active"], $attributes);
				?>
			</div>
		</div>
	</div>
	
	

	
</form>

<script type="text/javascript">
	function isActive(elm){
		if($(elm)[0].checked){
			$("#opt-active-flag").val('1').change();
			//$("label[for='flexSwitchCheckChecked']").text('Active');
		}else{
			$("#opt-active-flag").val('0').change();
			//$("label[for='flexSwitchCheckChecked']").text('Not Active');
		}
	}

    $('#opt-area_branch-province').change(function(){
        console.log("halo");
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_area_branch/setup_area_branch/getDescent',
			data: { "value": $("#opt-area_branch-province").val(),
                "<?= csrf_token() ?>" :  "<?= csrf_hash() ?>"},
            dataType: 'json',
			type: "get",
			success: function(data){
				$("#opt-area_branch-city").html(data.data);
				$("#opt-area_branch-kecamatan").val("");
				$("#opt-area_branch-kelurahan").val("");
			}
		});
    });	

    $('#opt-area_branch-city').change(function(){
        console.log("halo");
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_area_branch/setup_area_branch/getDescent',
			data: { "value": $("#opt-area_branch-city").val(),
                "<?= csrf_token() ?>" :  "<?= csrf_hash() ?>"},
            dataType: 'json',
			type: "get",
			success: function(data){
				$("#opt-area_branch-kecamatan").html(data.data)
				$("#opt-area_branch-kelurahan").val("");
			}
		});
    });	

    $('#opt-area_branch-kecamatan').change(function(){
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_area_branch/setup_area_branch/getDescent',
			data: { "value": $("#opt-area_branch-kecamatan").val(),
                "<?= csrf_token() ?>" :  "<?= csrf_hash() ?>"},
            dataType: 'json',
			type: "get",
			success: function(data){
				$("#opt-area_branch-kelurahan").html(data.data)
			}
		});
    });	
    

</script>