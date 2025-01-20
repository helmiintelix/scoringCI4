<div class="row">
	<div class="col col-sm-12">
		<div class="input-group mb-1">
	        <input type="hidden" name="<?= csrf_token() ?>" id="token_csrf" value="<?= csrf_hash() ?>" />
			<input type="hidden" name="id" id="id" value="<?=$id?>" />
			<span class="input-group-text widthLabel" id="txt_deviation_reason"> assign to TL</span>
			<?
                $attributes = 'class="form-control" id="team_leader" ';
                echo form_dropdown('team_leader', $tl_list, "", $attributes);
            ?>
		</div>
	</div>
</div>