<form role="form" class="needs-validation" id="form_approval_diskon" name="form_approval_diskon" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    
    <input type="hidden" id="arrlevel" name="arrlevel" value="<?=$arrlevel?>">
    <input type="hidden" id="numLevel" name="numLevel" value="<?=$data['numOfLevel']?>">
    <input type="hidden" id="id" name="id" value="<?=$id?>" >
    <input type="hidden" id="for" name="for" value="EDIT" >
	
    <div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Discount Approval Name</label>
				<input type="text" id="txt-disc-approval-name" name="txt-disc-approval-name" class="form-control form-control-sm" aria-describedby="txt-disc-approval-name" placeholder="Enter Approval Name" value="<?=$data['disc_approval_name']?>"/>
			</div>
			<div class="row mb-3">
                <div class="col-md-6">
                    <label for="form-field-select-2" class="fs-6 text-capitalize">Discount Amount From</label>
                    <input type="text" oninput="currencyformat(this)" id="txt-dic-amount-from" name="txt-dic-amount-from" class="form-control form-control-sm" placeholder="from" value="<?=$data['amtfrom']?>"/>
                </div>
                <div class="col-md-6">
                    <label for="form-field-select-3" class="fs-6 text-capitalize"> </label>
                    <input type="text" oninput="currencyformat(this)" id="txt-dic-amount-until" name="txt-dic-amount-until" class="form-control form-control-sm" placeholder="until" value="<?=$data['amtuntil']?>"/>
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
				echo form_dropdown('opt-active-flag', $options, $data['is_active'], $attributes);
				?>
			</div>
            
            <div class="row mb-3">
                <!-- Kolom Kiri -->
                <div class="col-sm-6">
                    <div class="form-group" id="form-checker">
                        <?php
                            $i = 0;
                            foreach ($data['json_checker'] as $key => $value) {
                        ?>
                            <div class='col-sm-12'>
                                <?php 
                                    if ($i == 0) {
                                ?>
                                    <label for="ix">Checked By</label>
                                    <!-- Bungkus elemen select dan bi bi-plus-square dalam input-group -->
                                    <div class="input-group">
                                        <?php
                                            $attributes = 'class="form-control form-control-sm" id="checked_by" style="display:block"';
                                            echo form_dropdown('checked_by[]', $user_list_checker, $value, $attributes);
                                        ?>

                                        <!-- Tambahkan input-group-append untuk bi bi-plus-square -->
                                        
                                        <span class="input-group-append">
                                            <span class="bi bi-plus-square" id='btn-add-chacker' style="cursor:pointer;margin-top: 5px;margin-left: 10px;" onClick="addChecker(this)" data-toggle="tooltip" data-placement="top" title="Menambahkan checker"></span>
                                            <img src="<?=base_url()?>/assets/img/loading.gif" class="pull-right" id="loading_checker" style="margin-top: 5px;display:none" >
                                        </span>

                                        <!-- Tambahkan loading image -->
                                    </div>
                                <?php 
                                    } else {
                                ?>
                                    <div class="input-group" style="margin-top: 10px;">
                                        <?php
                                            $attributes = 'class="form-control form-control-sm" id="checked_by"';
                                            echo form_dropdown('checked_by[]', $user_list_checker, $value, $attributes);
                                        ?>
                                        
                                        
                                        <span class="input-group-append">
                                            <span class="bi bi-x-square" style="cursor: pointer; color: red;margin-top: 5px;margin-left: 10px;" onClick="delList(this)" data-toggle="tooltip" data-placement="top" title="Delete user"></span>
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php
                                $i++;
                            }
                        ?>
                    </div>
                </div>


                
                <!-- Kolom Kanan -->
                <div class="col-sm-6" id='form-approval'>
                    <?php
                        $i = 1;
                        foreach ($data['json_approval'] as $key => $value) {
                    ?>
                        <div class="form-group" id="form-approval-<?=$i?>">
                            <?php
                                $j = 1;
                                if (!is_array($value)) return false;
                                foreach ($value as $key1 => $value1) {
                            ?>
                                <div class='col-sm-12'>
                                    <?php
                                        if ($i != 1 && $j == 1) {
                                            echo '<span onClick="delLevel(' . $i . ')" data-toggle="tooltip" data-placement="top" title="Hapus level approval" class="bi bi-dash-circle" style="margin-right: 6px; color: red; cursor: pointer;"></span>';
                                        }

                                        if ($key1 == 0) echo '<label for="" style="color: cornflowerblue" class="lbl-approval">Approval ' . $i . '</label> ';

                                        if ($i == 1 && $key1 == 0) {
                                            echo '<span class="bi bi-plus-square pull-right" id="btn-add-level-approval-1" style="cursor: pointer; margin-top: 5px; color: cornflowerblue; margin-left: 10px;" onClick="addLevelApproval(this)" data-toggle="tooltip" data-placement="top" title="Menambahkan level approval"></span>';
                                            echo '<img src="' . base_url() . '/assets/img/loading.gif" class="pull-right" id="loading-approval2-1" style="margin-top: 5px; display: none; margin-left: 10px;">';
                                        }

                                        if ($key1 == 0) {
                                            echo '<span class="bi bi-plus-square pull-right" id="btn-add-approval-' . $i . '" style="cursor: pointer; margin-top: 5px;" onClick="addApproval(this, ' . $i . ')" data-toggle="tooltip" data-placement="top" title="Menambahkan approval"></span>';
                                            echo '<img src="' . base_url() . '/assets/img/loading.gif" class="pull-right" id="loading-approval-' . $i . '" style="margin-top: 5px; display: none;">';
                                        }
                                    ?>
                                    
                                    <!-- Bungkus elemen select dan bi bi-x-square dalam input-group -->
                                    <div class="input-group" style="margin-top: 10px;">
                                        <?php
                                            $attributes = 'class="form-control form-control-sm" id="approval-by-' . $i . '"';
                                            echo form_dropdown('approval-by-' . $i . '[]', $user_list_approval, $value1, $attributes);
                                        ?>
                                        
                                        <!-- Tambahkan input-group-append untuk bi bi-x-square -->
                                        <?php if ($key1 > 0) { ?>
                                            <div class="input-group-append">
                                                <span class="bi bi-x-square" style="cursor: pointer; color: red; margin-top: 5px;" onClick="delList(this)" data-toggle="tooltip" data-placement="top" title="Delete user"></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php
                                $j++;
                                }
                            ?>
                        </div>
                    <?php
                        $i++;
                        }
                    ?>
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

<script src="<?= base_url(); ?>modules/setup_diskon_approval/js/setup_diskon_approval_edit.js?v=<?= rand() ?>"></script>