# Poool Subscribe - PHP SDK

Poool Subscribe SDK for PHP 🚀


## Installation

```
composer require poool/subscribe-sdk
```


## Usage

```php
use Poool\Subscribe\SDK\Client;

$client = new Client([
    'clientId' => 'yourClientId',
    'clientSecret' => 'yourClientSecret',
]);
var_dump($client->offers->list());
```


## [Documentation](https://poool.dev/docs/subscribe/server)

https://poool.dev/docs/subscribe/server


## Sandbox mode

You can use the sandbox mode to test your integration without any real payment.
Pass the `sandbox: true` request option (last parameter) to any method of the SDK to use it:

```javascript
$client->offers->list(1, 10, [], [], 'active', ['sandbox' => true]);
```

## Migrations

## v1 to v2

- `customers.switchSubscriptionOffer(customerId, subscriptionId, offer, requestOptions)` becomes `customers.switchSubscriptionOffer(customerId, subscriptionId, offer, **{ priceId }**, requestOptions)` -> it now takes an additional parameter before request options to speficy offer options like price id
- `sandbox: true|false` is now an option on every request (instead of only offers list) and has been removed from `offers.list()`
- `offers.list(page, count, status, include, exclude)` becomes `offers.list(page, count, include, exclude, **status**, requestOptions)` -> it is moved to the end of the parameters list to be consistent with other SDKs.

## Contributing

[![](https://contrib.rocks/image?repo=p3ol/subscribe-php-sdk)](https://github.com/p3ol/subscribe-php-sdk/graphs/contributors)

Please check the [CONTRIBUTING.md](https://github.com/p3ol/subscribe-php-sdk/blob/main/CONTRIBUTING.md) doc for contribution guidelines.


## Development

Install dependencies:

```bash
composer install
```

Run examples at http://localhost:62000/ with php built-in server:

```bash
composer serve
```

And test your code:

```bash
composer test
```


## License

This software is licensed under [MIT](https://github.com/p3ol/subscribe-php-sdk/blob/main/LICENSE).
