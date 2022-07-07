<?php

namespace Poool\Subscribe\SDK\Test;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Poool\Subscribe\SDK\Client;

class ClientTest extends TestCase
{
    public static $client;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{ "foo": "bar" }'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        self::$client = new Client([
            'clientId' => 'test',
            'clientSecret' => 'test',
            'accessToken' => 'test',
            'refreshToken' => 'test',
        ], $httpClient);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Client::request
     */
    public function testRequest()
    {
        $result = self::$client->request(['resource' => '/test']);
        $this->assertSame($result['foo'], 'bar');
    }
}
