<?php

namespace Poool\Subscribe\SDK\Test;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Poool\Subscribe\SDK\Client;
use Poool\Subscribe\SDK\Payments;

class PaymentsTest extends TestCase
{
    public static $client;
    public static $payments;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{ "paymentId": "payment-1", "intentId": "intent-1" }'),
            new Response(200, [], '{ "payment": { "id": "payment-1" }, "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "clientSecret": "secret" }'),
            new Response(200, [], '{ "updated": true }'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        self::$client = new Client(['clientId' => 'test', 'clientSecret' => 'test'], $httpClient);
        self::$payments = new Payments(self::$client);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Payments::createIntent
     */
    public function testCreateIntent()
    {
        $result = self::$payments->createIntent('stripe', 'offer-1');
        $this->assertSame($result['paymentId'], 'payment-1');
        $this->assertSame($result['intentId'], 'intent-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Payments::confirmIntent
     */
    public function testConfirmIntent()
    {
        $result = self::$payments->confirmIntent('stripe', 'payment-1');
        $this->assertSame($result['payment']['id'], 'payment-1');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Payments::createSetupIntent
     */
    public function testCreateSetupIntent()
    {
        $result = self::$payments->createSetupIntent('stripe');
        $this->assertSame($result['clientSecret'], 'secret');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Payments::updateSource
     */
    public function testUpdateSource()
    {
        $result = self::$payments->updateSource('stripe', 'card-id');
        $this->assertSame($result['updated'], true);
    }
}
