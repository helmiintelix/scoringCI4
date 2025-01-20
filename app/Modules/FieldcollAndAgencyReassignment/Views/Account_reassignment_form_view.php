<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="txt-cd-customers" id="txt-cd-customers" value="<?= $cd_customers; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="opt-cd-customer" class="fs-6 text-capitalize">CUSTOMER NO</label>
                <?php
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-cd-customer" name="opt-cd-customer[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-cd-customer[]', $cd_customer_list,  "", $attributes);
                ?>
            </div>
            <div class="mb-3">
                <label for="optGroupAssignment" class="fs-6 text-capitalize"> ASSIGNMENT TO</label>
                <select name="optGroupAssignment" id="optGroupAssignment" class="form-control mandatory">
                    <option value="">select group</option>
                    <option value="TEAM">Team</option>
                    <option value="FC">Field Collector</option>
                    <!-- <option value="AGENCY">Agency</option> -->
                </select>
            </div>
            <div class="mb-3" id="showTeam">
                <label for="opt-id-coll" class="fs-6 text-capitalize"> ASSIGN TO TEAM</label>
                <?
                    $attributes = 'class="form-control mandatory" id="opt-id-coll" name="opt-id-coll"';
                    echo form_dropdown('opt-id-coll', $team_list, "", $attributes);
                ?>
            </div>
            <div class="mb-3" id="showFieldCollector">
                <label for="opt_id_field_coll" class="fs-6 text-capitalize"> FIELD COLLECTOR</label>
                <?
                    $attributes = 'class="form-control mandatory" id="opt_id_field_coll" name="opt_id_field_coll"';
                    echo form_dropdown('opt_id_field_coll', $collector_field_list, "", $attributes);
                ?>
            </div>
            <div class="mb-3" id="showAgency">
                <label for="opt_id_agency" class="fs-6 text-capitalize">AGENCY</label>
                <?
                    $attributes = 'class="form-control mandatory" id="opt_id_agency" name="opt_id_agency"';
                    echo form_dropdown('opt_id_agency', $agency_list, "", $attributes);
                ?>
            </div>
            <div class="mb-3">
                <label for="assignmentType" class="fs-6 text-capitalize"> ASSIGNMENT TYPE</label>
                <select name="assignmentType" id="assignmentType" class="form-control mandatory">
                    <option value="permanen">Permanen</option>
                    <option value="temporer">Temporer</option>
                </select>
            </div>
            <div class="mb-3" id="showDate" style="display: none;">
                <label for="fromDate" class="fs-6 text-capitalize"> FROM DATE</label>
                <input type="text" class="form-control form-control-sm mandatory mb-3" name="fromDate" id="fromDate">
                <label for="toDate" class="fs-6 text-capitalize"> TO DATE</label>
                <input type="text" class="form-control form-control-sm mandatory" name="toDate" id="toDate">
            </div>
            <div class="mb-3">
                <label for="opt-ac-agent" class="fs-6 text-capitalize">DISTRIBUTION</label>
                <?
                    $options = array( '' => 'SELECT DISTRIBUTION',
                                    '1'=>'ROUND ROBIN',
                                    '2'=>'REVERSED ROBIN'
                                );
                    $attributes = 'class="form-control mandatory" id="opt-ac-agent" name="opt-ac-agent"';
                    echo form_dropdown('opt-ac-agent', $options, "", $attributes);
                ?>
            </div>
            <div class="mb-3">
                <label for="txt-distribution-persen" class="fs-6 text-capitalize">%DISTRIBUTION</label>
                <input type="text" class="form-control form-control-sm mandatory" name="txt-distribution-persen"
                    id="txt-distribution-persen">
            </div>
            <div class="mb-3">
                <label for="txt-remarks" class="fs-6 text-capitalize">REMARKS</label>
                <input type="text" class="form-control form-control-sm mandatory" name="txt-remarks" id="txt-remarks">
            </div>
        </div>
    </div>

</form>

<script
    src="<?= base_url(); ?>modules/fieldcoll_and_agency_reassignment/js/account_reassignment_form.js?v=<?= rand() ?>">
</script>