<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'class/paypal.php';
$paypalConnection = new paypal();
$result = $paypalConnection->requestPerformPayment();
echo '<pre>'.print_r($result, 1).'</pre>';
?>
