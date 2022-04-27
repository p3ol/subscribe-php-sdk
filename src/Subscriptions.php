<?php

namespace Poool\Subscribe\SDK;

class Subscriptions
{
    private Client $client;

    /**
     * @param Client $client - SDK client
     *
     * <code>
     * $subscriptions = new Subscriptions($client);
     * </code>
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Create a subscription intent (needed to create a subscription later)
     * @param string $gateway Gateway name (stripe, paypal)
     * @param string $offerId Offer ID or slug to subscribe to
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { intentId, subscriptionId }
     *
     * <code>
     * $subscriptions->createIntent('stripe', 'offer-1');
     * </code>
     */
    public function createIntent($gateway, $offerId, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/payments/' . $gateway . '/subscribe',
            'json' => [
                'offer' => $offerId,
            ],
        ]));
    }

    /**
     * Confirm a previously created subscription intent
     * @param string $gateway Gateway name (stripe, paypal)
     * @param string $subscriptionId Subscription ID
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { subscription }
     *
     * <code>
     * $subscriptions->confirmIntent('stripe', 'subscription-1');
     * </code>
     */
    public function confirmIntent($gateway, $subscriptionId, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/payments/' . $gateway . '/subscribe/confirm/' . $subscriptionId,
        ]));
    }

    /**
     * Subscribe the current user to a different offer (upgrade or downgrade)
     * @param string $subscriptionId Subscription ID
     * @param string $offerId Offer ID or slug to subscribe to
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { subscription }
     *
     * <code>
     * $subscriptions->switchOffer('subscription-1', 'offer-2');
     * </code>
     */
    public function switchOffer($subscriptionId, $offerId, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/subscriptions/' . $subscriptionId . '/change',
            'json' => [
                'offer' => $offerId,
            ],
        ]));
    }

    /**
     * Cancel a subscription
     * @param string $subscriptionId Subscription ID
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { subscription }
     *
     * <code>
     * $subscriptions->cancel('subscription-1');
     * </code>
     */
    public function cancel($subscriptionId, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/subscriptions/' . $subscriptionId . '/cancel',
        ]));
    }

    /**
     * Reactivate a canceled subscription
     * @param string $subscriptionId Subscription ID
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { subscription }
     *
     * <code>
     * $subscriptions->reactivate('subscription-1');
     * </code>
     */
    public function reactivate($subscriptionId, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/subscriptions/' . $subscriptionId . '/reactivate',
        ]));
    }
}
