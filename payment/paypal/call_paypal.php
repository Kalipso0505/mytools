<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'class/paypal.php';

$paypalConnection = new paypal();
$paypalConnection->addAmount('55.00', 'USD');
$result = $paypalConnection->requestAuthentication();
header('Location:'.$result['loginurl']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf8">
  <title>paypal</title>
  </head>
  <body>
	  <iframe src="<?=$result['loginurl']?>" >
	  </iframe>
	  <div>
		  <?= '<pre>'.var_dump($result, 1).'</pre>'; ?>
	  </div>
  </body>
</html>
