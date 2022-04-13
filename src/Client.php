<?php

namespace Poool\Subscribe\SDK;

use GuzzleHttp\Exception\RequestException;

class Client
{
    private $clientId;
    private $clientSecret;
    private $apiUrl;
    private $debug;
    private $httpClient;
    private $timeout;

    public $auth;
    public $offers;

    public function __construct($config, $httpClient = null)
    {
        $this->clientId = $config['clientId'];
        $this->clientSecret = $config['clientSecret'];
        $this->apiUrl = $config['apiUrl'] ?? 'https://api.poool.develop:8443/api/v3';
        $this->debug = $config['debug'] ?? false;
        $this->timeout = $config['timeout'] ?? 30000;
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client();

        $this->auth = new Auth($this, [
            'accessToken' => $config['accessToken'] ?? null,
            'refreshToken' => $config['refreshToken'] ?? null,
        ]);
        $this->offers = new Offers($this);
    }

    /**
     * @param array[mixed] $options Request options
     * @return array[mixed] Response from api, always json
     *
     * @example
     * <code>
     * $client->request(['method' => 'GET', 'url' => 'https://google.fr']);
     * </code>
     */
    public function request($options = [])
    {
        if ($this->clientId === null || $this->clientSecret === null) {
            throw new \Exception(
                'clientId and/or clientSecret were not provided. Please use ' .
                '`$client = new Client([ \'clientId\' => \'your_client_id\', ' .
                '\'clientSecret\' => \'your_client_secret\' ]);` ' .
                'in order to retrieve your entrepot objects.'
            );
        }

        $method = $options['method'] ?? 'GET';
        $headers = array_merge($options['headers'] ?? [], [
            'Content-Type' => 'application/json; charset=utf-8',
            'Platform' => 9,
        ]);

        if ($options['resource']) {
            $options['url'] = $this->apiUrl . $options['resource'];
            unset($options['resource']);
        }

        if ($method === 'GET') {
            $options['query'] = array_merge($options['query'] ?? [], [
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
            ]);
        }

        // Add authorization if found
        $accessToken = (isset($options['auth']) ? $options['auth']['accessToken'] : null) ?? $this->auth->accessToken;
        if (!empty($accessToken) && $headers['Authorization'] !== false) {
            $headers['Authorization'] = 'Bearer ' . $accessToken;
        }

        if (isset($options['json']) && $method !== 'GET') {
            $options['json']['clientId'] = $this->clientId;
            $options['json']['clientSecret'] = $this->clientSecret;
        }

        $response = $this->httpClient->request($method, $options['url'], array_merge($options, [
            'headers' => $headers,
            'timeout' => $this->timeout,
        ]));

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array[mixed] $options Request options
     * @return array[mixed] Response from api, always json
     *
     * @example
     * <code>
     * $client->requestWithRetry(['method' => 'GET', 'url' => 'https://google.fr']);
     * </code>
     */
    public function requestWithRetry($options)
    {
        try {
            $response = $this->request($options);
        } catch (RequestException $error) {
            if ($error->getResponse()->getStatusCode() === 403 && isset($this->auth->refreshToken)) {
                $tokens = $this->request([
                    'method' => 'POST',
                    'resource' => '/subscribe/auth/token',
                    'json' => [
                        'grantType' => 'refresh_token',
                        'refreshToken' => $this->getRefreshToken(),
                    ],
                ]);

                $this->auth->accessToken = $tokens['accessToken'];
                $this->auth->refreshToken = $tokens['refreshToken'];

                $response = $this->request($options);
            } else {
                throw $error;
            }
        }

        return $response;
    }
}
