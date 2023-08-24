<?php

namespace Poool\Subscribe\SDK;

class Customers
{
    private Client $client;

    /**
     * @param Client $client - SDK client
     *
     * <code>
     * $customers = new Customers($client);
     * </code>
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Get a particular customer
     * @param string $id Customer ID
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Customer object
     *
     * <code>
     * $customers->get('customer-1');
     * </code>
     */
    public function get($id, $reqOptions = [])
    {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'GET',
            'resource' => '/subscribe/customers/' . $id,
        ]));
    }

    /**
     * Update a particular customer
     * @param string $id Customer ID
     * @param mixed[] $updates Updates to apply
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Customer object
     *
     * <code>
     * $customers->set('customer-1', [
     *     'firstName' => 'John',
     *     'lastName' => 'Doe',
     * ]);
     * </code>
     */
    public function set($id, $updates, $reqOptions = [])
    {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'POST',
            'resource' => '/subscribe/customers/' . $id,
            'json' => $updates,
        ]));
    }

    /**
     * Attach an existing stripe/paypal/... subscription to a customer
     * @param string $id Customer ID
     * @param string $gateway Gateway name (stripe, paypal)
     * @param mixed[] $subscriptionData Subscription data
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Customer object
     *
     * <code>
     * $customers->attachExistingSubscription('customer-1', 'stripe', [
     *     'subscriptionId' => 'sub_12345',
     *     'priceId' => 'price_12345',
     * ]);
     * </code>
     */
    public function attachExistingSubscription($id, $gateway, $subscriptionData, $reqOptions = [])
    {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'POST',
            'resource' => '/subscribe/customers/' . $id . '/subscriptions/' . $gateway . '/add/external',
            'json' => $subscriptionData,
        ]));
    }

    /**
     * Subscribe a customer to a different offer (upgrade or downgrade)
     * @param string $id Customer ID
     * @param string $subscriptionId Subscription ID
     * @param string $offerId Offer ID or slug
     * @param mixed[] $offerOptions (optional) Offer options
     *   - string priceId (optional) Price ID
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Customer object
     *
     * <code>
     * $customers->switchSubscriptionOffer('customer-1', 'subscription-1', 'offer-1', ['priceId' => 'price-1']]);
     * </code>
     */
    public function switchSubscriptionOffer($id, $subscriptionId, $offerId, $offerOptions = ['priceId' => null], $reqOptions = [])
    {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'POST',
            'resource' => '/subscribe/customers/' . $id . '/subscriptions/' . $subscriptionId . '/change',
            'json' => [
                'offer' => $offerId,
                'price' => $offerOptions['priceId']
            ],
        ]));
    }

    /**
     * Cancel a customer's subscription
     * @param string $id Customer ID
     * @param string $subscriptionId Subscription ID
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Customer object
     *
     * <code>
     * $customers->cancelSubscription('customer-1', 'subscription-1');
     * </code>
     */
    public function cancelSubscription($id, $subscriptionId, $reqOptions = [])
    {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'POST',
            'resource' => '/subscribe/customers/' . $id . '/subscriptions/' . $subscriptionId . '/cancel',
        ]));
    }

    /**
     * Reactivate a canceled customer's subscription
     * @param string $id Customer ID
     * @param string $subscriptionId Subscription ID
     * @param mixed[] $reqOptions (optional) Guzzle request options
     * @return mixed[] Customer object
     *
     * <code>
     * $customers->reactivateSubscription('customer-1', 'subscription-1');
     * </code>
     */
    public function reactivateSubscription($id, $subscriptionId, $reqOptions = [])
    {
        return $this->client->request(array_merge($reqOptions, [
            'method' => 'POST',
            'resource' => '/subscribe/customers/' . $id . '/subscriptions/' . $subscriptionId . '/reactivate',
        ]));
    }
}
