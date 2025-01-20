<form role="form" class="form-horizontal" id="form_upload" name="form_upload" enctype="multipart/form-data" method="post">
	<input type="hidden" name="class_id" value="<?= $dialing_mode['class_id'] ?>">
	<div class="form-group">
		<label class="control-label no-padding-right"> Class Name </label>

		<div class="col-sm-9">
			<input type="text" id="txt-upload-file" name="classification_name" class="col-xs-10 col-sm-8 form-control" value="<?= $dialing_mode['classification_name'] ?>" readonly />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Can Call</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'0' => 'No',
				'1' => 'Yes'
			);

			$attributes = 'class="form-control mandatory" id="opt_call"';
			echo form_dropdown('can_call_select', $option, $dialing_mode["can_call"], $attributes);
			?>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Mode</label>

		<div class="col-sm-9">
			<?

			$attributes = 'class="form-control mandatory" id="opt_mode"';
			echo form_dropdown('dialing_select', $mode, $dialing_mode['dialing_mode_id'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Formula Factor </label>

		<div class="col-sm-9">
			<input type="number" name="formula_factor" class="col-xs-10 col-sm-8 form-control" value="<?= $dialing_mode['formula_factor'] ?>" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Timeout </label>

		<div class="col-sm-9">
			<input type="number" name="call_timeout" class="col-xs-10 col-sm-8 form-control" value="<?= $dialing_mode['call_timeout'] ?>" />
		</div>
	</div>
	<!-- <div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Try Again After </label>

		<div class="col-sm-9">
			<input type="number" name="try_again_after" class="col-xs-10 col-sm-8 form-control" value="<?= $dialing_mode['try_again_after'] ?>" />
		</div>
	</div> -->
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Max Call Attemp per Day </label>

		<div class="col-sm-9">
			<input type="number" name="max_call_attempt" class="col-xs-10 col-sm-8 form-control" value="<?= $dialing_mode['max_call_attempt'] ?>" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 1</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_1"';
			echo form_dropdown('call_priority_1', $option, $dialing_mode['call_priority_1'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 2</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_2"';
			echo form_dropdown('call_priority_2', $option, $dialing_mode['call_priority_2'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 3</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_3"';
			echo form_dropdown('call_priority_3', $option, $dialing_mode['call_priority_3'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 4</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_4"';
			echo form_dropdown('call_priority_4', $option, $dialing_mode['call_priority_4'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 5</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_5"';
			echo form_dropdown('call_priority_5', $option, $dialing_mode['call_priority_5'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 6</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_6"';
			echo form_dropdown('call_priority_6', $option, $dialing_mode['call_priority_6'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 7</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_7"';
			echo form_dropdown('call_priority_7', $option, $dialing_mode['call_priority_7'], $attributes);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right"> Call Priority 8</label>

		<div class="col-sm-9">
			<?
			$option = array(
				'1' => 'Handphone',
				'2' => 'Handphone 2',
				'3' => 'Home Phone',
				'4' => 'Home Phone 2',
				'5' => 'Office Phone',
				'6' => 'Office Phone 2'
			);

			$attributes = 'class="form-control mandatory" id="call_priority_8"';
			echo form_dropdown('call_priority_8', $option, $dialing_mode['call_priority_8'], $attributes);
			?>
		</div>
	</div>
</form>
