
<?php
	$lov1_label="placeholder='please input label name'";
	$lov2_label="placeholder='please input label name'";
	$lov3_label="placeholder='please input label name'";
	$lov4_label="placeholder='please input label name'";
	$lov5_label="placeholder='please input label name'";
	if($lov_data['lov1_label_name']){
		$lov1_label="value='".$lov_data['lov1_label_name']."'"; 
	}
	if($lov_data['lov2_label_name']){
		$lov2_label="value='".$lov_data['lov2_label_name']."'"; 
	}
	if($lov_data['lov3_label_name']){
		$lov3_label="value='".$lov_data['lov3_label_name']."'"; 
	}
	if($lov_data['lov4_label_name']){
		$lov4_label="value='".$lov_data['lov4_label_name']."'"; 
	}
	if($lov_data['lov5_label_name']){
		$lov5_label="value='".$lov_data['lov5_label_name']."'"; 
	}
?>

<form role="form" class="needs-validation" id="form_edit-relation" name="form_edit-relation" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="text" id="txt-id" name="txt-id" class="col-sm-9 " value="<?=$lov_data['lov_id']?>" hidden readonly />

	<div class="row">
		<div class="row">
			<div class="col-sm-6">
				<div class="mb-3">
					<label for="txt-lov1-label-name" class="fs-6 text-capitalize">LoV 1</label>
					<input type="text" id="txt-lov1-label-name" name="txt-lov1-label-name" class="form-control form-control-sm mandatory" <?=$lov1_label?> readonly required/>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="mb-3">
					<?php
						$attributes = 'class="form-control form-control-sm mandatory" multiple id="opt-lov1-category[]" data-placeholder="[select]" required';
						echo form_dropdown("opt-lov1-category[]", $lov_category, $lov1_category, $attributes);
					?>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="mb-3">
					<label for="txt-lov2-label-name" class="fs-6 text-capitalize">LoV 2</label>
					<input type="text" id="txt-lov2-label-name" name="txt-lov2-label-name" class="form-control form-control-sm mandatory" <?=$lov2_label?> readonly required/>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="mb-3">
					<?php
						$attributes = 'class="form-control form-control-sm mandatory" multiple id="opt-lov2-category[]" data-placeholder="[select]" required';
						echo form_dropdown("opt-lov2-category[]", $lov_category, $lov2_category, $attributes);
					?>
				</div>
			</div>
		</div>
				
		<div class="row">
			<div class="col-sm-6">
				<div class="mb-3">
					<label for="txt-lov3-label-name" class="fs-6 text-capitalize">LoV 3</label>
					<input type="text" id="txt-lov3-label-name" name="txt-lov3-label-name" class="form-control form-control-sm mandatory" <?=$lov3_label?> readonly required/>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="mb-3">
					<?php
						$attributes = 'class="form-control form-control-sm mandatory" multiple id="opt-lov3-category[]" data-placeholder="[select]" required';
						echo form_dropdown("opt-lov3-category[]", $lov_category, $lov3_category, $attributes);
					?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="mb-3">
					<label for="txt-lov4-label-name" class="fs-6 text-capitalize">LoV 4</label>
					<input type="text" id="txt-lov4-label-name" name="txt-lov4-label-name" class="form-control form-control-sm mandatory" <?=$lov4_label?> readonly required/>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="mb-3">
					<?php
						$attributes = 'class="form-control form-control-sm mandatory" multiple id="opt-lov4-category[]" data-placeholder="[select]" required';
						echo form_dropdown("opt-lov4-category[]", $lov_category, $lov4_category, $attributes);
						
					?>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="mb-3">
					<label for="txt-lov5-label-name" class="fs-6 text-capitalize">LoV 5</label>
					<input type="text" id="txt-lov5-label-name" name="txt-lov5-label-name" class="form-control form-control-sm mandatory" <?=$lov5_label?> readonly required/>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="mb-3">
					<?php
						$attributes = 'class="form-control form-control-sm mandatory" multiple id="opt-lov5-category[]" data-placeholder="[select]" required';
						echo form_dropdown("opt-lov5-category[]", $lov_category, $lov5_category, $attributes);
					?>
				</div>
			</div>
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
			echo form_dropdown('opt-active-flag', $options, $lov_data['is_active'], $attributes);
			?>
		</div>
		<div class="mb-3 " style="display:none">
			<label for="form-field-select-2" class="fs-6 text-capitalize">Collection Type</label>
			<?php
				$options = array(
					'' => '-Pilih Tipe-',
					'FieldColl' => 'FieldColl',
					'TeleColl' => 'TeleColl');
				$attributes = 'class="form-control form-control-sm mandatory" id="opt-type_collection" data-placeholder ="[select]" required';
				echo form_dropdown('opt-type_collection', $options,$lov_data['type_collection'], $attributes);
			?>
		</div>

	</div>
	
</form>

<script type="text/javascript">

$(document).ready(function() {
    var isActive = <?php echo $lov_data['is_active']; ?>;
    if (isActive == 1) {
        $("#flexSwitchCheckChecked").prop('checked', true);
        setActive($("#flexSwitchCheckChecked")[0]);
    } else {
        $("#flexSwitchCheckChecked").prop('checked', false);
        setActive($("#flexSwitchCheckChecked")[0]);
    }

    function setActive(elm) {
        if ($(elm)[0].checked) {
            $("#opt-active-flag").val('1').change();
            //$("label[for='flexSwitchCheckChecked']").text('Active');
        } else {
            $("#opt-active-flag").val('0').change();
            //$("label[for='flexSwitchCheckChecked']").text('Not Active');
        }
    }

});
</script>