<?php
    date_default_timezone_set('Europe/Berlin');
	include_once 'updateGeoip.class';
	
	$update = new updateGeoip();
	$update->download();
	$update->unzip();
	$update->parseCSV();
	$update->updateDatabase();
	
?>
