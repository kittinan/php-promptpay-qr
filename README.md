# php-promptpay-qr
[![Build Status](https://travis-ci.org/kittinan/php-promptpay-qr.svg?branch=master)](https://travis-ci.org/kittinan/php-promptpay-qr)
[![Code Coverage](https://scrutinizer-ci.com/g/kittinan/php-promptpay-qr/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kittinan/php-promptpay-qr/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kittinan/php-promptpay-qr/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kittinan/php-promptpay-qr/?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

PHP Library to generate QR Code payload for PromptPay inspired from [dtinth/promptpay-qr](https://github.com/dtinth/promptpay-qr)

# Requirement
- PHP 5.6+

# Composer
Install the latest version with composer [Packagist](https://packagist.org/packages/kittinan/php-promptpay-qr)

```
composer require kittinan/php-promptpay-qr
```

# Usage

```php
$pp = new \KS\PromptPay();

//Generate PromptPay Payload
$target = '0899999999';
echo $pp->generatePayload($target); 
//00020101021129370016A000000677010111011300668999999995802TH53037646304FE29

//Generate PromptPay Payload With Amount
$target = '089-999-9999';
$amount = 420;
echo $pp->generatePayload($target, $amount);
//00020101021229370016A000000677010111011300668999999995802TH53037645406420.006304CF9E

//Generate QrCode PNG file
$target = '1-2345-67890-12-3';
$savePath = '/tmp/qrcode.png';
$pp->generateQrCode($savePath, $target);

//Generate QrCode With Amount
$amount = 420;
$pp->generateQrCode($savePath, $target, $amount);

//Set QrCode Size Pixel
$width = 1000;
$pp->generateQrCode($savePath, $target, $amount, $width);
```

# Contributing
Feel free to contribute on this project, I will be happy to work with you.

# License
The MIT License (MIT)
