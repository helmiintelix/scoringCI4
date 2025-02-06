<div class="container py-4">
	<form>
        <div class="row">
            <!-- Left Side -->
            <div class="col-md-3">
               

                    <div class="mb-3">
                        <label for="label_class_name" class="form-label">Class Name</label>
                        <div id="label_class_name" name="label_class_name" class="form-text"><?= $dialing_mode['name'] ?></div>
						<input type="hidden" id="txt-upload-file" name="classification_name" class="col-xs-10 col-sm-8 form-control" value="<?= $dialing_mode['name'] ?>" readonly />
                        <input type="hidden" id="class_id" value="<?= $dialing_mode['class_id'] ?>" name="class_id">
                    </div>

                    <div class="mb-3">
                        <label for="can_call_select" class="form-label">Can Call</label>
						<?
							$option = array(
								'0' => 'No',
								'1' => 'Yes'
							);

							$attributes = 'class="form-control mandatory" id="opt_call"';
							echo form_dropdown('can_call_select', $option, $dialing_mode["can_call"], $attributes);
						?>
                    </div>

                    <div class="mb-3">
                        <label for="dialing_select" class="form-label">Dialing Mode</label>
						<?
							$attributes = 'class="form-control mandatory" id="opt_mode"';
							echo form_dropdown('dialing_select', $mode, $dialing_mode['dialing_mode_id'], $attributes);
							?>
                    </div>
					
                    <div class="mb-3">
						<label for="process_select" class="form-label">Contract Process Time</label>
                        
							<?
								$attributes = 'class="form-select mandatory" id="process_select"';
								echo form_dropdown('process_select', $contract_process_time, $dialing_mode['contract_process_time'], $attributes);
                        	?>
                    </div>
					<div class="mb-3">
                        <label for="busy_callback_select" class="form-label">"Busy" Call Back</label>
						<?
								$attributes = 'class="form-select mandatory" id="busy_callback_select"';
								echo form_dropdown('busy_callback_select', $busy_callback, $dialing_mode['busy_call_back'], $attributes);
                        ?>
                    </div>

                    <div class="mb-3">
                        <label for="left_message_callback_select" class="form-label">"Left Message" Call Back</label>
						<?
								$attributes = 'class="form-select mandatory" id="left_message_callback_select"';
								echo form_dropdown('left_message_callback_select', $busy_callback, $dialing_mode['left_message_call_back'], $attributes);
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="autodisconect_select" class="form-label">Auto Disconect(Sec)</label>
						<?
								$attributes = 'class="form-select mandatory" id="autodisconect_select"';
								echo form_dropdown('autodisconect_select', $auto_disconect,  $dialing_mode['auto_disconect'], $attributes);
                        ?>
                    </div>
					<div class="mb-3">
                        <label for="noanswer_callback_select" class="form-label">"No Answer" Call Back</label>
						<?
								$attributes = 'class="form-select mandatory" id="noanswer_callback_select"';
								echo form_dropdown('noanswer_callback_select', $busy_callback, $dialing_mode['no_ans_call_back'], $attributes);
                        ?>
                    </div>
					<div class="mb-3">
                        <label for="ddl_select" class="form-label">Daily Dial Limiter</label>
						<?
							$attributes = 'class="form-select mandatory" id="ddl_select"';
							echo form_dropdown('ddl_select', $dial_limiter, $dialing_mode['daily_dial_limiter'], $attributes);
                        ?>
                    </div>
                
            </div>

            <!-- Middle Side -->
            <div class="col-md-3">
                
					<div class="mb-3">
                        <label for="maxptp_select" class="form-label">Max PTP Days</label>
						<?
							$attributes = 'class="form-select mandatory" id="maxptp_select"';
							echo form_dropdown('maxptp_select', $max_ptp_days, $dialing_mode['max_ptp_days'], $attributes);
                        ?>
                    </div>
					<div class="mb-3">
                        <label for="maxptp_select" class="form-label">Max Visit Req</label>
						<?
							$attributes = 'class="form-select mandatory" id="maxvisit_select"';
							echo form_dropdown('maxvisit_select', $max_visit, $dialing_mode['max_req_visit'], $attributes);
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="call_timeout" class="form-label">Call Timeout (Minutes)</label>
                        <input type="text" class="form-control" id="call_timeout" name="call_timeout" value="<?= $dialing_mode['call_timeout'] ?>" placeholder="Enter timeout" onkeypress="return isNumberKey(event)">
                    </div>

                    <div class="mb-3">
                        <label for="formula_factor" class="form-label">Formula Factor</label>
                        <input type="text" class="form-control" id="formula_factor" name="formula_factor" placeholder="Enter formula factor" value="<?= $dialing_mode['formula_factor'] ?>" onkeypress="return isNumberKey(event, true)">
                    </div>
					<div class="mb-3">
                        <label for="max_call_attempt" class="form-label">Max Call Attempt</label>
                        <input type="text" class="form-control" id="max_call_attempt" name="max_call_attempt" value="<?= $dialing_mode['max_call_attempt'] ?>" placeholder="Enter max attempts" onkeypress="return isNumberKey(event)">
                    </div>
					<div class="mb-3">
                        <label for="ecentrix_group" class="form-label">Ecentrix Group</label>
                        <?
							$attributes = 'class="form-select mandatory" id="ecentrix_group"';
							echo form_dropdown('ecentrix_group', $ecentrix_group, $dialing_mode['ecentrix_group'], $attributes);
                        ?>
                    </div>
                
            </div>

            <!-- Right Side -->
            <div class="col-md-3">
                
					<div class="mb-3">
                        <label for="call_priority_1" class="form-label">Phone Priority 1</label>
						<?
							$list = array(
													//'0' => '[Not Active]',
													'1' => 'Mobile #1',
													'2' => 'Mobile #2',
													'3' => 'Mobile #3',
													'4' => 'Home #1',
													'5' => 'Home #2',
													'6' => 'Home #3',
													'7' => 'Office #1',
													'8' => 'Office #2',
													'9' => 'Office #3',
													'10' => 'Emergency Phone #1',
													'11' => 'Emergency Phone #2',
													'12' => 'Emergency Phone #3',
													'13' => 'Legal Phone #1',
													'14' => 'Legal Phone #2',
													'15' => 'Legal Phone #3',
													'16' => 'Mailing Phone #1',
													'17' => 'Mailing Phone #2',
													'18' => 'Mailing Phone #3'
											);
							$attributes = 'class="form-select mandatory" id="call_priority_1"';
							echo form_dropdown('call_priority_1', $list, $dialing_mode['call_priority_1'], $attributes);
						?>
                    </div>

                    <div class="mb-3">
                        <label for="call_priority_2" class="form-label">Phone Priority 2</label>
						<?
							$attributes = 'class="form-select" id="call_priority_2"';
							echo form_dropdown('call_priority_2', $list, $dialing_mode['call_priority_2'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_3" class="form-label">Phone Priority 3</label>
						<?
							$attributes = 'class="form-select" id="call_priority_3"';
							echo form_dropdown('call_priority_3', $list, $dialing_mode['call_priority_3'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_4" class="form-label">Phone Priority 4</label>
						<?
							$attributes = 'class="form-select" id="call_priority_4"';
							echo form_dropdown('call_priority_4', $list, $dialing_mode['call_priority_4'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_5" class="form-label">Phone Priority 5</label>
						<?
							$attributes = 'class="form-select" id="call_priority_5"';
							echo form_dropdown('call_priority_5', $list, $dialing_mode['call_priority_5'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_6" class="form-label">Phone Priority 6</label>
						<?
							$attributes = 'class="form-select" id="call_priority_6"';
							echo form_dropdown('call_priority_6', $list, $dialing_mode['call_priority_6'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_7" class="form-label">Phone Priority 7</label>
						<?
							$attributes = 'class="form-select" id="call_priority_7"';
							echo form_dropdown('call_priority_7', $list, $dialing_mode['call_priority_7'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_8" class="form-label">Phone Priority 8</label>
						<?
							$attributes = 'class="form-select" id="call_priority_8"';
							echo form_dropdown('call_priority_8', $list, $dialing_mode['call_priority_8'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_9" class="form-label">Phone Priority 9</label>
						<?
							$attributes = 'class="form-select" id="call_priority_9"';
							echo form_dropdown('call_priority_9', $list, $dialing_mode['call_priority_9'], $attributes);
						?>
                    </div>
                
            </div>
			<div class="col-md-3">
				
				<div class="mb-3">
                        <label for="call_priority_10" class="form-label">Phone Priority 10</label>
						<?
							$attributes = 'class="form-select" id="call_priority_10"';
							echo form_dropdown('call_priority_10', $list, $dialing_mode['call_priority_10'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_11" class="form-label">Phone Priority 11</label>
						<?
							$attributes = 'class="form-select" id="call_priority_11"';
							echo form_dropdown('call_priority_11', $list, $dialing_mode['call_priority_11'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_12" class="form-label">Phone Priority 12</label>
						<?
							$attributes = 'class="form-select" id="call_priority_12"';
							echo form_dropdown('call_priority_12', $list, $dialing_mode['call_priority_12'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_13" class="form-label">Phone Priority 13</label>
						<?
							$attributes = 'class="form-select" id="call_priority_13"';
							echo form_dropdown('call_priority_13', $list, $dialing_mode['call_priority_13'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_14" class="form-label">Phone Priority 14</label>
						<?
							$attributes = 'class="form-select" id="call_priority_14"';
							echo form_dropdown('call_priority_14', $list, $dialing_mode['call_priority_14'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_15" class="form-label">Phone Priority 15</label>
						<?
							$attributes = 'class="form-select" id="call_priority_15"';
							echo form_dropdown('call_priority_15', $list, $dialing_mode['call_priority_15'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_16" class="form-label">Phone Priority 16</label>
						<?
							$attributes = 'class="form-select" id="call_priority_16"';
							echo form_dropdown('call_priority_16', $list, $dialing_mode['call_priority_16'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_17" class="form-label">Phone Priority 17</label>
						<?
							$attributes = 'class="form-select" id="call_priority_17"';
							echo form_dropdown('call_priority_17', $list, $dialing_mode['call_priority_17'], $attributes);
						?>
                    </div>
					<div class="mb-3">
                        <label for="call_priority_18" class="form-label">Phone Priority 18</label>
						<?
							$attributes = 'class="form-select" id="call_priority_18"';
							echo form_dropdown('call_priority_18', $list, $dialing_mode['call_priority_18'], $attributes);
						?>
                    </div>
				
        	</div>
        </div>
	</form>
</div>

    <script>
        // Function to allow numbers only
        function isNumberKey(evt, allowDecimal = false) {
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (allowDecimal && charCode === 46) return true;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
            return true;
        }
    </script>