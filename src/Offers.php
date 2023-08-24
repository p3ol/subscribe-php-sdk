<?php

namespace Poool\Subscribe\SDK;

class Offers
{
    private Client $client;

    /**
     * @param Client $client - SDK client
     *
     * <code>
     * $offers = new Offers($client);
     * </code>
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * List all available offers
     * @param int $page (optional) Current page of results
     * @param int $count (optional) Count per page
     * @param string $status (optional) Status of the offer (active, archived, all)
     * @param string[] $include (optional) List of included slugs
     * @param string[] $exclude (optional) List of excluded slugs
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Array of offers objects
     *
     * <code>
     * $offers->list(1, 10, 'active', false, ['slug1', 'slug2']);
     * </code>
     */
    public function list(
        $page = 1,
        $count = 10,
        $status = 'active',
        $sandbox = false,
        $include = [],
        $exclude = [],
        $reqOptions = []
    ) {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'GET',
            'resource' => '/subscribe/offers',
            'query' => [
                'page' => $page,
                'count' => $count,
                'status' => $status,
                'sandbox' => $sandbox,
                'include' => $include,
                'exclude' => $exclude,
            ],
        ]));
    }

    /**
     * Get a particular
     * @param string $id Offer ID or slug
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Offer object
     *
     * <code>
     * $offers->get('offer-1');
     * </code>
     */
    public function get($id, $reqOptions = [])
    {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'GET',
            'resource' => '/subscribe/offers/' . $id,
        ]));
    }
}
