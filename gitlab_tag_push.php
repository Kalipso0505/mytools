<?php
/**
 * aachim 09 2017
 *
 * Redirects webhook from gitlab to command wish starts build.
 * A new security token has to be changed in gitlab AND here.
 */

$logPath = '/var/log/build.log';
$lockPath = '/tmp/build.lock';

$gitRequestAsArray = json_decode(file_get_contents('php://input'));

mylog('new Request received', $logPath);
if(!isLocked($lockPath)) {
    createLock($lockPath, $logPath);
    if(evaluateRequest($gitRequestAsArray, $logPath)) {
        header("HTTP/1.1 200 OK");
    } else {
        header('HTTP/1.1 401 See mylog for details', true, 401);
    }
    removeLock($lockPath, $logPath);
} else {
    mylog('Aborted because another process is already running', $logPath);
}
mylog('script end', $logPath);


function createLock($lockPath, $logPath) {
    mylog('create lock to prevent multiple calls @ "/tmp/build.lock"', $logPath);
    $lockFileHandle = fopen($lockPath, "w");
    fclose($lockFileHandle);
}

function removeLock($lockPath, $logPath) {
    unlink($lockPath);
    mylog('remove lock', $logPath);
}

function isLocked($lockPath) {
    return file_exists($lockPath);
}

function mylog($message, $path) {
    system('echo "`date "+%Y-%m-%d %H:%M:%S"` ' . $message . '" >> ' . $path);
}

function evaluateRequest($gitRequestAsObject, $logPath) {

    if(!is_object($gitRequestAsObject)) {
        mylog('No information found in request body', $logPath);
        return false;
    }

    $isTagPushEvent = $gitRequestAsObject->object_kind == 'tag_push';
    if(!$isTagPushEvent) {
        mylog('Aborting because of wrong push event (' . $gitRequestAsObject->object_kind . ')', $logPath);
        return false;

    } else {
        mylog('Detected "tag_push" event', $logPath);
    }

    if($gitRequestAsObject->after == '0000000000000000000000000000000000000000') {
        mylog('Aborting, because this is a delete push', $logPath);
        return false;

    } else {
        $tag = array_pop(explode('/', $gitRequestAsObject->ref));
        mylog('Extracted tag "' . $tag . '" from "' . $gitRequestAsObject->ref . '"', $logPath);
    }

    if(empty($_GET['app'])) {
        mylog('Aborting because app was not defined in request. Please add app=(akira|kiwishop) to the push url', $logPath);
        return false;

    }else {
        $app = $_GET['app'];
    }

    $command= "ssh build01@build01.gkm.cloud '/sbin/build_package " . $app . " " . $tag . " 1'";
    mylog('Executing command: "' . $command . '"', $logPath);
    system($command . ' >> ' . $logPath);

    return true;
}