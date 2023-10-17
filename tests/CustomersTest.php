<?php

namespace Poool\Subscribe\SDK\Test;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Poool\Subscribe\SDK\Client;
use Poool\Subscribe\SDK\Customers;

class CustomersTest extends TestCase
{
    public static $client;
    public static $customers;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{ "username": "user@test.com", "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "username": "user@test.com", "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "username": "user@test.com", "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "username": "user@test.com", "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "username": "user@test.com", "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "username": "user@test.com", "subscription": { "id": "subscription-1" } }'),
            new Response(200, [], '{ "username": "user@test.com", "subscription": { "id": "subscription-1", "price": "price_id" } }'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        self::$client = new Client(['clientId' => 'test', 'clientSecret' => 'test'], $httpClient);
        self::$customers = new Customers(self::$client);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Customers::get
     */
    public function testGet()
    {
        $result = self::$customers->get('customer-1');
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Customers::set
     */
    public function testSet()
    {
        $result = self::$customers->set('customer-1', [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Customers::attachExistingSubscription
     */
    public function testAttachExistingSubscription()
    {
        $result = self::$customers->attachExistingSubscription('customer-1', 'stripe', [
            'subscriptionId' => 'subscription-1',
        ]);
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Customers::switchSubscriptionOffer
     */
    public function testSwitchSubscriptionOffer()
    {
        $result = self::$customers->switchSubscriptionOffer('customer-1', 'subscription-1', 'offer-1');
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Customers::cancelSubscription
     */
    public function testCancelSubscription()
    {
        $result = self::$customers->cancelSubscription('customer-1', 'subscription-1');
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Customers::reactivateSubscription
     */
    public function testReactivateSubscription()
    {
        $result = self::$customers->reactivateSubscription('customer-1', 'subscription-1');
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Customers::switchSubscriptionOffer
     */
    public function testswitchSubscriptionWithPrice()
    {
        $result = self::$customers->switchSubscriptionOffer('customer-1', 'subscription-1', 'offer-1', ['priceId' => 'price_id']);
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertSame($result['subscription']['id'], 'subscription-1');
        $this->assertSame($result['subscription']['price'], 'price_id');
    }
}
