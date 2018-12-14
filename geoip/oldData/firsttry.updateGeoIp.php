<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set('mysql.connect_timeout', 10000);
$delete = "DROP TABLE IF EXISTS `ws_geoips`;";
$create = "CREATE TABLE IF NOT EXISTS `ws_geoips` (
  `ipAddressStart` varchar(16) NOT NULL,
  `ipAddressEnd` varchar(16) NOT NULL,
  `ipNumberStart` int(11) NOT NULL COMMENT 'can be calculated to ip-Address with INET_NTOA()',
  `ipNumberEnd` int(11) NOT NULL COMMENT 'can be calculated to ip-Address with INET_NTOA()',
  `CountryCode` varchar(2) NOT NULL COMMENT 'ISO 3166',
  `CountryName` text NOT NULL,
  UNIQUE KEY `ipAddressStart` (`ipAddressStart`,`ipAddressEnd`,`ipNumberStart`,`ipNumberEnd`,`CountryCode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$zip = new ZipArchive;
if ($zip->open('GeoIPCountryCSV.zip') !== TRUE) {
	die('failed');
} else {
	$zip->extractTo('.');
	$zip->close();
	echo 'successfully unziped csv-data<br />';

	$dbInstance = makeMysqlConnection();
	myQuery($delete, $dbInstance);
	myQuery($create, $dbInstance);	
	insertCsv2Mysql('GeoIPCountryWhois.csv', $dbInstance);
//	$dbInstance = makeMysqliConnection();
//	myIquery($delete, $dbInstance);
//	myIquery($create, $dbInstance);
//	$sSql = insertCsv2imysql('GeoIPCountryWhois.csv', $dbInstance);
	echo 'successfully finished csv import<br />';

//	$dbInstance->close();
	mysql_close($dbInstance);
}

function insertCsv2Mysql($mycsv, $dbInstance) {
	$fh = fopen($mycsv, 'r');
	$sSqlsTART = 'INSERT INTO `ws_geoips` (`ipAddressStart`, `ipAddressEnd`, `ipNumberStart`, `ipNumberEnd` , `CountryCode`,  `CountryName`) VALUES ';
	$sSql = $sSqlsTART;

	$cnt = 1;
	$overall = 1;
	echo 'successfully inserted ';
	while (($line = fgets($fh)) !== FALSE) {
		if ($cnt < 90) {
			$sSql .= '(' . trim($line) . "),";
		} else {
			$cnt = 0;
			$sSql = rtrim($sSql, ',');
			$sSql .= '; ';
			myQuery($sSql, $dbInstance);
			$sSql = $sSqlsTART;
		}
		++$cnt;
		++$overall;
	}
	echo $overall . ' lines <br />';
	$sSql = rtrim($sSql, ',');
	$sSql .= ';';
	myQuery($sSql, $dbInstance);
	fclose($fh);
	return $sSql;
}


// *** mysql
function makeMysqlConnection(){
	$dbInstance = mysql_pconnect('localhost', 'root', '');
	mysql_select_db('jag_accounts', $dbInstance);
	myQuery("SET NAMES utf8", $dbInstance);
	return $dbInstance;
}

function myQuery($sSql, $dbInstance){
	mysql_query($sSql, $dbInstance);
	if (mysql_errno($dbInstance)) {
		throw new \Exception('MySQL error '.mysql_errno($dbInstance).': '.mysql_error($dbInstance)."\nWhen executing:".$sSql);
	}
}

// *** mysqli
function insertCsv2imysql($mycsv, $dbInstance) {
	$fh = fopen($mycsv, 'r');
	$sSqlsTART = 'INSERT INTO `ws_geoips` (`ipAddressStart`, `ipAddressEnd`, `ipNumberStart`, `ipNumberEnd` , `CountryCode`,  `CountryName`) VALUES ';
	$sSql = $sSqlsTART;

	$cnt = 1;
	$overall = 1;
	echo 'successfully inserted ';
	while (($line = fgets($fh)) !== FALSE) {
		if ($cnt < 90) {
			$sSql .= '(' . trim($line) . "),";
		} else {
			$cnt = 0;
			$sSql = rtrim($sSql, ',');
			$sSql .= '; ';
			myIquery($sSql, $dbInstance);
			$sSql = $sSqlsTART;
		}
		++$cnt;
		++$overall;
	}
	echo $overall . ' lines <br />';
	$sSql = rtrim($sSql, ',');
	$sSql .= ';';
	myIquery($sSql, $dbInstance);
	fclose($fh);
	return $sSql;
}


function myIquery($sSql, $dbInstance) {
	if (!$dbInstance->query($sSql)) {
		throw new \Exception('MySQL error ' . $dbInstance->errno . ': ' . $dbInstance->error . "\nWhen executing:" . $sSql);
	}
}

function makeMysqliConnection() {
	$mysqli = mysqli_init();
	if (!$mysqli) {
		die('mysqli_init failed');
	}

//	if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
//		die('Setting MYSQLI_INIT_COMMAND failed');
//	}

	if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 1000)) {
		die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
	}

	if (!$mysqli->real_connect('localhost', 'root', '', 'jag_accounts')) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
	}

	if (!mysqli_options($mysqli, MYSQLI_READ_DEFAULT_GROUP, "max_allowed_packet=50M")) {
		die('Error setting max_allowed_packet');
	}

	echo 'successfully established Database connection to ' . $mysqli->host_info . "<br />\n";
	return $mysqli;
}

?>