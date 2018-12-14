<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'class/dunited.php';
$sGame = "og41";
$sLanguage = "de";
$sSh = "9d05c220f99e5685ada2b8e68aa8b719";
$sOh = "9c1b4cf9c35d3f4ed84a8223fb887f89";
$sPh = "814dcf34d5cc8095a368c7d400a0de95";


$dunited = new dunited();
$dunited->init('ad5bf60644d46b835660f2b3ab78e219', $sGame, $sLanguage, $sSh, $sOh, $sPh);
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