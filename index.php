<?php

require_once "vendor/autoload.php";

$httpClient = new \GuzzleHttp\Client(['verify' => false]);
$client = new \Poool\Subscribe\SDK\Client([
    'clientId' => '0893042862168465',
    'clientSecret' => '23WfqB4TbdKm1TnkQ0QrAIv4nZuQcHQ9'
], $httpClient);

?>
<pre>
<?php print_r($client->offers->list()); ?>
</pre>
