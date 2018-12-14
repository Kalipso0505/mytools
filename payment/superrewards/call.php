<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'class/superrewards.php';

$uid = "1234";
$opt = "og41";


$dunited = new superrewards(1234, '848fc3a03b73e98b762b7c64738d7355', 'ishfqylmqlr.32824847080');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=windows-1250">
		<title>Diamantstore</title>
	</head>
	<body>
		<div style="margin: 10px;">
			<b>generated iframeurl: </b><?= $dunited->getIFrameUrl(); ?><br />
			<b>generated response-testuri: </b><a href="<?= $testResponseUri ?>"><?= $testResponseUri ?></a>
		</div>
		<div style="width: 1400px">
			<div style="float: left;">
				<iframe src="<?= $dunited->getIFrameUrl(); ?>" frameborder="0" width="728" height="2400" scrolling="no" ></iframe>
			</div>
			<div style="float: left;"><?php '<pre>' . var_dump($dunited, 1) . '</pre>' ?></div>
			<div style="clear: both;"></div>
		</div>
	</body>
</html>