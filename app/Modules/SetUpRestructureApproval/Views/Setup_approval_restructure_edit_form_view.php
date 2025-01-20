<form role="form" class="needs-validation" id="form_approval_diskon" name="form_approval_diskon" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="id" value="<?= $data['id']; ?>" />
    
    <input type="hidden" id="arrlevel" name="arrlevel" value="<?=$arrlevel?>">
    <input type="hidden" id="numLevel" name="numLevel" value="<?=$data['numOfLevel']?>">
    <!-- <input type="hidden" id="for" name="for" value="ADD"> -->
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-disc-approval-name" class="fs-6 text-capitalize">Restructure Approval Name</label>
                <input type="text" id="txt-disc-approval-name" name="txt-disc-approval-name"
                    class="form-control form-control-sm" aria-describedby="txt-disc-approval-name"
                    placeholder="Enter Approval Name" value="<?=$data['disc_approval_name']?>" />
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txt-dic-amount-from" class="fs-6 text-capitalize">Outstanding Balance</label>
                    <input type="text" oninput="currencyformat(this)" id="txt-dic-amount-from"
                        name="txt-dic-amount-from" class="form-control form-control-sm" placeholder="from"
                        value="<?=$data['amtfrom']?>" />
                </div>
                <div class="col-md-6">
                    <label for="txt-dic-amount-until" class="fs-6 text-capitalize"> </label>
                    <input type="text" oninput="currencyformat(this)" id="txt-dic-amount-until"
                        name="txt-dic-amount-until" class="form-control form-control-sm" placeholder="until"
                        value="<?=$data['amtuntil']?>" />
                </div>
            </div>

            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" <?= $data["is_active"]=='1'?  'checked' :  ''; ?>>
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
                        <?php 
                            $i=0;
                            foreach ($data['json_checker'] as $key => $value) {
                        ?>
                        <div class="input-group" style="margin-top: 10px;">
                            <?
                                $attributes = 'class="form-control form-control-sm" id="checked_by" style="display:block" ';
                                echo form_dropdown('checked_by[]', $user_list_checker, $value, $attributes);
                            ?>
                            </select>
                            <?php 
                                if($i==0){
                            ?>
                            <span class="input-group-append">
                                <span class="bi bi-plus-square" id='btn-add-chacker'
                                    style="cursor:pointer;margin-top: 5px;margin-left: 10px;" onClick="addChecker(this)"
                                    data-toggle="tooltip" data-placement="top" title="Menambahkan checker"></span>
                                <div class="spinner-border spinner-border-sm" id="loading_checker" role="status">
                                    <span class="visually-hidden" id="loading_checker">Loading...</span>
                                </div>
                            </span>
                            <?php 
                                } else {
                            ?>
                            <span class="input-group-append">
                                <span class="bi bi-x-square"
                                    style="color:red;cursor: pointer;margin-top: 5px;margin-left: 10px;color:red;"
                                    onClick="delListChecker(this)" data-toggle="tooltip" data-placement="top"
                                    title="Delete user"></span>
                            </span>
                            <?php 
                                }
                            ?>
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
                        $j = 1;
                        foreach ($data['json_approval'] as $key => $value) {
                    ?>
                    <div class="form-group" id="form-approval-<?=$j?>">
                        <?php 
                            $k = 1;
                            if(!is_array($value)) return false;
                            foreach ($value as $key1 => $value1) {
                        ?>
                        <?php 
                            if($j!=1 && $k==1){
                                echo '<span onClick="delLevel('.$j.')" data-toggle="tooltip" data-placement="top" title="Hapus level approval" class="bi bi-dash-circle" style="margin-right: 6px; color: red; cursor: pointer;"></span>';
                            }
                            if ($key1 == 0) {
                                echo '<label for="" style="color:cornflowerblue" class="lbl-approval">Approval '.$j.'</label>';
                            }
                        ?>

                        <div class="input-group">
                            <?
                                if ($key1 == 0) {
                                    $attributes = 'class="form-control form-control-sm" id="approval-by-1" style="display:block" ';
                                } else {
                                    $attributes = 'class="form-control form-control-sm" id="approval-by-1" style="display:block; margin-top:10px" ';
                                }
                                
                                echo form_dropdown('approval-by-'.$j.'[]', $user_list_approval, $value1, $attributes);
                            ?>
                            </select>
                            <span class="input-group-append">
                                <?php 
                                if ($j == 1 && $key1 == 0) {
                            ?>
                                <span class="bi bi-plus-square pull-right" id='btn-add-level-approval-1'
                                    style="cursor:pointer;margin-top: 5px;color:cornflowerblue;margin-left: 10px;"
                                    onClick="addLevelApproval(this)" data-toggle="tooltip" data-placement="top"
                                    title="menambahkan level approval"></span>
                                <div class="spinner-border spinner-border-sm" id="loading-approval2-1" role="status">
                                    <span class="visually-hidden" id="loading-approval2-1">Loading...</span>
                                </div>
                                <?php 
                                }
                                if ($key1 == 0) {
                            ?>
                                <span class="bi bi-plus-square pull-right" id='btn-add-approval-<?=$j?>'
                                    style="cursor:pointer; margin-top: 5px; margin-left: <?= ($j == 1) ? '0px' : '10px' ?>"
                                    onClick="addApproval(this,<?=$j?>)" data-toggle="tooltip" data-placement="top"
                                    title="menambahkan approval">
                                </span>

                                <div class="approval-loading spinner-border spinner-border-sm"
                                    id="loading-approval-<?=$j?>" role="status" style="display: none;">
                                    <span class="visually-hidden" id="loading-approval-<?=$j?>">Loading...</span>
                                </div>
                            </span>
                            <?php 
                                } else {
                                    echo '<span class="bi bi-x-square" style="color:red;cursor: pointer;margin-top: 5px;margin-left: 10px;color:red;" onClick="delListChecker(this)" data-toggle="tooltip" data-placement="top" title="Delete user"></span>';
                                }
                            ?>
                        </div>
                        <?php 
                            $k++;
                            }
                        ?>
                    </div>
                    <?php 
                        $j++;
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>




</form>

<script type="text/javascript">
    var numberOfLevel = <?= $data['numOfLevel'] ?>;
    // $('#numLevel').val(numberOfLevel);
    var lastNumberOfLevel = <?= $data['numOfLevel'] ?> ;
    var numberOfChecker = <?= $i; ?> ;
    var LIMIT_LEVEL_APPROVAL = parseInt('<?= $LIMIT_LEVEL_APPROVAL ?>');
    var LIMIT_USER_APPROVAL = parseInt("<?=$LIMIT_USER_APPROVAL?>");
    var LIMIT_USER_CHECKER = parseInt("<?=$LIMIT_USER_CHECKER?>");
</script>

<script src="<?= base_url(); ?>modules/setup_restructure_approval/js/script_add_form.js?v=<?= rand() ?>"></script>