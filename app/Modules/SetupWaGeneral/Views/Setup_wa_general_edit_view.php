<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<input type="hidden" name="id" id ="id" value="<?= $data_template['id'] ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3">
				<label for="txt_tot_agent_message" class="fs-6 text-capitalize">Attempt Send Message / Agent</label>
				<input type="number" id="txt_tot_agent_message" name="txt_tot_agent_message" class="form-control form-control-sm mandatory" value="<?= $data_template['attemp_per_agent'] ?>" required/>
			</div>
			<div class="mb-3">
				<label for="txt_tot_customer_message" class="fs-6 text-capitalize">Attempt Send Message / Customer</label>
				<input type="number" id="txt_tot_customer_message" name="txt_tot_customer_message" class="form-control form-control-sm mandatory" value="<?= $data_template['attemp_per_cust'] ?>" required/>
			</div>
			<div class="mb-3 row">
				<div class="col">
					<label for="txt_outgoing_from" class="fs-6 text-capitalize">Wa Outgoing Time From</label>
					<input style="width: 100%;" type="time" id="txt_outgoing_from" name="txt_outgoing_from" class="form-control form-control-sm mandatory" value="<?= $data_template['from_wa_outgoing'] ?>" required/>
				</div>
				<div class="col">
					<label for="txt_outgoing_to" class="fs-6 text-capitalize">Wa Outgoing Time To</label>
					<input style="width: 100%;" type="time" id="txt_outgoing_to" name="txt_outgoing_to" class="form-control form-control-sm mandatory" value="<?= $data_template['to_wa_outgoing'] ?>" required/>
				</div>
			</div>
			<div class="mb-3 row">
				<div class="col">
					<label for="txt_office_hour_from" class="fs-6 text-capitalize">Office Hour Time From</label>
					<input style="width: 100%;" type="time" id="txt_office_hour_from" name="txt_office_hour_from" class="form-control form-control-sm mandatory" value="<?= $data_template['from_office_hour'] ?>" required/>
				</div>
				<div class="col">
					<label for="txt_office_hour_to" class="fs-6 text-capitalize">Office Hour Time To</label>
					<input style="width: 100%;" type="time" id="txt_office_hour_to" name="txt_office_hour_to" class="form-control form-control-sm mandatory" value="<?= $data_template['to_office_hour'] ?>" required/>
				</div>
			</div>
			<div class="mb-3">
	            <label for="txt_wa_template_template_design" class="fs-6 text-capitalize">After Office Hour Notification</label>
	            <textarea id="txt_wa_template_template_design" name="txt_wa_template_template_design" class="form-control form-control-sm mandatory" rows="4" required><?= $data_template['holiday_content']?></textarea>
	        </div>
		</div>
	</div>
	
</form>
<script src="<?= base_url(); ?>modules/setup_wa_general/js/setup_wa_general_edit.js?v=<?= rand() ?>"></script>