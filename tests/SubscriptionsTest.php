<?php

namespace Poool\Subscribe\SDK\Test;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Poool\Subscribe\SDK\Client;
use Poool\Subscribe\SDK\Subscriptions;

class SubscriptionsTest extends TestCase
{
    public static $client;
    public static $subscriptions;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{ "payment": { "id": "payment-1" }, "subscription": { "id": "subscription-1" }, ' .
                '"intentId": "intent-1" }'),
            new Response(200, [], '{ "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "subscription": { "id": "subscription-1" } }'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        self::$client = new Client(['clientId' => 'test', 'clientSecret' => 'test'], $httpClient);
        self::$subscriptions = new Subscriptions(self::$client);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Subscriptions::createIntent
     */
    public function testCreateIntent()
    {
        $result = self::$subscriptions->createIntent('stripe', 'offer-1');
        $this->assertSame($result['payment']['id'], 'payment-1');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
        $this->assertSame($result['intentId'], 'intent-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Subscriptions::confirmIntent
     */
    public function testConfirmIntent()
    {
        $result = self::$subscriptions->confirmIntent('stripe', 'subscription-1');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Subscriptions::switchOffer
     */
    public function testSwitchOffer()
    {
        $result = self::$subscriptions->switchOffer('subscription-1', 'offer-1');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Subscriptions::cancel
     */
    public function testCancel()
    {
        $result = self::$subscriptions->cancel('subscription-1');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Subscriptions::reactivate
     */
    public function testReactivate()
    {
        $result = self::$subscriptions->reactivate('subscription-1');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }
}
