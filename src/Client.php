<?php

namespace Poool\Subscribe\SDK;

use GuzzleHttp\Exception\RequestException;

class Client
{
    private $clientId;
    private $clientSecret;
    private $apiUrl;
    private $debug;
    private \GuzzleHttp\Client $httpClient;
    private $timeout;

    public Auth $auth;
    public Offers $offers;

    public function __construct($config, $httpClient = null)
    {
        $this->clientId = $config['clientId'];
        $this->clientSecret = $config['clientSecret'];
        $this->apiUrl = $config['apiUrl'] ?? 'https://api.poool.develop:8443/api/v3';
        $this->debug = $config['debug'] ?? false;
        $this->timeout = $config['timeout'] ?? 30000;
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client();

        $this->auth = new Auth($this);
        $this->offers = new Offers($this);
    }

    /**
     * @param mixed[] $options Request options
     * @return mixed[] Response from api, always json
     *
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

        if ($options['sandbox']) {
            $headers['Sandbox'] = true;
            unset($options['sandbox']);
        }

        if ($method === 'GET') {
            $options['query'] = array_merge($options['query'] ?? [], [
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
            ]);
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
}
