# PHP Netgsm Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bahricanli/Netgsm-php.svg?style=flat-square)](https://packagist.org/packages/bahricanli/Netgsm-php)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/bahricanli/Netgsm-php/master.svg?style=flat-square)](https://travis-ci.org/bahricanli/Netgsm-php)
[![StyleCI](https://styleci.io/repos/121802100/shield?branch=master)](https://styleci.io/repos/121802100)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bahricanli/Netgsm-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bahricanli/Netgsm-php/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/bahricanli/Netgsm-php/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/bahricanli/Netgsm-php/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/bahricanli/Netgsm-php.svg?style=flat-square)](https://packagist.org/packages/bahricanli/Netgsm-php)

This package provides an easy to use Netgsm service which can be used with both XML and Http apis.

Bu paket, hem XML hem Http API ile çalışan kullanımı kolay bir Netgsm servisi sağlar.

Dokümanın türkçe hali için: [BENIOKU](BENIOKU.md)

## Contents

- [Installation](#installation)
    - [Setting up the Netgsm service](#setting-up-the-Netgsm-service)
- [Usage](#usage)
    - [Available methods](#available-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install this package via composer:

``` bash
composer require bahricanli/Netgsm-php
```

### Setting up the Netgsm service

You will need to register to Netgsm to use this channel.

## Usage

First, boot the NetgsmService with your desired client implementation.
- **NetgsmXmlClient**
- **NetgsmHttpClient** (This is actually a Rest-Like client but the vendor names their API that way.)

```php
require __DIR__ . '/../vendor/autoload.php';

use BahriCanli\Netgsm\NetgsmService;
use BahriCanli\Netgsm\NetgsmService;
use BahriCanli\Netgsm\ShortMessageFactory;
use BahriCanli\Netgsm\Http\Clients\NetgsmXmlClient;
use BahriCanli\Netgsm\Http\Clients\NetgsmHttpClient;
use BahriCanli\Netgsm\ShortMessageCollectionFactory;

$service = new NetgsmService(new NetgsmXmlClient(
    'sms.Netgsm.net/xml',
    'username',
    'password',
    'outboxname'
), new ShortMessageFactory(), new ShortMessageCollectionFactory());

// ya da

$service = new NetgsmService(new NetgsmHttpClient(
    new GuzzleHttp\Client(),
    'https://sms.Netgsm.net/http',
    'username',
    'password',
    'outboxname'
), new ShortMessageFactory(), new ShortMessageCollectionFactory());
```

### Available methods

After successfully booting your NetgsmService instance up; use one of the following methods to send SMS message(s).

#### One Message - Single or Multiple Recipients:

```php
$response = $service->sendShortMessage(['5530000000', '5420000000'], 'This is a test message.');

if($response->isSuccessful()) {
    // storeGroupIdForLaterReference is not included in the package.
    storeGroupIdForLaterReference($response->groupId());
} else {
    var_dump($response->message());
    var_dump($response->statusCode());
    var_dump($response->status());
}
```

#### Multiple Messages - Multiple Recipients:

Please not that if you have using that method, every message should only have one receiver. _(This is also an API limitation which I didn't hack.)_

```php
$response2 = $service->sendShortMessages([[
    'recipient' => '5530000000',
    'message' => 'This is a test.',
], [
    'recipient' => '5420000000',
    'message' => 'This is another test.',
]]);

if($response2->isSuccessful()) {
    // storeGroupIdForLaterReference is not included in the package.
    storeGroupIdForLaterReference($response2->groupId());
} else {
    var_dump($response2->message());
    var_dump($response2->statusCode());
    var_dump($response2->status());
}
```

### Cross Reference

`$response->groupId()` will throw BadMethodCallException if the client is `NetgsmHttpClient`.
`$response->messageReportIdentifiers()` will throw BadMethodCallException if the client is `NetgsmXmlClient`.

change client implementation with caution.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email bahri@bahri.info instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Bahri Meriç Canlı](https://github.com/bahricanli)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
