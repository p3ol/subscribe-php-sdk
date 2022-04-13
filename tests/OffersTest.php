<?php

namespace Poool\Subscribe\SDK\Test;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Poool\Subscribe\SDK\Client;
use Poool\Subscribe\SDK\Offers;

class OffersTest extends TestCase
{
    public static $client;
    public static $offers;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{ "offers": [{ "id": "offer-1", "name": "My offer"}], "total": 1 }'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        self::$client = new Client(['clientId' => 'test', 'clientSecret' => 'test'], $httpClient);
        self::$offers = new Offers(self::$client);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     * @covers \Poool\Subscribe\SDK\Offers::list
     */
    public function testList()
    {
        $result = self::$offers->list();
        $this->assertSame($result['offers'][0]['id'], 'offer-1');
        $this->assertSame($result['offers'][0]['name'], 'My offer');
        $this->assertSame($result['total'], 1);
    }
}
