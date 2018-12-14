<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
date_default_timezone_set('Europe/Berlin');

$mymd5 = '';
$mysha1 = '';
$mysha512 = '';

$myutf8 = '';
$myutf8decode = '';

$mybase64 = '';
$mybase64decode = '';

$myurlencode = '';
$myrawurl = '';
$myhtml = '';
$myurldecode = '';
$myrawurldecode = '';

$json = '';
$array2jsonPretty = '';
$array2jsonShort = '';

$jasonline = '';
$jasonpretty = '';

$json2object = '';
$json2array = '';

if (isset($_POST['2decode'])) {
    error_reporting(E_ALL);
    $mymd5 = md5($_POST['2decode']);
    $mysha1 = sha1($_POST['2decode']);
    $mysha512 = hash('sha512', $_POST['2decode']);

    $myutf8 = htmlentities(utf8_encode($_POST['2decode']));
    $myutf8decode = htmlentities(utf8_decode($_POST['2decode']));

    $mybase64 = htmlentities(base64_encode($_POST['2decode']));
    $mybase64decode = htmlentities(base64_decode($_POST['2decode']));

    $myurlencode = htmlentities(urlencode($_POST['2decode']));
    $myrawurl = htmlentities(rawurlencode($_POST['2decode']));
    $myhtml = htmlentities(htmlentities($_POST['2decode']));
    $myurldecode = htmlentities(urldecode($_POST['2decode']));
    $myrawurldecode = htmlentities(rawurldecode($_POST['2decode']));

    // from json
    $json2object = json_decode($_POST['2decode']);
    $json2array = json_decode($_POST['2decode'], true);
    $json2array = var_export($json2array, true);

    //to json
    $json = $_POST['2decode'];
    // build from array
    $ob = json_decode($json);
    if ($ob === null) {
        $error = json_last_error();
        $array2jsonPretty = 'not possible (' . $error['message'] . ' at line ' . $error['line'] . ')';
        $array2jsonShort = $array2jsonPretty;
    } else {
        $array2jsonPretty = json_encode($json, JSON_PRETTY_PRINT);
        $array2jsonShort = json_encode($json);
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 70px;
        }
    </style>
    <meta http-equiv="content-type" content="text/html; charset=windows-1250">
    <title>encodings</title>
</head>
<body>
<form action="" method="post" class="container col-md-12">
    <div class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <p class="nav navbar-text lead">Alex's converter helper</p>
        <input type="submit" name="do" value="convert" class="btn btn-warning navbar-btn"/>
    </div>

    <div class="container col-md-12">
        <div class="form-group">
            <textarea class="form-control" rows="3" name="2decode"
                      placeholder="Place the text to convert here"><?= (empty($_POST['2decode']) ? '' : $_POST['2decode']) ?></textarea>
        </div>


        <hr>
        <div class="row"><label class="col-md-1"><strong>md5:</label>
            <pre><?= $mymd5 ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>sha1:</label>
            <pre><?= $mysha1 ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>sha512:</label>
            <pre><?= $mysha512 ?></pre>
        </div>
        <hr>
        <div class="row"><label class="col-md-1"><strong>utf8:</label>
            <pre><?= $myutf8 ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>base64:</label>
            <pre><?= $mybase64 ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>urlencoded:</label>
            <pre><?= $myurlencode ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>rawurlencoded:</label>
            <pre><?= $myrawurl ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>htmlencoded:</label>
            <pre><?= $myhtml ?></pre>
        </div>
        <hr>
        <div class="row"><label class="col-md-1"><strong>utf8decode:</label>
            <pre><?= $myutf8decode ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>base64decode:</label>
            <pre><?= $mybase64decode ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>urldecoded:</label>
            <pre><?= $myurldecode ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>rawurldecode:</label>
            <pre><?= $myrawurldecode ?></pre>
        </div>
        <hr>
        <div class="row"><label class="col-md-1"><strong>array 2 json line:</label>
            <pre class="col-xs-"><?= $array2jsonShort ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>array 2 json pretty:</label>
            <pre class="col-xs-"><?= $array2jsonPretty ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>json 2 object:</label>
            <pre class="col-xs-"><?= print_r($json2object, 1) ?></pre>
        </div>
        <div class="row"><label class="col-md-1"><strong>json 2 array:</label>
            <pre class="col-xs-"><?= print_r($json2array, 1) ?></pre>
        </div>
    </div>
</form>
</body>
</html>
