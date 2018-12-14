<?php
/**
 * call.php
 * Author: aachim
 * Created: 23.07.13
 * This file is property of net mobile AG
 */

include_once '../vendor/autoload.php';

 $call = (new JsonRpcCurl())
            ->setId(1)
            ->setUrl('http://localhost/test/php_tests/RPC/Server/public/server.php')
            ->setMethod('web.Base.hello')
//            ->setData([1,2])
            ->setProxy()
            ->send();

echo '<pre>';
print_r($call);
    echo '</pre>';