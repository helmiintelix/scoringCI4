<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3">
                <div class="row">
                    <div class="col-9">
                        <label for="opt_field_list" class="fs-6 text-capitalize">List Field</label>
                        <select class="form-control form-control-sm mandatory" name="opt_field_list"
                            id="opt_field_list">
                            <option value="[[CR_NAME_1]]">Customer Name</option>
                            <option value="[[CM_CARD_NMBR]]">Loan</option>
                            <option value="[[CM_DTE_PYMT_DUE]]">Due Date </option>
                            <option value="[[CM_INSTALLMENT_AMOUNT]]">Overdue Installment </option>
                            <option value="[[CM_RTL_MISC_FEES]]">Late Charge</option>
                            <option value="[[STAMP_DUTY]]">Admin Fee</option>
                            <option value="[[CM_CURR_DUE]]">Total Billing Current</option>
                            <option value="[[CM_AMOUNT_DUE]]">Total Billing Due</option>
                            <option value="[[CM_INSTALLMENT_NO]]">Installment No </option>
                            <option value="[[CM_TENOR]]">Tenor</option>
                            <option value="[[DPD]]">DPD</option>
                            <option value="[[CM_OS_BALANCE]]">Outstanding Balance</option>
                            <option value="[[CM_AMOUNT_DUE]]">Minimum Payment</option>
                            <option value="[[CM_LST_PYMT_AMNT]]">Last Payment Amount </option>
                            <option value="[[CM_DTE_LST_PYMT]]">Last Payment Date </option>
                            <option value="[[payment_flag]]">Payment Status </option>
                            <option value="[[CM_INSTALLMENT_AMOUNT]]">Instalment Amount </option>
                            <option value="[[CM_TYPE]]">Product</option>
                            <option value="[[ptp_date]]">PTP Date </option>
                            <option value="[[ptp_amount]]">PTP Amount </option>
                            <option value="[[escalate_date]]">Escalate Date </option>
                            <option value="[[escalate_by]]">Escalate By </option>
                            <option value="[[remark_escalate]]">Remark escalate </option>
                            <option value="[[send_date]]">Send Date </option>
                            <option value="[[CM_DTE_LIQUIDATE]]">Tanggal Pencairan </option>
                            <option value="[[CM_CRLIMIT]]">Jumlah Pencairan </option>
                            <option value="[[CM_CARD_EXPIR_DTE]]">Tanggal Berakhir </option>
                            <option value="[[mailing]]">Mailing </option>
                            <option value="[[CM_CYCLE]]">Cycle </option>
                        </select>
                    </div>
                    <div class="col-3 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-xs btn-outline-danger" id="btnAddField"
                            onclick="addField();return false;"><i class="bi bi-journals"></i> Add Field</button>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <textarea maxlength="50" class="form-control form-control-sm mandatory" name="txt-template-content"
                    id="txt-template-content" style="height: 300px;"><?=$template_design?></textarea>
            </div>
            <div class="mb-3 ">
                <label for="txt-template-id" class="fs-6 text-capitalize">Template ID</label>
                <input type="text" id="txt-template-id" name="txt-template-id"
                    class="form-control form-control-sm mandatory" required value="<?=$data["template_id"]?>" />
            </div>
            <div class=" mb-3 ">
                <label for=" txt-template-name" class="fs-6 text-capitalize">Template Name</label>
                <input type="text" id="txt-template-name" name="txt-template-name"
                    class="form-control form-control-sm mandatory" required value="<?=$data["template_name"]?>" />
            </div>
            <div class=" mb-3 ">
                <label for=" opt-sentby" class="fs-6 text-capitalize">Sent By</label>
                <?php
				$options = array(
                    "Email" => "Email",
                    "SMS" => "SMS"
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-sentby" name="opt-sentby" data-placeholder ="[select]" required';
				echo form_dropdown('opt-sentby', $options, $data['sent_by'], $attributes);
				?>
            </div>
            <div class="mb-3" id="div-template-relation">
                <label for="opt-template-relation" class="fs-6 text-capitalize">Template Relation</label>
                <?php
				$options = array(
					""	=> "-PLEASE SELECT-",
                    "Request Billing dari Layar Agent Deskcoll" => "Request Billing dari Layar Agent Deskcoll",
                    "Flag Class Parameter" => "Flag Class Parameter"
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-template-relation" name="opt-template-relation" data-placeholder ="[select]"';
				echo form_dropdown('opt-template-relation', $options, $data['template_relation'], $attributes);
				?>
            </div>
            <input type="hidden" id="tmp_relation" name="tmp_relation" value="false">
            <div class="mb-3" id="div-recepient">
                <label for="opt-recipient" class="fs-6 text-capitalize">Recipient</label>
                <?php
				$options = array(
                    "Internal" => "Internal",
                    "Debitur" => "Debitur"
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-recipient" name="opt-recipient" data-placeholder ="[select]" required';
				echo form_dropdown('opt-recipient', $options, $data['recipient'], $attributes);
				?>
            </div>
            <div class="mb-3" id="rules_ptp">
                <label for="opt-rules" class="fs-6 text-capitalize">Rules</label>
                <?php
				$options = array(
                    "Before PTP" => "Before PTP",
                    "After PTP" => "After PTP",
                    "Before Due Date" => "Before Due Date",
                    "After Due Date" => "After Due Date"
				);
				$attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-rules" name="opt-rules[]"  multiple data-placeholder="-Please Select Product-"';
				echo form_dropdown('opt-rules[]', $options, $rules_list, $attributes);
				?>
            </div>
            <div class="mb-3" id="input_value_template">
                <label for="txt-template-input-value" class="fs-6 text-capitalize">Input Value</label>
                <input type="text" id="txt-template-input-value" name="txt-template-input-value"
                    class="form-control form-control-sm mandatory" value="<?= $data['input_value']; ?>" />
            </div>
            <div class="mb-3 hide" id="div-mechanism">
                <label for="txt-template-mechanism" class="fs-6 text-capitalize">Select Mechanism</label>
                <?php
                $options = array(
                    '' => 'None',
                    'DAILY' => 'Daily',
                    'WEEKLY' => 'Weekly',
                    'MONTHLY' => 'Monthly',
                );
                $attributes = 'class="form-control form-control-sm mandatory" id="opt-schedule" name="opt-schedule" data-placeholder="-Please Select Product-"';
                echo form_dropdown('opt-schedule', $options, $data['select_mechanism'], $attributes);
                ?>
                &nbsp;
                &nbsp;
                &nbsp;
                <div id='div_week' style='display:none;' class='form group col-xs-10 col-sm-12 well'>
                    <?
				$week = array(
					//						'' => '[select-data]',
					'0' => 'Sunday',
					'1' => 'Monday',
					'2' => 'Tuesday',
					'3' => 'Wednesday',
					'4' => 'Thursday',
					'5' => 'Friday',
					'6' => 'Saturday',

				);
				foreach ($week as $key => $value) {
					$data = array(
						'name'          => 'opt-week[]',
						'id'            => $key,
						'value'         => $key,
						'checked'       => false,
						'style'         => 'margin:10px'
					);
					echo form_checkbox($data);
					echo $value;
				}
				?>
                </div>
            </div>
            <div class="form-group border well " style='display:none;' id="div_monthly" style="margin:50px">
                <?
                $month = array(
                    "01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August",
                    "09" => "September", "10" => "October", "11" => "November", "12" => "December"
                );


                ?>
                <table id="table_month" class="col-sm-12 ">
                    <tr>
                        <td> <label class="green"><b> Month</b> </label> </td>
                        <? for ($i = 1; $i < 7; $i++) { ?>
                        <td>
                            <div class="checkbox">
                                <label>
                                    <input name="months-checkbox[]" type="checkbox"
                                        value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>"
                                        id="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>" class="ace" />
                                    <span class="lbl"> <?= $month[str_pad($i, 2, "0", STR_PAD_LEFT)] ?></span>
                                </label>
                            </div>
                        </td>



                        <? } ?>

                    </tr>
                    <tr>
                        <td> </td>
                        <? for ($i = 7; $i < 13; $i++) { ?>
                        <td>
                            <div class="checkbox">
                                <label>
                                    <input name="months-checkbox[]" type="checkbox"
                                        value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>"
                                        id="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>" class="ace" />
                                    <span class="lbl"> <?= $month[str_pad($i, 2, "0", STR_PAD_LEFT)] ?></span>
                                </label>
                            </div>
                        </td>



                        <? } ?>
                </table>
                <table class="col-sm-6 ">
                    <tr>
                        <td><label class="green"><b> Option</b> </label> </td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input name="month-option" type="radio" class="ace" id="FIRST_DAY"
                                        value="FIRST_DAY" />
                                    <span class="lbl"> First day</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input name="month-option" type="radio" class="ace" id="LAST_DAYS"
                                        value="LAST_DAYS" />
                                    <span class="lbl"> Last Day</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input name="month-option" type="radio" class="ace" id="DAYS" value="DAYS" />
                                    <span class="lbl"> Days</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input name="month-option" type="radio" class="ace" id="WEEK_DAYS"
                                        value="WEEK_DAYS" />
                                    <span class="lbl"> Week days</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="col-sm-8 " id="table_week" style='display:none;'>
                    <tr>
                        <td><label class="green"><b> Weeks</b> </label> </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weeks-checkbox[]" type="checkbox" class="ace" value="1" id="w1" />
                                    <span class="lbl"> FIRST</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weeks-checkbox[]" type="checkbox" class="ace" value="2" id="w2" />
                                    <span class="lbl"> SECOND</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weeks-checkbox[]" type="checkbox" class="ace" value="3" id="w3" />
                                    <span class="lbl"> THIRD</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weeks-checkbox[]" type="checkbox" class="ace" value="4" id="w4" />
                                    <span class="lbl"> FOURTH</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weeks-checkbox[]" type="checkbox" class="ace" value="5" id="w5" />
                                    <span class="lbl"> FIFTH</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="col-sm-12" id="table_days" style='display:none;'>
                    <tr>
                        <td><label class="green"><b> Days</b> </label> </td>

                        <? for ($i = 1; $i < 16; $i++) { ?>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="day-checkbox[]" type="checkbox" class="ace" value="<?= $i ?>"
                                        id="tgl<?= $i ?>" />
                                    <span class="lbl"> <?= $i ?></span>
                                </label>
                            </div>
                        </td>
                        <? } ?>
                    </tr>
                    <tr>
                        <td> </td>

                        <? for ($i = 16; $i < 32; $i++) { ?>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="day-checkbox[]" type="checkbox" class="ace" value="<?= $i ?>"
                                        id="tgl<?= $i ?>" />
                                    <span class="lbl"> <?= $i ?></span>
                                </label>
                            </div>
                        </td>
                        <? } ?>
                    </tr>
                </table>

                <table class="col-sm-12" id="table_week_days" style='display:none;'>
                    <tr>
                        <td><label class="green"><b> Week Days</b> </label> </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weekday-checkbox[]" type="checkbox" class="ace" value="7"
                                        id="SUNDAY" />
                                    <span class="lbl"> SUNDAY</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weekday-checkbox[]" type="checkbox" class="ace" value="1"
                                        id="MONDAY" />
                                    <span class="lbl"> MONDAY</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weekday-checkbox[]" type="checkbox" class="ace" value="2"
                                        id="TUESDAY" />
                                    <span class="lbl"> TUESDAY</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weekday-checkbox[]" type="checkbox" class="ace" value="3"
                                        id="WEDNESDAY" />
                                    <span class="lbl"> WEDNESDAY</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weekday-checkbox[]" type="checkbox" class="ace" value="4"
                                        id="THURSDAY" />
                                    <span class="lbl"> THURSDAY</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weekday-checkbox[]" type="checkbox" class="ace" value="5"
                                        id="FRIDAY" />
                                    <span class="lbl"> FRIDAY</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox ">
                                <label>
                                    <input name="weekday-checkbox[]" type="checkbox" class="ace" value="6"
                                        id="SATURDAY" />
                                    <span class="lbl"> SATURDAY</span>
                                </label>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>
            <div class="mb-3" id="div-select-times">
                <label for="txt-template-input-times" class="fs-6 text-capitalize">Select Times</label>
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-3">
                        <input type="time" id="txt-template-input-times1" name="txt-template-input-times[]"
                            class="form-control form-control-sm mandatory" />
                    </div>
                    <div class="col-9" style="display: flex; align-items: center;">
                        <i id="btn_add_times1" class="bi bi-plus-square btn_add_times"
                            style="cursor:pointer;font-size:30px; margin-left:8px;"></i>
                    </div>
                </div>
            </div>
            <div id="dynamic_field">
            </div>

            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" <?= $is_active=='1'?  'checked' :  ''; ?>>
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
           
            <input type="hidden" id="content" name="content">

        </div>
    </div>

</form>
<script type="text/javascript">
    let select_time = '<?= $select_time; ?>'
</script>
<script src="<?= base_url(); ?>modules/email_sms_template/js/script_edit_form.js?v=<?= rand() ?>"></script>