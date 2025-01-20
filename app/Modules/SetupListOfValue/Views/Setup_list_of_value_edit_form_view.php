


<form role="form" class="needs-validation" id="form_edit" name="form_edit" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">ID LoV Category</label>
				<input type="text" id="txt-lov-id" name="txt-lov-id" class="form-control form-control-sm mandatory" value="<?=$lov_data['id']?>" readonly required/>
			</div>
			
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">LoV Description</label>
				<input type="text" id="txt-lov-name" name="txt-lov-name" class="form-control form-control-sm mandatory" value="<?=$lov_data['category_name']?>" required />
			</div>
            
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">LoV Category</label>
				<?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-lov_category" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-lov_category', $get_category,$lov_data['category_lov'], $attributes);
				?>
			</div>

            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">LoV Category</label>
				<?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-lov_hirarki" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-lov_hirarki', $get_hirarki_list,$lov_data['hirarki'], $attributes);
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
				echo form_dropdown('opt-active-flag', $options, $lov_data['is_active'], $attributes);
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