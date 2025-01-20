<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="txt-id" id="txt-id" value="<?= $id; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3">
                <label for="txt-zipcode_area_assign-sub_area_id" class="fs-6 text-capitalize"> SUB AREA ID</label>
                <input type="text" class="form-control form-control-sm  mandatory"
                    name="txt-zipcode_area_assign-sub_area_id" id="txt-zipcode_area_assign-sub_area_id">
            </div>
            <div class="mb-3">
                <label for="txt-zipcode_area_assign-sub_area_name" class="fs-6 text-capitalize"> SUB AREA NAME</label>
                <input type="text" class="form-control form-control-sm  mandatory"
                    name="txt-zipcode_area_assign-sub_area_name" id="txt-zipcode_area_assign-sub_area_name">
            </div>
            <div class="mb-3">
                <label for="opt-zipcode_area_assign-area_tagih" class="fs-6 text-capitalize"> AREA TAGIHE</label>
                <?php
                    $options = array(""=>"SELECT BRANCH AREA TAGIH") + $branch_area_tagih;
                    $attributes = 'class="form-control form-control-sm  mandatory" id="opt-zipcode_area_assign-area_tagih" name="opt-zipcode_area_assign-area_tagih" data-placeholder ="[select]"';
                    echo form_dropdown('opt-zipcode_area_assign-area_tagih', $options, '', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-zipcode_area_assign-product" class="fs-6 text-capitalize">PRODUCT</label>
                <?php
                    foreach($product as $value){
                        $data[$value["productcode"]] = $value["productcode"];
                    } 
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-zipcode_area_assign-product" name="opt-zipcode_area_assign-product[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-zipcode_area_assign-product[]', $data,  "", $attributes);
				?>
            </div>
            <div class="mb-3">
                <label for="txt-zipcode_area_mapping-zip_code" class="fs-6 text-capitalize">CUSTOMER ZIPCODE
                </label>
                <div class="row">
                    <div class="col-6">
                        <textarea class="form-control form-control-sm mandatory"
                            name="txt-zipcode_area_mapping-zip_code" id="txt-zipcode_area_mapping-zip_code" cols="30"
                            rows="5"><?= $id; ?></textarea>
                    </div>
                    <div class="col-6">
                        <label> *use minus(-) to separator range,<br>and comma(,) to add new range </label>

                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<script src="<?= base_url(); ?>modules/unassigned_zipcode/js/unassigned_zipcode_add.js?v=<?= rand() ?>"></script>