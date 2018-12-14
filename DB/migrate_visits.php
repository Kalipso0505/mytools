<?php
use BackEndHelperBundle\Helper\MysqlQueryHelper;

date_default_timezone_set('Europe/Berlin');
ini_set('mysqli.reconnect', 'ON');

include_once 'DBQueryHelper.php';
include_once 'MysqlQueryHelper.php';

const DATABASE = 'gkms_nami_prod';

// const HOST_SOURCE = '35.189.214.104';
const HOST_SOURCE = '127.0.0.1';
const USER_SOURCE = 'aachim_r';
const PASSWORD_SOURCE = 'F2m;N93:xI';
const PORT_SOURCE = 3307;

// const HOST_TARGET = '35.195.83.174';
const HOST_TARGET = '127.0.0.1';
const USER_TARGET = 'aachim_rw';
const PASSWORD_TARGET = 'F2m;N93:xI';
const PORT_TARGET = 3307;


$start = new DateTime();
$totalRows = 0;
echo 'start' . PHP_EOL;

$source = new DBConnector(HOST_SOURCE, USER_SOURCE, PASSWORD_SOURCE, DATABASE, PORT_SOURCE);
$target = new DBConnector(HOST_TARGET, USER_TARGET, PASSWORD_TARGET, DATABASE, PORT_TARGET);

$controllerRefData = loadRefTable($target);
$insertCandidates = get($source, $target, $controllerRefData);
while (count($insertCandidates['data'])> 0) {
    $lap = new DateTime();
    $inserts = MysqlQueryHelper::generateInsertsFromArray('visit', $insertCandidates['data'],true);
    $target->query($inserts);
    $deletes = MysqlQueryHelper::generateDeletesFromArray('derfel_visits', $insertCandidates['ids']);
    $target->query($deletes);

    $totalRows += count($insertCandidates['data']);
    $interval = (new DateTime())->diff($lap);
    $intervalTotal = (new DateTime())->diff($start);
    echo 'lap time: ' . $interval->format('%i:%s s') . ' (' . $intervalTotal->format('%h:%i:%s h') . ')' . PHP_EOL;
    echo 'rows so far rows: ' . $totalRows . PHP_EOL;
     // sleep(2);

    $insertCandidates = get($source, $target, $controllerRefData);
}

$interval = (new DateTime())->diff($start);
echo 'Total time: ' . $interval->format('%d days %h:%i:%s') . PHP_EOL;
echo 'Total rows: ' . $totalRows . PHP_EOL;

$source->disconnect();
$target->disconnect();


## FUNCTIONS
function get(DBConnector $source, DBConnector $target, $controllerRefData) {
    $get = 'SELECT dv.id, dv.visitor_id, dv.visit_path, dv.controller AS visit_controller_id, loading_time, server_name as server_number, visit_date as created
FROM derfel_visits dv
WHERE dv.visitor_id IS NOT NULL
limit 10000';
    $result = [];
    $idCollect = '';

    $resultPointer = $source->query($get);
    while($row = mysqli_fetch_assoc($resultPointer)) {

        // two columns have to be modified:
        // 1. getting server number from servername
        $row['server_number'] = extractNumberFromName($row['server_number']);

        // 2. get refernce key from controller; a special one is created, that has no special chars and can be used as array key
        $key = genKeyFromcontroller($row['visit_controller_id']);

        if(!isset($controllerRefData[$key])) {
            $controllerRefData = loadRefTable($target);
            if(!isset($controllerRefData[$key])) {
                // unknown controller
                $controllerRefData[$key] = insertNewController($target, $row['visit_controller_id']);
            }
        }

        $row['visit_controller_id'] = $controllerRefData[$key];
        $result[] = $row;
        $idCollect[] = ['id' => $row['id']];
    }
    return ['data' => $result, 'ids' => $idCollect];
}

function loadRefTable(DBConnector $source) {
    $data = $source->query('SELECT id, `name` FROM visit_controller');
    $idLookup = [];
    while($row = mysqli_fetch_assoc($data)) {
        $key = genKeyFromcontroller($row['name']);
        $idLookup[$key] = $row['id'];
    }
    return $idLookup;
}

function insertNewController(DBConnector $target, $controllerName) {
    $target->query('INSERT IGNORE INTO `visit_controller` (`name`) VALUES ("' . $controllerName . '")');
    return mysqli_insert_id($target->getConn());
}

function extractNumberFromName($serverName)
{
    $serverNumber = 0;
    $matches = [];
    // get digit from string
    $pattern = preg_match('!\d+!', $serverName, $matches);
    if (!empty($pattern)) {
        // get last match
        $serverNumber = (int) $matches[count($matches) - 1];
    }

    return $serverNumber;
}

function genKeyFromcontroller($controller) {
    return str_replace(':','', $controller);
}
