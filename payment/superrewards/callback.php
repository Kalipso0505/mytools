<?php
include_once 'class/superrewards.php';
$dunited = createSuperrewards(SHOP_TEST, 1234);
$dunited->evaluatePostback();
?>
