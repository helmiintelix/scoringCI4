<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>eCentrix Collection Management System</title>

		<meta name="description" content="Common UI Features &amp; Elements" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<style>
		

		</style>


	</head>
	
	<body>
	<div id="map" class="map"></div>
	</body>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?=$gmapApiKey?>&libraries=drawing&callback=initMap" async defer></script>
	<script
        src="<?= base_url(); ?>modules/report_visit_luar_radius_geofencing/js/tracking_history_gmaps.js?v=<?= rand() ?>">
    </script>
	<script type="text/javascript">
        var loc=<?=$history?>.length;
        var pos=<?=$history?>;
	</script>	

</html>