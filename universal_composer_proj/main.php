<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 29.06.2017
 * Time: 09:46
 */

require __DIR__ . '/vendor/autoload.php';


$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'https://mp-pgw.net-m.net:8700/pgw/m2m');
echo $res->getStatusCode();
// "200"
echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
echo $res->getBody();
// {"type":"User"...'

// Send an asynchronous request.
$request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
$promise = $client->sendAsync($request)->then(function ($response) {
    echo 'I completed! ' . $response->getBody();
});
$promise->wait();