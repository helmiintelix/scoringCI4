<style>
    .checkbox {
        padding-left: 3px;
        padding-top: 2px;
        padding-right: 3px;
    }

    table {
        outline-style: dotted;
        outline-color: grey;
        /* margin: 6px; */
    }

    .lbl {
        font-size: 12px;
    }
</style>
<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="classification_id" value="<?= $data['classification_id']; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-classification-name" class="fs-6 text-capitalize">Campaign Name</label>
                <input type="text" id="txt-classification-name" name="txt-classification-name"
                    class="form-control form-control-sm mandatory" required
                    value="<?= $data['classification_name']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="description" class="fs-6 text-capitalize">Campaign Description</label>
                <textarea class="form-control form-control-sm mandatory" id="description" name="description" cols="80"
                    rows="4" required><?= $data['description']; ?></textarea>
            </div>
            <div class="mb-3 ">
                <label for="txt-class-category" class="fs-6 text-capitalize">Campaign Category</label>
                <input type="text" id="txt-class-category" name="txt-class-category"
                    class="form-control form-control-sm mandatory" required value="<?= $data['class_category']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-classification-priority" class="fs-6 text-capitalize">Campaign Priority</label>
                <input type="text" id="txt-classification-priority" name="txt-classification-priority"
                    class="form-control form-control-sm mandatory" required value="<?= $data['class_priority']; ?>" />
                (* higher number less priority)
            </div>
            <div class="mb-3 ">
                <label for="opt-handling" class="fs-6 text-capitalize">Account Handling</label>
                <?php
                     $options = array(
                         'SMS' => 'SMS',
                         'Email' => 'Email',
                         'Whatsapp' => 'Whatsapp',
                         'Telecoll' => 'Telecoll',
                         'Fieldcoll' => 'Fieldcoll',
                         'Robocoll' => 'Robocoll',
                         'NCA' => 'Non Collection Activity'
                        );
                        $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-handling" name="opt-handling[]"  multiple data-placeholder="-Please Select Account Handling-"';
                        echo form_dropdown('opt-handling[]', $options,  $account_handling, $attributes);
                        ?>
            </div>
            <div class="mb-3" style="display: none;">
                <label for="check-number" class="fs-6 text-capitalize">Using Check Number</label>
                <?php
                    $options = array(
                        '0' => 'No',
                        '1' => 'Yes'
                    );
                    $attributes = 'class="form-control form-control-sm mandatory" id="check-number" name="check-number" data-placeholder="-Please Select Value-"';
                    echo form_dropdown('check-number', $options,  $check_number, $attributes);
                ?>
            </div>
            <div class="mb-3" id="form-template-sms" <?= ($data['sms_template']) ? '' : 'style="display:none"' ?>>
                <label for="opt-sms-template" class="fs-6 text-capitalize">SMS Template</label>
                <?php
                    //  $options = array(
                    //     '' => 'SELECT SMS Template'
                    // );
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-sms-template" name="opt-sms-template" data-placeholder="-Please Select Value-"';
                    echo form_dropdown('opt-sms-template', $sms_template_list,  $data['sms_template'], $attributes);
                ?>
            </div>
            <div class="mb-3" id="form-template-email" <?= ($data['email_template']) ? '' : 'style="display:none"' ?>>
                <label for="opt-email-template" class="fs-6 text-capitalize">Email Template</label>
                <?php
                    //  $options = array(
                    //     '' => 'SELECT Email Template'
                    // );
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-email-template" name="opt-email-template" data-placeholder="-Please Select Value-"';
                    echo form_dropdown('opt-email-template', $email_template_list,  $data['email_template'], $attributes);
                ?>
            </div>
            <!-- <div class="mb-3" id="form-template-wa">
                <label for="opt-wa-template" class="fs-6 text-capitalize">Whatsapp Template</label>
                <?php
                    //  $options = array(
                    //     '' => 'SELECT Whatsapp Template'
                    // );
                    // $attributes = 'class="form-control form-control-sm mandatory" id="opt-wa-template" name="opt-wa-template" data-placeholder="-Please Select Value-"';
                    // echo form_dropdown('opt-wa-template', $options,  "", $attributes);
                ?>
            </div> -->
            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Campaign Detail</label>
                <div class="col-sm-12" id='query_builder'></div>
            </div>
            <div class="mb-3">
                <label for="opt-filter-list" class="fs-6 text-capitalize">History Parameter</label>
                <div class="row">
                    <div class="col-sm-4">
                        <?php
                        $options = array(
                            '' => 'Select',
                            'LOV1' => 'LOV 1',
                            'LOV2' => 'LOV 2',
                            'LOV3' => 'LOV 3',
                            'LOV4' => 'LOV 4',
                            'LOV5' => 'LOV 5',
                            'MAX_DPD_30' => 'Ever DPD 30 +',
                            'MAX_DPD_90' => 'Ever DPD 90 +',
                            'ptp_status' => 'Status Payment'
                        );
                        $attributes = 'class="form-control form-control-sm mandatory" id="opt-filter-list" name="opt-filter-list" data-placeholder="-Please Select Value-"';
                        echo form_dropdown('opt-filter-list', $options,  $parameter_field, $attributes);
                    ?>
                    </div>
                    <div class="col-sm-4">
                        <?php
                            $attributes = 'class="form-control form-control-sm mandatory" id="opt-detail-list" name="opt-detail-list"';
                            echo form_dropdown("opt-detail-list", $value_c,  $value_c, $attributes);
                        ?>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt-times" name="txt-times"
                            class="form-control form-control-sm mandatory" placeholder="times" value="<?= $times; ?>" />
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt-days" name="txt-days" class="form-control form-control-sm mandatory"
                            placeholder="days" value="<?= $days; ?>" />
                    </div>
                </div>
            </div>
            <div class="mb-3" id="set_detail">
                <div id="div_add_set_header" name="div_add_set_header"
                    class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="col-auto">
                        <label for="opt-wa-template" class="fs-6 text-capitalize">Set Detail</label>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-success btn-sm" id="btn_add_set"><i
                                class="bi bi-plus-circle"></i> Add</button>
                    </div>
                </div>
                <div id="div_edit_set" name="div_edit_set" class="row align-items-center">
                    <div class="col-auto">
                        <?php
                            $attributes = 'class="form-control form-control-sm mandatory" id="opt-search" data-placeholder="-Please Select Data-"';
                            echo form_dropdown('opt-search[]', $field_name,  "", $attributes);
                        ?>
                    </div>
                    <div class="col-auto">
                        <label for="txt-class-category" class="fs-6 text-capitalize"> = </label>
                    </div>
                    <div class="col-sm-3" id="opt-input">
                        <?php
                            $options = array(
                                "" => "[select data]", 
                                "this" => "this", 
                                "last_agent1" => "last agent"
                            );
                            $attributes = 'class="form-control form-control-sm mandatory" id="opt-self" data-placeholder="-Please Select Data-"';
                            echo form_dropdown('opt-self[]', $options,  "", $attributes);
                        ?>
                    </div>
                    <div class="col-auto" id="text-input">
                        <input type="text" id="txt-keyword" name="txt-keyword[]"
                            class="form-control form-control-sm mandatory" />
                    </div>
                    <div class="col-auto justify-content-end">
                        <button type="button" class="btn btn-danger btn-sm" id="btn_delete_set"><i
                                class="bi bi-x-circle"></i> Delete</button>
                    </div>
                </div>

            </div>
            <div class="mb-3" style="display: none;">
                <label for="opt-class-type" class="fs-6 text-capitalize">Class Type</label>
                <?php
                     $class_type_vals = array(
                        'REGULAR' => 'Regular',
                        'CHAMPION' => 'Champion',
                        'CHALLENGER' => 'Challenger'
                    );
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-class-type" name="opt-class-type" data-placeholder="-Please Select Value-"';
                    echo form_dropdown('opt-class-type', $class_type_vals,  "", $attributes);
                ?>
            </div>
            <div class="mb-3">
                <label for="opt-schedule" class="fs-6 text-capitalize">Job Schedule</label>
                <div class="row align-items-center">
                    <div class="col-4">
                        <?php
                            $schedule = array(
                                '' => 'None',
                                'DAILY' => 'Daily',
                                'WEEKLY' => 'Weekly',
                                'MONTHLY' => 'Monthly',
                                'DATE' => 'Date',
                                'DATE_RANGE' => 'Date Range',
                            );
                            $attributes = 'class="form-control form-control-sm mandatory" id="opt-schedule" name="opt-schedule" data-placeholder="-Please Select Value-"';
                            echo form_dropdown('opt-schedule', $schedule, $data['job_schedule'], $attributes);
                        ?>
                    </div>
                    <?php 
                        if ($data['spesific_date'] == "0000-00-00") {
                            $specific_date = '';
                        } else {
                            $specific_date = $data['spesific_date'];
                        }
                    ?>

                    <div id="div_date" class="col-8" style="display: none;">
                        <input type="text" id="specificDate" name="specificDate"
                            class="form-control form-control-sm mandatory" value="<?= $specific_date; ?>" />
                    </div>
                    <?
                        if ($data['end_date'] == "0000-00-00") {
                            $end_date = '';
                        } else {
                            $end_date = $data['end_date'];
                        }
                        if ($data['start_date'] == "0000-00-00") {
                            $start_date = '';
                        } else {
                            $start_date = $data['start_date'];
                        }
                    ?>
                    <div id="div_date_range" class="col-8" style="display: none;">
                        <div class="input-group">
                            <div class="input-group-text">From</div>
                            <input type="text" id="DateRangeFrom" name="DateRangeFrom"
                                class="form-control form-control-sm mandatory" placeholder="From"
                                value="<?= $start_date; ?>" />
                            <div class="input-group-text">To</div>
                            <input type="text" id="DateRangeTo" name="DateRangeTo"
                                class="form-control form-control-sm mandatory" placeholder="To"
                                value="<?= $end_date; ?>" />
                        </div>
                    </div>
                </div>
                <div id='div_week' class='form group col-xs-10 col-sm-12 border rounded' style="display: none;">
                    <?
                        $week = array(
                            '1' => 'Sunday',
                            '2' => 'Monday',
                            '3' => 'Tuesday',
                            '4' => 'Wednesday',
                            '5' => 'Thursday',
                            '6' => 'Friday',
                            '7' => 'Saturday',

                        );
                     
                        $ar_weekly_day =  explode(',', $data['weekly_day']);
                        foreach ($week as $key => $value) {
                            $data = array(
                                'name'          => 'opt-week[]',
                                'id'            => $key,
                                'value'         => $key,
                                'checked'       => in_array($key, $ar_weekly_day),
                                'style'         => 'margin:10px'
                            );
                            echo form_checkbox($data);
                            echo $value;
                        }
                    ?>
                </div>
            </div>
            <div class="mb-3 border rounded" id="div_monthly" style="display: none;">
                <?
                    $month = array(
                        "01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August",
                        "09" => "September", "10" => "October", "11" => "November", "12" => "December"
                    );
                ?>
                <table id="table_month" class="col-sm-12">
                    <tr>
                        <td><label style="color: rgb(244, 132, 32);"><b>Month</b></label></td>
                        <?php for ($i = 1; $i <= 6; $i++) { ?>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" name="months-checkbox[]"
                                    value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>"
                                    id="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>" class="form-check-input ace" />
                                <label class="form-check-label" for="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>">
                                    <?= $month[str_pad($i, 2, "0", STR_PAD_LEFT)] ?>
                                </label>
                            </div>
                        </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td></td>
                        <?php for ($i = 7; $i <= 12; $i++) { ?>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" name="months-checkbox[]"
                                    value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>"
                                    id="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>" class="form-check-input ace" />
                                <label class="form-check-label" for="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>">
                                    <?= $month[str_pad($i, 2, "0", STR_PAD_LEFT)] ?>
                                </label>
                            </div>
                        </td>
                        <?php } ?>
                    </tr>
                </table>
                <table class="col-sm-12">
                    <tr>
                        <td><label style="color: rgb(244, 132, 32)"><b>Option</b></label></td>
                        <td>
                            <div class="form-check">
                                <input name="month-option" type="radio" class="form-check-input ace" id="FIRST_DAY"
                                    value="FIRST_DAY" />
                                <label class="form-check-label" for="FIRST_DAY">First day</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="month-option" type="radio" class="form-check-input ace" id="LAST_DAYS"
                                    value="LAST_DAYS" />
                                <label class="form-check-label" for="LAST_DAYS">Last day</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="month-option" type="radio" class="form-check-input ace" id="DAYS"
                                    value="DAYS" />
                                <label class="form-check-label" for="DAYS">Days</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="month-option" type="radio" class="form-check-input ace" id="WEEK_DAYS"
                                    value="WEEK_DAYS" />
                                <label class="form-check-label" for="WEEK_DAYS">Week days</label>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="col-sm-8" id="table_week" style="display: none;">
                    <tr>
                        <td><label style="color: rgb(244, 132, 32)"><b>Weeks</b></label></td>
                        <td>
                            <div class="form-check">
                                <input name="weeks-checkbox[]" type="checkbox" class="form-check-input ace" value="1"
                                    id="w1" />
                                <label class="form-check-label" for="w1">FIRST</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weeks-checkbox[]" type="checkbox" class="form-check-input ace" value="2"
                                    id="w2" />
                                <label class="form-check-label" for="w2">SECOND</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weeks-checkbox[]" type="checkbox" class="form-check-input ace" value="3"
                                    id="w3" />
                                <label class="form-check-label" for="w3">THIRD</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weeks-checkbox[]" type="checkbox" class="form-check-input ace" value="4"
                                    id="w4" />
                                <label class="form-check-label" for="w4">FOURTH</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weeks-checkbox[]" type="checkbox" class="form-check-input ace" value="5"
                                    id="w5" />
                                <label class="form-check-label" for="w5">FIFTH</label>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="col-sm-12" id="table_days" style="display: none;">
                    <tr>
                        <td><label style="color: rgb(244, 132, 32)"><b>Days</b></label></td>

                        <?php for ($i = 1; $i < 16; $i++) { ?>
                        <td>
                            <div class="form-check">
                                <input name="day-checkbox[]" type="checkbox" class="form-check-input ace"
                                    value="<?= $i ?>" id="tgl<?= $i ?>" />
                                <label class="form-check-label" for="tgl<?= $i ?>"><?= $i ?></label>
                            </div>
                        </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td></td>

                        <?php for ($i = 16; $i < 32; $i++) { ?>
                        <td>
                            <div class="form-check">
                                <input name="day-checkbox[]" type="checkbox" class="form-check-input ace"
                                    value="<?= $i ?>" id="tgl<?= $i ?>" />
                                <label class="form-check-label" for="tgl<?= $i ?>"><?= $i ?></label>
                            </div>
                        </td>
                        <?php } ?>
                    </tr>
                </table>
                <table class="col-sm-12" id="table_week_days" style="display: none;">
                    <tr>
                        <td><label style="color: rgb(244, 132, 32)"><b>Week Days</b></label></td>
                        <td>
                            <div class="form-check">
                                <input name="weekday-checkbox[]" type="checkbox" class="form-check-input ace" value="1"
                                    id="SUNDAY" />
                                <label class="form-check-label" for="SUNDAY">SUNDAY</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weekday-checkbox[]" type="checkbox" class="form-check-input ace" value="2"
                                    id="MONDAY" />
                                <label class="form-check-label" for="MONDAY">MONDAY</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weekday-checkbox[]" type="checkbox" class="form-check-input ace" value="3"
                                    id="TUESDAY" />
                                <label class="form-check-label" for="TUESDAY">TUESDAY</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weekday-checkbox[]" type="checkbox" class="form-check-input ace" value="4"
                                    id="WEDNESDAY" />
                                <label class="form-check-label" for="WEDNESDAY">WEDNESDAY</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weekday-checkbox[]" type="checkbox" class="form-check-input ace" value="5"
                                    id="THURSDAY" />
                                <label class="form-check-label" for="THURSDAY">THURSDAY</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weekday-checkbox[]" type="checkbox" class="form-check-input ace" value="6"
                                    id="FRIDAY" />
                                <label class="form-check-label" for="FRIDAY">FRIDAY</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input name="weekday-checkbox[]" type="checkbox" class="form-check-input ace" value="7"
                                    id="SATURDAY" />
                                <label class="form-check-label" for="SATURDAY">SATURDAY</label>
                            </div>
                        </td>
                    </tr>
                </table>


            </div>
            <div class="mb-3 ">
                <label for="effectiveDate" class="fs-6 text-capitalize">Effective Date</label>
                <input type="text" id="effectiveDate" name="effectiveDate"
                    class="form-control form-control-sm mandatory" value="<?= $effective_date; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="expiredDate" class="fs-6 text-capitalize">Expires Date</label>
                <input type="text" id="expiredDate" name="expiredDate" class="form-control form-control-sm mandatory"
                    value="<?= $class_expired_date; ?>" />
            </div>
            <div class="mb-3">
                <label for="opt-allocation-type" class="fs-6 text-capitalize">Allocation Type</label>
                <?php
                    $allocation_type = array(
                        'PERMANEN' => 'Permanen',
                        'RECALL' => 'Recall',
                        'TEMPORER' => 'Temporer',
                    );
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-allocation-type" name="opt-allocation-type" data-placeholder="-Please Select Data-"';
                    echo form_dropdown('opt-allocation-type', $allocation_type,  $valueAllocationType, $attributes);
                ?>
            </div>
            <div class="mb-3">
                <label for="opt-assignment_duration" class="fs-6 text-capitalize">Assigment Duration</label>
                <div class="row">
                    <div class="col-5">
                        <?php
                            $opt_assignment_duration = array(
                                'DAY' => 'IN DAYS',
                                'WEEK' => 'IN WEEKS',
                                'MONTH' => 'IN MONTHS',
                                'INDEFINITE' => 'INDEFINITE'
                            );
                            $attributes = 'class="form-control form-control-sm mandatory" id="opt-assignment_duration" name="opt-assignment_duration" data-placeholder="-Please Select Data-"';
                            echo form_dropdown('opt-assignment_duration', $opt_assignment_duration,  $valueAssignmentDuration, $attributes);
                        ?>

                    </div>
                    <div class="col-auto">
                        <label for="expired_time" class="fs-6 text-capitalize right">Expires On</label>

                    </div>
                    <div class="col-5">
                        <input type="text" name="expired_time" id="expired_time"
                            class="form-control form-control-sm mandatory" value="<?= $expired_time; ?>">
                    </div>
                </div>
            </div>
            <div class="mb-3 d-flex justify-content-center">
                <button type="button" class="btn btn-danger btn-sm" id="btn-filter-reset">
                    <i class="bi bi-x-circle"></i> Reset Filter
                </button>
            </div>

        </div>
    </div>

</form>
<script type="text/javascript">
    // $('#form-template-sms').hide();
    // $('#form-template-email').hide();
    // $('#form-template-wa').hide();
    var daftar_filter = <?= $daftar_filter ?> ;
    var rules_edit = <?= $classification_json ?> ;
    var jsonAG = <?= json_encode($account_group ? : []); ?> ;
    var update_json = <?= $update_detail_json; ?> ;
    var job_schedule = '<?= $job_schedule; ?>';
</script>
<script src="<?= base_url(); ?>modules/class_management/js/class_management_edit.js?v=<?= rand() ?>"></script>