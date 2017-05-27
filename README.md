# Juration PHP Library

PHP version of the [Dom Christie's](https://github.com/domchristie) javascript library: [juration-js](https://github.com/domchristie/juration)

This library parses text and extracts the duration as seconds integer but unlike the javascript version it does not use regular expressions to match time patterns so helpful pull requests will be appreciated!

### Installation
```sh
composer require bagf/juration-php
```

### Testing
```sh
composer install
composer test
```

### Usage
```php
Juration\Juration::parse('2min'); // 120
```
