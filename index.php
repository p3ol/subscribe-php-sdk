<?php

require_once "vendor/autoload.php";

$httpClient = new \GuzzleHttp\Client(['verify' => false]);
$client = new \Poool\Subscribe\SDK\Client([
    'clientId' => '0893042862168465',
    'clientSecret' => '23WfqB4TbdKm1TnkQ0QrAIv4nZuQcHQ9'
], $httpClient);

$offers = $client->offers->list();
echo '<h2>offers->list()</h2><pre>' . print_r($offers, true) . '</pre>';

$tokens = $client->auth->signin('ugo+test1@poool.fr', 'lol');
echo '<h2>auth->signin()</h2><pre>' . print_r($tokens, true) . '</pre>';

$callback = $client->auth->createCallback('bearer', 'http://localhost');
echo '<h2>auth->createCallback()</h2><pre>' . print_r($callback, true) . '</pre>';

$verify = $client->auth->verifyCallback($callback['callbackId']);
echo '<h2>auth->verifyCallback()</h2><pre>' . print_r($verify, true) . '</pre>';
