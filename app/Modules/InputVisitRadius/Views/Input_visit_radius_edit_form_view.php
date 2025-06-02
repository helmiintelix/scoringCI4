


<form role="form" class="needs-validation" id="form_edit" name="form_edit" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">ID</label>
				<input type="text" id="txt-id" name="txt-id" class="form-control form-control-sm mandatory" required disabled value="<?= $data["id"] ?>"/>
			</div>
			

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Radius (M)</label>
				<input type="number" id="txt-radius" name="txt-radius" class="form-control form-control-sm mandatory" required value="<?= $data["radius"] ?>"/>
			</div>

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
				<div class="form-check form-switch">
					<label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
					<input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo $data["is_active"]=='1'?  'checked' :  ''; ?>>
				</div>
			</div>

			<div class="mb-3 " style="display:none">
                <input type="text" id="id_edit" name="id_edit" value="<?= $data["id"] ?>"/>
				<label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
				<?php
				$options = array(
					'1' => 'ENABLE',
					'0'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, $data["is_active"], $attributes);
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
</script>