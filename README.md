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
Juration\Juration::parse('2min and 1hr'); // 3720
Juration\Juration::stringify(12); // 12 secs
Juration\Juration::stringify(184); // 3 mins 4 secs
Juration\Juration::stringify(8400); // 2 hrs 20 mins
Juration\Juration::stringify(15854400); // 6 mths 1 day
```
