<?
  $var_redy=json_decode($redy);
  $var_pending=json_decode($pending);
  $var_sent=json_decode($sent);
  $var_read=json_decode($read);
  $var_valid=json_decode($valid);
  $var_invalid=json_decode($invalid);
  $var_void=json_decode($void);
  $var_expired=json_decode($expired);
  $var_reject=json_decode($reject);

  $total_status = $var_redy+$var_pending+$var_sent+$var_read+$var_valid+$var_invalid+$var_void+$var_expired;
  $var_redy_percent =round(($var_redy / $total_status) * 100, 2);
  $var_pending_percent =round(($var_pending / $total_status) * 100, 2);
  $var_sent_percent =round(($var_sent / $total_status) * 100, 2);
  $var_read_percent =round(($var_read / $total_status) * 100, 2);
  $var_valid_percent =round(($var_valid / $total_status) * 100, 2);
  $var_invalid_percent =round(($var_invalid / $total_status) * 100, 2);
  $var_void_percent =round(($var_void / $total_status) * 100, 2);
  $var_var_expired =round(($var_expired / $total_status) * 100, 2);
?>
<div class="row">
	<div class="col-6">
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">
					Status Ready
				</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_redy?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_redy_percent.'%'?>;"
						aria-valuenow="<? echo $var_redy_percent?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_redy_percent.'%'?>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">
					Status Invalid
				</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_invalid?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_invalid_percent.'%'?>;"
						aria-valuenow="<? echo $var_invalid_percent?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_invalid_percent.'%'?>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">
					Status Sent
				</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_sent?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_sent_percent.'%'?>;"
						aria-valuenow="<? echo $var_sent_percent?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_sent_percent.'%'?>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">
					Status Void
				</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_void?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_void_percent.'%'?>;"
						aria-valuenow="<? echo $var_void_percent?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_void_percent.'%'?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-6">
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">Status Valid</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_valid?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_valid_percent.'%'?>;"
						aria-valuenow="<? echo $var_valid_percent?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_valid_percent.'%'?>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">Status Pending</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_pending?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_pending_percent.'%'?>;"
						aria-valuenow="<? echo $var_pending_percent?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_pending_percent.'%'?>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">Status Read</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_read?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_read_percent.'%'?>;"
						aria-valuenow="<? echo $var_read_percent?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_read_percent.'%'?>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<div class="card-title text-center">Status Expired</div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<? echo $var_expired?>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: <? echo $var_var_expired.'%'?>;"
						aria-valuenow="<? echo $var_var_expired?>" aria-valuemin="0" aria-valuemax="100">
						<? echo $var_var_expired.'%'?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var classification = '<?= $classification ?>';
</script>