<?php

namespace Poool\Subscribe\SDK\Test;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Poool\Subscribe\SDK\Client;
use Poool\Subscribe\SDK\Auth;

class AuthTest extends TestCase
{
    public static $client;
    public static $auth;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{ "callbackId": "callback-id" }'),
            new Response(200, [], '{ "accessToken": "callback test", "refreshToken": "callback test" }'),
            new RequestException(
                'Forbidden',
                new Request('GET', '/subscribe/auth/me'),
                new Response(403, [], '{ "error": "forbidden" }')
            ),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        self::$client = new Client([
            'clientId' => 'test',
            'clientSecret' => 'test',
        ], $httpClient);
        self::$auth = new Auth(self::$client);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Auth::createCallback
     * @covers \Poool\Subscribe\SDK\Client::request
     */
    public function testCreateCallback()
    {
        $callback = self::$auth->createCallback('bearer', 'http://test.com/callback');
        $this->assertSame($callback['callbackId'], 'callback-id');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Auth::verifyCallback
     * @covers \Poool\Subscribe\SDK\Client::request
     */
    public function testVerifyCallback()
    {
        $callback = self::$auth->verifyCallback('callback-id');
        $this->assertSame($callback['accessToken'], 'callback test');
        $this->assertSame($callback['refreshToken'], 'callback test');
    }
}
