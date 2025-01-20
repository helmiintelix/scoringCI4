<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="campaignid" class="fs-6 text-capitalize">Class ID</label>
                <input type="text" id="campaignid" name="campaignid" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3 ">
                <label for="description" class="fs-6 text-capitalize">Description</label>
                <input type="text" id="description" name="description" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-9">
                        <label for="opt_field_list" class="fs-6 text-capitalize">List Field</label>
                        <select class="form-control form-control-sm mandatory" name="opt_field_list"
                            id="opt_field_list">
                            <option value="#CR_NAME_1#">Customer Name</option>
                            <option value="#CM_CARD_NMBR#">Loan</option>
                            <option value="#CM_DTE_PYMT_DUE#">Due Date </option>
                            <option value="#CM_INSTALLMENT_AMOUNT#">Overdue Installment </option>
                            <option value="#CM_RTL_MISC_FEES#">Late Charge</option>
                            <option value="#STAMP_DUTY#">Admin Fee</option>
                            <option value="#CM_CURR_DUE#">Total Billing Current</option>
                            <option value="#CM_AMOUNT_DUE#">Total Billing Due</option>
                            <option value="#CM_INSTALLMENT_NO#">Installment No </option>
                            <option value="#CM_TENOR#">Tenor</option>
                            <option value="#DPD#">DPD</option>
                            <option value="#CM_OS_BALANCE#">Outstanding Balance</option>
                            <option value="#CM_AMOUNT_DUE#">Minimum Payment</option>
                            <option value="#CM_LST_PYMT_AMNT#">Last Payment Amount </option>
                            <option value="#CM_DTE_LST_PYMT#">Last Payment Date </option>
                            <option value="#payment_flag#">Payment Status </option>
                            <option value="#CM_INSTALLMENT_AMOUNT#">Instalment Amount </option>
                            <option value="#CM_TYPE#">Product</option>
                            <option value="#ptp_date#">PTP Date </option>
                            <option value="#ptp_amount#">PTP Amount </option>
                            <option value="#escalate_date#">Escalate Date </option>
                            <option value="#escalate_by#">Escalate By </option>
                            <option value="#remark_escalate#">Remark escalate </option>
                            <option value="#send_date#">Send Date </option>
                            <option value="#CM_DTE_LIQUIDATE#">Tanggal Pencairan </option>
                            <option value="#CM_CRLIMIT#">Jumlah Pencairan </option>
                            <option value="#CM_CARD_EXPIR_DTE#">Tanggal Berakhir </option>
                            <option value="#mailing#">Mailing </option>
                            <option value="#CM_CYCLE#">Cycle </option>
                        </select>
                    </div>
                    <div class="col-3 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-xs btn-outline-danger" id="btnAddField"
                            onclick="addField();return false;"><i class="bi bi-journals"></i> Add Field</button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="call_script" class="fs-6 text-capitalize">Call script</label>
                <textarea class="form-control form-control-sm mandatory" name="call_script" id="call_script" cols="30"
                    rows="5" required></textarea>
            </div>

            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Voice Detail</label>
                <div class="col-sm-12" id='query_builder'></div>
            </div>
            <div class="mb-3 ">
                <label for="days" class="fs-6 text-capitalize">Days</label>
                <?php
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="days[]" name="days[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('days[]', $days,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="holiday" class="fs-6 text-capitalize">Include Holiday</label>
                <?php
				$options = array(
					""	=> "-PLEASE SELECT-",
                    "Y" => "Yes",
                    "N" => "No"
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="holiday" data-placeholder ="[select]" required';
				echo form_dropdown('holiday', $options, '', $attributes);
				?>
            </div>
            <div class="mb-3">
                <label for="times" class="fs-6 text-capitalize">Start Time</label>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <select class="form-control form-control-sm mandatory" name="start_time1" id="start_time1"
                                required>
                                <option selected>[select]</option>
                                <option value='00'>00</option>
                                <option value='01'>01</option>
                                <option value='02'>02</option>
                                <option value='03'>03</option>
                                <option value='04'>04</option>
                                <option value='05'>05</option>
                                <option value='06'>06</option>
                                <option value='07'>07</option>
                                <option value='08'>08</option>
                                <option value='09'>09</option>
                                <option value='10'>10</option>
                                <option value='11'>11</option>
                                <option value='12'>12</option>
                                <option value='13'>13</option>
                                <option value='14'>14</option>
                                <option value='15'>15</option>
                                <option value='16'>16</option>
                                <option value='17'>17</option>
                                <option value='18'>18</option>
                                <option value='19'>19</option>
                                <option value='20'>20</option>
                                <option value='21'>21</option>
                                <option value='22'>22</option>
                                <option value='23'>23</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-control form-control-sm mandatory" name="start_time2" id="start_time2">
                                <option selected>[select]</option>
                                <option value='00'>00</option>
                                <option value='15'>15</option>
                                <option value='30'>30</option>
                                <option value='45'>45</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="times2" class="fs-6 text-capitalize">End Time</label>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <select class="form-control form-control-sm mandatory" name="end_time1" id="end_time1"
                                required>
                                <option selected>[select]</option>
                                <option value='00'>00</option>
                                <option value='01'>01</option>
                                <option value='02'>02</option>
                                <option value='03'>03</option>
                                <option value='04'>04</option>
                                <option value='05'>05</option>
                                <option value='06'>06</option>
                                <option value='07'>07</option>
                                <option value='08'>08</option>
                                <option value='09'>09</option>
                                <option value='10'>10</option>
                                <option value='11'>11</option>
                                <option value='12'>12</option>
                                <option value='13'>13</option>
                                <option value='14'>14</option>
                                <option value='15'>15</option>
                                <option value='16'>16</option>
                                <option value='17'>17</option>
                                <option value='18'>18</option>
                                <option value='19'>19</option>
                                <option value='20'>20</option>
                                <option value='21'>21</option>
                                <option value='22'>22</option>
                                <option value='23'>23</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-control form-control-sm mandatory" name="end_time2" id="end_time2">
                                <option selected>[select]</option>
                                <option value='00'>00</option>
                                <option value='15'>15</option>
                                <option value='30'>30</option>
                                <option value='45'>45</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="max_retry" class="fs-6 text-capitalize">Max Retry</label>
                <div class="d-flex align-items-center">
                    <input type="text" id="max_retry" name="max_retry"
                        class="form-control form-control-sm mandatory mr-1" required />
                    <div class="help-block" style="color: blue;"> TIMES</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="call_timeout" class="fs-6 text-capitalize">Call Timeout</label>
                <div class="d-flex align-items-center">
                    <input type="text" id="call_timeout" name="call_timeout"
                        class="form-control form-control-sm mandatory mr-1" required />
                    <div class="help-block" style="color: blue;"> SECOND</div>
                </div>
            </div>
            <div class="mb-3">
                <label for="next_dial" class="fs-6 text-capitalize">Next Dial</label>
                <div class="d-flex align-items-center">
                    <input type="text" id="next_dial" name="next_dial"
                        class="form-control form-control-sm mandatory mr-1" required />
                    <div class="help-block" style="color: blue;"> SECOND</div>
                </div>
            </div>
            <div class="mb-3 ">
                <label for="priority" class="fs-6 text-capitalize">Priority</label>
                <?php
				$priority = array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9");
				$attributes = 'class="form-control form-control-sm  mandatory" id="priority" data-placeholder ="[select]" required';
				echo form_dropdown('priority', $priority, '', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="voice_type" class="fs-6 text-capitalize">Voice Type</label>
                <?php
				$options = array(
                    ""	=> "-PLEASE SELECT-",
                    "MALE" => "MALE",
                    "FEMALE" => "FEMALE"
                );
				$attributes = 'class="form-control form-control-sm  mandatory" id="voice_type" data-placeholder ="[select]" required';
				echo form_dropdown('voice_type', $options, '', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="phone_priority" class="fs-6 text-capitalize">Phone Priority</label>
                <?php
				$options = array(
                    "HP1" => "HANDPHONE 1",
                    "HP2" => "HANDPHONE 2",
                    "EC" => "EMERGENCY CONTACT"
                );
				$attributes = 'class="chosen-select form-control form-control-sm mandatory" id="phone_priority" multiple data-placeholder ="[select]" required';
				echo form_dropdown('phone_priority[]', $options, '', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" checked>
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
<script src="<?= base_url(); ?>modules/setup_voice_blast/js/script_add_form.js?v=<?= rand() ?>"></script>