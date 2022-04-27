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
