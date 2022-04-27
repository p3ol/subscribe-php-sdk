<?php

namespace Poool\Subscribe\SDK;

class Payments
{
    private Client $client;

    /**
     * @param Client $client - SDK client
     *
     * <code>
     * $payments = new Payments($client);
     * </code>
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Create a payment intent (needed to create a payment later)
     * @param string $gateway Gateway name (stripe, paypal)
     * @param string $offerId Offer ID or slug
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { intentId }
     *
     * <code>
     * $payments->createIntent('stripe', 'offer-1');
     * </code>
     */
    public function createIntent($gateway, $offerId, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/payments/' . $gateway . '/intent',
            'json' => [
                'offer' => $offerId,
            ],
        ]));
    }

    /**
     * Confirm a previously created payment intent
     * @param string $gateway Gateway name (stripe, paypal)
     * @param string $paymentId Payment ID
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { paymentId }
     *
     * <code>
     * $payments->confirmIntent('stripe', 'payment-1');
     * </code>
     */
    public function confirmIntent($gateway, $paymentId, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/payments/' . $gateway . '/intent/confirm/' . $paymentId,
        ]));
    }

    /**
     * Create a setup intent (needed to update a payment method)
     * @param string $gateway Gateway name (stripe, paypal)
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { intentSecret }
     *
     * <code>
     * $payments->createSetupIntent('stripe');
     * </code>
     */
    public function createSetupIntent($gateway, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/payments/' . $gateway . '/intent/setup',
        ]));
    }

    /**
     * Update a payment method
     * @param string $gateway Gateway name (stripe)
     * @param string $method Payment method ID
     * @param mixed[] $options (optional) Guzzle request options
     * @return mixed[] { paymentId }
     *
     * <code>
     * $payments->updateSource('stripe', 'pm_1');
     * </code>
     */
    public function updateSource($gateway, $method, $options = [])
    {
        return $this->client->requestWithRetry(array_merge($options, [
            'method' => 'POST',
            'resource' => '/subscribe/payments/' . $gateway . '/method/update',
            'json' => [
                'method' => $method,
            ],
        ]));
    }
}
