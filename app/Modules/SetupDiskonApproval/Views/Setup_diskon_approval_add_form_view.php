<form role="form" class="needs-validation" id="form_approval_diskon" name="form_approval_diskon" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    
    <input type="hidden" id="arrlevel" name="arrlevel" value="approval-by-1[]">
    <input type="hidden" id="numLevel" name="numLevel" >
    <input type="hidden" id="for" name="for" value="ADD" >
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Discount Approval Name</label>
				<input type="text" id="txt-disc-approval-name" name="txt-disc-approval-name" class="form-control form-control-sm" aria-describedby="txt-disc-approval-name" placeholder="Enter Approval Name"/>
			</div>
			<div class="row mb-3">
                <div class="col-md-6">
                    <label for="form-field-select-2" class="fs-6 text-capitalize">Discount Amount From</label>
                    <input type="text" oninput="currencyformat(this)" id="txt-dic-amount-from" name="txt-dic-amount-from" class="form-control form-control-sm" placeholder="from" />
                </div>
                <div class="col-md-6">
                    <label for="form-field-select-3" class="fs-6 text-capitalize"> </label>
                    <input type="text" oninput="currencyformat(this)" id="txt-dic-amount-until" name="txt-dic-amount-until" class="form-control form-control-sm" placeholder="until" />
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
				$attributes = 'class="form-control form-control-sm  mandatory" id="txt-disc-is-active" data-placeholder ="[select]" required';
				echo form_dropdown('txt-disc-is-active', $options, '1', $attributes);
				?>
			</div>
            
            <div class="row mb-3">
                <!-- Kolom Kiri -->
                <div class="col-sm-6">
                    <div class="form-group" id="form-checker">
                        <label for="ix">Checked By</label>
                        <div class="input-group">
                            <?
                                $attributes = 'class="form-control form-control-sm" id="checked_by" style="display:block" ';
                                echo form_dropdown('checked_by[]', $user_list_checker, "", $attributes);
                            ?>
                            </select>
                            <span class="input-group-append">
                                <span class="bi bi-plus-square" id='btn-add-chacker' style="cursor:pointer;margin-top: 5px;margin-left: 10px;" onClick="addChecker(this)" data-toggle="tooltip" data-placement="top" title="Menambahkan checker"></span>
                                <img src="<?=base_url()?>/assets/img/loading.gif" class="pull-right" id="loading_checker" style="margin-top: 5px;display:none" >
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Kolom Kanan -->
                <div class="col-sm-6" id='form-approval'>
                    <div class="form-group" id="form-approval-1">
                        <label for="" style="color:cornflowerblue" class="lbl-approval">Approval 1</label>
                        <div class="input-group">
                            <img src="<?=base_url()?>/assets/img/loading.gif" class="pull-right" id="loading_checker" style="margin-top: 5px;display:none" >
                            <?
                                $attributes = 'class="form-control form-control-sm" id="approval-by-1" style="display:block" ';
                                echo form_dropdown('approval-by-1[]', $user_list_approval, "", $attributes);
                            ?>
                            </select>
                            <span class="input-group-append">
                                <span class="bi bi-plus-square pull-right" id='btn-add-level-approval-1' style="cursor:pointer;margin-top: 5px;color:cornflowerblue;margin-left: 10px;" onClick="addLevelApproval(this)" data-toggle="tooltip" data-placement="top" title="menambahkan level approval" ></span>
                                <img src="<?=base_url()?>/assets/img/loading.gif" class="pull-right" id="loading-approval2-1" style="margin-top: 5px;display:none;margin-left: 10px;" >
                                <span class="bi bi-plus-square pull-right" id='btn-add-approval-1' style="cursor:pointer;margin-top: 5px;" onClick="addApproval(this,1)" data-toggle="tooltip" data-placement="top" title="menambahkan approval" ></span>
                                <img src="<?=base_url()?>/assets/img/loading.gif" class="pull-right" id="loading-approval-1" style="margin-top: 5px;display:none" >
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	
	

	
</form>

<script type="text/javascript">
var numberOfLevel = 1 ;
$('#numLevel').val(numberOfLevel);
var lastNumberOfLevel = 1 ;
var numberOfChecker = 1 ;
var LIMIT_LEVEL_APPROVAL =  parseInt(<?=$LIMIT_LEVEL_APPROVAL?>) ;
var LIMIT_USER_APPROVAL =  parseInt("<?=$LIMIT_USER_APPROVAL?>") ;
var LIMIT_USER_CHECKER =  parseInt("<?=$LIMIT_USER_CHECKER?>") ;

</script>

<script src="<?= base_url(); ?>modules/setup_diskon_approval/js/setup_diskon_approval_add.js?v=<?= rand() ?>"></script>