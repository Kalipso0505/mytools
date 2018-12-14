<?php

/**
 * JAG Webservice Application
 */

namespace JAG_WSC\Lib\DB;
use JAG_WSC\Lib\Log AS Log;

$dbInstance;
$host = '';
$user = '';
$password = '';
$database = '';

if (isset($config['database'])){
	$host = $config['database']['host'];
	$user = $config['database']['user'];
	$password = $config['database']['password'];
	$database = $config['database']['database'];
}else{
	throw new \Exception('No Databaseconfiguration found!');
}


if (!isset($dbInstance)) {
	$dbInstance = mysql_pconnect($host, $user, $password);
	$db = mysql_select_db($database, $dbInstance);
	mysql_query("SET NAMES utf8", $dbInstance);
	throwErr("SET NAMES utf8");
}



/**
 * Creates a sql string for inserting a dataset
 * @param string $tableName table, in which the dataset is inserted
 * @param array $aData array containing the data to be stored; key = colname, value ...
 * @param array $join optional Parameter, which can be used to turn the insert .. Value-Statement into a insert .. Select-Statment<br />
 * static values from $aData will be integrated into the insertstatement<br />
 * <pre>
 * $join['table'] table, where the data is selected from
 * $join['fieldnames'] an array like $aData [sourcecol] => targetcol
 * $join['where'] string with where additionals
 * </pre>
 * @return string inserted ID
 */
function insert($tableName, $aData, $join=NULL) {
	$sKeys = '';
	$sValues = '';
	foreach ($aData as $colname => $colvalue) {
		$sKeys .= '`'.mysql_real_escape_string((string) $colname) . '`,';
		$sValues .= '\'' . mysql_real_escape_string((string) $colvalue) . '\',';
	}
	if(isset($join)){
		$bFirst = true;
		foreach ($join as $table) {
			foreach ($table['fieldnames'] as $colname => $colvalue) {
				$sKeys .= $colname . ',';			
				$sValues .= $colvalue . ',';
			}
			if($bFirst){
				$sFrom = ' FROM '.$table['table'].' ';			
				$sWhere = ' WHERE '.$table['where'];
				$bFirst = false;
			}else{
				$sFrom .= 'INNER JOIN '.$table['table'].' on ('.$table['where'].') ';
			}
		}
		$sSql = 'INSERT INTO '.$tableName.' ('.rtrim($sKeys, ',').') SELECT '.rtrim($sValues, ',').$sFrom.$sWhere;			
	}else{
		$sSql = 'INSERT INTO '.$tableName.' ('.rtrim($sKeys, ',').') VALUES ('.rtrim($sValues, ',').');';
	}
	mysql_query($sSql);
	throwErr($sSql);
	Log\fileLog('insert: '.$sSql);
	return  mysql_insert_id();
}

/**
 * Creates an update-statement and executes it
 * @param string $tableName table to be updated
 * @param array $aData associative array, where keys are cols and values are colvalues
 * @param string $where empty per default. <br/>Will be added when filled. <br />Sql-'WHERE' will be added by function
 */
function update($tableName, $aData, $where = '') {
	$sSql = '';
	foreach ($aData as $colname => $colvalue) {
		$colval = (is_array($colvalue)) ? mysql_real_escape_string(array_shift($colvalue)) : '"'.mysql_real_escape_string($colvalue).'"';
		$sSql .= '`'.mysql_real_escape_string($colname) . '` = ' . $colval . ',';
	}
	$sSql = 'UPDATE ' . $tableName . ' SET ' . rtrim($sSql, ',');
	$sSql .= (empty($where) ? '' : ' WHERE ' . $where) . ';';
	$result = mysql_query($sSql);
	throwErr($sSql);
	Log\fileLog('update: '.$sSql);
	return $result;
}

/**
 * Selects data per $sql
 * @param type $sql
 * @return bollean|array false on error, associative array with sqlresult on success
 */
function select($sSql, $isSingleRow = false) {
	$result = false;
	$resource = mysql_query($sSql);
	if ($resource) {
		if($isSingleRow){
			$result =  mysql_fetch_array($resource, MYSQL_ASSOC);
		}else{
			while($r = mysql_fetch_array($resource, MYSQL_ASSOC)){
				$result[] = $r;
			}
		}
	}
	throwErr($sSql);
	Log\fileLog('select: '.$sSql);
	return $result;
}

function throwErr($query){
	if (mysql_errno()) {
		throw new \Exception('MySQL error '.mysql_errno().': '.mysql_error()."\nWhen executing:".$query);
	}
}