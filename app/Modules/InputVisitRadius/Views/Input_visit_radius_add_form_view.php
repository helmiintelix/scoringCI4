


<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">ID</label>
				<input type="text" id="txt-id" name="txt-id" class="form-control form-control-sm mandatory" required/>
			</div>
			

			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Radius (M)</label>
				<input type="number" id="txt-radius" name="txt-radius" class="form-control form-control-sm mandatory" required />
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
				echo form_dropdown('opt-active-flag', $options, '1', $attributes);
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