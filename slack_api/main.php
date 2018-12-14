<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 29.06.2017
 * Time: 09:46
 */

require __DIR__ . '/vendor/autoload.php';

$settings = [
    'username' => 'Alexs Slack Bot',
    'link_names' => true
];

$client = new Maknz\Slack\Client(
    'https://hooks.slack.com/services/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',
    $settings
);

$result = $client->attach(
    [
        'fallback' => 'Current server stats',
        'text'     => 'Current server stats',
        'color'    => 'danger',
        'fields'   => [
            [
                'title' => 'CPU usage',
                'value' => '90%',
                'short' => true // whether the field is short enough to sit side-by-side other fields, defaults to false
            ],
            [
                'title' => 'RAM usage',
                'value' => '2.5GB of 4GB',
                'short' => true
            ]
        ]
    ]
)->send('YOU\'VE BEEN SLACKT');
var_dump($result);