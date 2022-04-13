<?php

namespace Poool\Subscribe\SDK;

class Offers
{
    private $client;

    /**
     * @param Client $client - SDK client
     *
     * @example
     * <code>
     * $auth = new Offers($client);
     * </code>
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param int $page (optional) Current page of results
     * @param int $count (optional) Count per page
     * @param string $status (optional) Status of the offer (active, archived, all)
     * @param array[string] $include (optional) List of included slugs
     * @param array[string] $exclude (optional) List of excluded slugs
     * @param array[mixed] $options (optional) Guzzle request options
     * @return array[mixed] Returns generated auth tokens
     *
     * @example
     * <code>
     * $offers->list(1, 10, 'active', ['slug1', 'slug2']);
     * </code>
     */
    public function list($page = 1, $count = 10, $status = 'active', $include = [], $exclude = [], $options = [])
    {
        $tokens = $this->client->request(array_merge($options, [
            'method' => 'GET',
            'resource' => '/subscribe/offers',
            'query' => [
                'page' => $page,
                'count' => $count,
                'status' => $status,
                'include' => $include,
                'exclude' => $exclude,
            ],
        ]));

        return $tokens;
    }
}
