<?php

require_once "vendor/autoload.php";

$httpClient = new \GuzzleHttp\Client(['verify' => false]);
$client = new \Poool\Subscribe\SDK\Client([
    'clientId' => '8229685410804140',
    'clientSecret' => 'Cxs1fey6CBGg8ghErxba200u0ZgAdbeJ',
    'debug' => false,
], $httpClient);

$offers = $client->offers->list();
echo '<h2>offers->list()</h2><pre>' . print_r($offers, true) . '</pre>';


$callback = $client->auth->createCallback('password', 'http://localhost', 'john@doe.fr', 'aaa');
echo '<h2>auth->createCallback()</h2><pre>' . print_r($callback, true) . '</pre>';

$verify = $client->auth->verifyCallback($callback['callbackId']);
echo '<h2>auth->verifyCallback()</h2><pre>' . print_r($verify, true) . '</pre>';
