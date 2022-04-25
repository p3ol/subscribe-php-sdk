<?php

namespace Poool\Subscribe\SDK;

class Auth
{
    private Client $client;
    public $accessToken;
    public $refreshToken;

    /**
     * @param Client $client - SDK client
     *
     * <code>
     * $auth = new Auth($client);
     * </code>
     */
    public function __construct($client, $options = [])
    {
        $this->client = $client;

        if (isset($options['accessToken'])) {
            $this->accessToken = $options['accessToken'];
        }

        if (isset($options['refreshToken'])) {
            $this->refreshToken = $options['refreshToken'];
        }
    }

    /**
     * Sign a user in
     * @param string $username - Customer username
     * @param string $password - Customer password
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] Returns generated auth tokens
     *
     * <code>
     * $auth->signin('username@email.com', 'password123');
     * </code>
     */
    public function signin($username, $password, $options = [])
    {
        $tokens = $this->client->request(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/auth/token',
            'json' => [
                'grantType' => 'password',
                'username' => $username,
                'password' => $password,
            ],
        ]));

        if (isset($tokens['accessToken'])) {
            $this->accessToken = $tokens['accessToken'];
            $this->refreshToken = $tokens['refreshToken'];
        }

        return $tokens;
    }

    /**
     * Step 1 of the JSAT (JSON Security Assertion Token) auth flow.
     * This flow requires the creation of a callbackId from the provider that
     * will be used by the consumer to redirect the user once it's signed in on
     * both sides. Thus, the provider can also become a consumer if the consumer
     * implements the same flow in the opposite way.
     *
     * The callbackId can be granted using two methods:
     * - "bearer": This uses the "Authorization" header to recognize an already
     * authenticated user and automatically attach the callback ownership to it
     * - "password": This first signs the user then attach the callback ownership
     *
     * Both methods require a redirectUri for the consumer to be able to redirect
     * the user once it's signed in on both sides.
     * @param string $grantType Grant type (password, bearer)
     * @param string $redirectUri Redirect URI
     * @param string $username (optional) Username for password grant type
     * @param string $password (optional) Password for password grant type
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] Returns generated auth tokens
     *
     * <code>
     * $auth->createCallback('bearer', 'https://example.com/callback');
     * $auth->createCallback('bearer', 'https://example.com/callback', 'test@test.com', 'password123');
     * </code>
     */
    public function createCallback($grantType, $redirectUri, $username = null, $password = null, $options = [])
    {
        $json = [
            'grantType' => $grantType,
            'redirectUri' => $redirectUri,
        ];

        if ($grantType === 'password') {
            $json['username'] = $username;
            $json['password'] = $password;
        }

        $callback = $this->client->request(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/auth/callback',
            'json' => $json,
        ]));

        return $callback;
    }

    /**
     * Step 2 of JSAT: Verify the previously created callback
     * @param string $callbackId Callback ID generated by $auth->createCallback
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] Returns generated auth tokens
     *
     * <code>
     * $auth->verifyCallback('callback-id');
     * </code>
     */
    public function verifyCallback($callbackId, $options = [])
    {
        $callback = $this->client->request(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/auth/token',
            'json' => [
                'grantType' => 'callback',
                'callbackId' => $callbackId,
            ],
        ]));

        if (isset($callback['accessToken'])) {
            $this->accessToken = $callback['accessToken'];
            $this->refreshToken = $callback['refreshToken'];
        }

        return $callback;
    }

    /**
     * Get information about the currently authenticated user
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] Returns info on the current authenticated user
     *
     * <code>
     * $auth->me();
     * </code>
     */
    public function me($options = [])
    {
        $result = $this->client->requestWithRetry(array_merge($options, [
            'method' => 'GET',
            'resource' => '/subscribe/auth/me',
        ]));

        return $result;
    }

    /**
     * Create a new user account
     * @param mixed[] $account Account information
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] Returns info on the current authenticated user
     * 
     * <code>
     * $auth->signup([
     *     'firstName' => 'John',
     *     'lastName' => 'Doe',
     *     'email' => 'john@doe.com',
     *     'password' => 'password123',
     * ]);
     * </code>
     */
    public function signup($account, $options = [])
    {
        $result = $this->client->request(array_merge($options, [
            'method' => 'PUT',
            'resource' => '/subscribe/account',
            'json' => $account,
        ]));

        return $result;
    }

    /**
     * Update the currently authenticated user account
     * @param mixed[] $updates Account information
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] Returns info on the current authenticated user
     * 
     * <code>
     * $auth->set([
     *     'firstName' => 'John',
     *     'lastName' => 'Doe',
     * ]);
     * </code>
     */
    public function set($updates, $options = [])
    {
        $result = $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/account',
            'json' => $updates,
        ]));

        return $result;
    }
}
