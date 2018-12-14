<?php

/**
 * JAG Webservice Application
 */

# live
const HOST = '192.168.214.16';
const USER = 'developer';
const PASSWORD = 'XD1qV48gpo';
const DATABASE = 'tbe_data';

# local
//const HOST = '192.168.56.2';
//const USER = 'developer';
//const PASSWORD = 'XD1qV48gpo';
//const DATABASE = 'tbe_mutable';

# stage
#const HOST = '192.168.214.24';
#const USER = 'developer';
#const PASSWORD = 'XD1qV48gpo';
#const DATABASE = 'tbe_data';

$dbInstance;

/**
 * @todo throw exception on warning so it can be catched by try except
 * function exception_error_handler($errno, $errstr, $errfile, $errline ) {
 * 	if(0==(error_reporting(E_WARNING)))
 * 		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
 * }
 * set_error_handler("exception_error_handler");
 * http://de.w3support.net/index.php?db=so&id=1241728
 */
if (!isset($dbInstance)) {
	$dbInstance = mysqli_connect(HOST, USER, PASSWORD);
	$db = mysqli_select_db($dbInstance, DATABASE);
	mysqli_query($dbInstance, "SET NAMES utf8");
}

/**
 * Creates a sql string for inserting a dataset
 * @param $dbInstance
 * @param string $tableName table, in which the dataset is inserted
 * @param array $aData array containing the data to be stored; key = colname, value ...
 * @return int|string inserted ID
 */
function insert($dbInstance, $tableName, $aData) {
	$sKeys = '';
	$sValues = '';
	foreach ($aData as $key => $value) {
		$sKeys .= mysqli_real_escape_string($dbInstance, (string) $key) . ',';
		$sValues .= '"' . mysqli_real_escape_string($dbInstance, (string) $value) . '",';
	}
	$sSql = 'INSERT INTO ' . $tableName . ' (' . rtrim($sKeys, ',') . ') VALUES (' . rtrim($sValues, ',') . ');';
	mysqli_query($dbInstance, $sSql);
    return mysqli_insert_id($dbInstance);
}

/**
 * Creates an update-statement and executes it
 * @param $dbInstance
 * @param string $tableName table to be updated
 * @param array $aData associative array, where keys are cols and values are colvalues
 * @param string $where empty per default. <br/>Will be added when filled. <br />Sql-'WHERE' will be added by function
 */
function update($dbInstance, $tableName, $aData, $where = '') {
	$sKeys = '';
	$sValues = '';
    $sql = '';
	foreach ($aData as $key => $value) {
		$sql .= $key . ' = "' . mysqli_real_escape_string($dbInstance, $value) . '",';
	}
	$sSql = 'UPDATE ' . $tableName . ' SET ' . rtrim($sKeys, ',') . ' = "' . rtrim($sValues, '",') . ')';
	$sSql = (empty($where) ? '' : ' WHERE ' . $where) . ';';
	mysqli_query($dbInstance, $sSql);
}

/**
 * Selects data per $sql
 * @param $dbInstance
 * @param $sql
 * @param bool $isSingleRow
 * @return array|bool|null false on error, associative array with sqlresult on success
 */
function select($dbInstance, $sql, $isSingleRow = false) {
	$result = false;
	$resource = mysqli_query($dbInstance, $sql);
	if(mysqli_errno($dbInstance)){
		echo mysqli_error($dbInstance);
	}else
		if ($resource) {
			if($isSingleRow){
				$result =  mysqli_fetch_array($resource, MYSQL_ASSOC);
			}else{
				while($r = mysqli_fetch_array($resource, MYSQL_ASSOC)){
					$result[] = $r;
				}
			}
		}
	return $result;
}
