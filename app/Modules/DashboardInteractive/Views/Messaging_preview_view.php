<form class="form-horizontal" id="messaging_preview" name="messaging_preview" method="POST">
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="mb-3 row">
						<label for="from" class="col-sm-3 col-form-label profile-info-name">From:</label>
						<div class="col-sm-9 profile-info-value">
							<input type="text" class="form-control" id="from" name="from" value="<?=$from ?>" readonly>
						</div>
					</div>
					<div class="mb-3 row">
						<label for="to" class="col-sm-3 col-form-label profile-info-name">To:</label>
						<div class="col-sm-9 profile-info-value">
							<input type="text" class="form-control" id="to" name="to" value="<?=$to ?>">
						</div>
					</div>
					<div class="mb-3 row">
						<label for="message" class="col-sm-3 col-form-label profile-info-name">Message:</label>
						<div class="col-sm-9 profile-info-value">
							<?=$template ?>
							<textarea class="form-control" style="width: 100%; height: 400px; display: none;"
								id="message" name="message" readonly><?=$template ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>