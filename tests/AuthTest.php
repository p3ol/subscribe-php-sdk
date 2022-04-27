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
            new Response(200, [], '{ "accessToken": "access test", "refreshToken": "refresh test" }'),
            new Response(200, [], '{ "callbackId": "callback-id" }'),
            new Response(200, [], '{ "accessToken": "callback test", "refreshToken": "callback test" }'),
            new RequestException(
                'Forbidden',
                new Request('GET', '/subscribe/auth/me'),
                new Response(403, [], '{ "error": "forbidden" }')
            ),
            new Response(200, [], '{ "accessToken": "new access test", "refreshToken": "new refresh test" }'),
            new Response(200, [], '{ "username": "user@test.com", "email": "foo@bar.com" }'),
            new Response(200, [], '{ "username": "user@test.com" }'),
            new Response(200, [], '{ "username": "user@test.com" }'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        self::$client = new Client([
            'clientId' => 'test',
            'clientSecret' => 'test',
            'accessToken' => 'test',
            'refreshToken' => 'test',
        ], $httpClient);
        self::$auth = new Auth(self::$client);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Auth::signin
     * @covers \Poool\Subscribe\SDK\Client::request
     */
    public function testSignin()
    {
        $tokens = self::$auth->signin('username', 'password');
        $this->assertSame($tokens['accessToken'], 'access test');
        $this->assertSame($tokens['refreshToken'], 'refresh test');
        $this->assertSame(self::$auth->accessToken, 'access test');
        $this->assertSame(self::$auth->refreshToken, 'refresh test');
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
        $this->assertSame(self::$auth->accessToken, 'callback test');
        $this->assertSame(self::$auth->refreshToken, 'callback test');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Auth::me
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Client::request
     */
    public function testMe()
    {
        $infos = self::$auth->me();
        $this->assertSame($infos['username'], 'user@test.com');
        $this->assertSame($infos['email'], 'foo@bar.com');
        $this->assertSame(self::$client->auth->accessToken, 'new access test');
        $this->assertSame(self::$client->auth->refreshToken, 'new refresh test');
    }

    /**
     * @covers \Poool\Subscribe\SDK\Auth::signup
     * @covers \Poool\Subscribe\SDK\Client::request
     */
    public function testSignup()
    {
        $result = self::$auth->signup([
            'username' => 'user@test.com',
            'password' => 'password',
        ]);
        $this->assertSame($result['username'], 'user@test.com');
        $this->assertNotContains('password', $result);
    }

    /**
     * @covers \Poool\Subscribe\SDK\Auth::set
     * @covers \Poool\Subscribe\SDK\Client::requestWithRetry
     * @covers \Poool\Subscribe\SDK\Client::request
     */
    public function testSet()
    {
        $result = self::$auth->set([
            'email' => 'test@test.com',
        ]);
        $this->assertSame($result['username'], 'user@test.com');
    }
}
