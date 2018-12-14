<?php

    require __DIR__ . '/../vendor/autoload.php';

    $response = (new JsonRpcCurl())
        ->setUrl('http://localhost/opensource/server/jsonrpc_curl/test/RemoteServerMock')
        ->setId(1)
        ->setMethod('Foo.bar.fooBarMethod')
        ->setData(['name' => 'Mr. X'])
        ->send();

    var_dump($response);
