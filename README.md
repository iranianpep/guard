# Guard
[![Build Status](https://travis-ci.org/iranianpep/guard.svg?branch=master)](https://travis-ci.org/iranianpep/guard)
[![Maintainability](https://api.codeclimate.com/v1/badges/e460d53b2ec097644d4d/maintainability)](https://codeclimate.com/github/iranianpep/guard/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/e460d53b2ec097644d4d/test_coverage)](https://codeclimate.com/github/iranianpep/guard/test_coverage)

Application-level defender against blocked entities such as IP, email, ...

## Requirements
- PHP >= 7.0.0

## Usage
-  Install via composer:
```
composer require guard/guard
```

- To block an entity for example an IP:
```
$guard = new Guard();

// instantiate a mongoDB driver
$mongoDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');

// push the mongoDB driver to the guard object - at the moment mongoDB is the only available driver
$guard->pushDriver($mongoDriver);

// block an IP
$guard->block('ip', '127.0.0.1');
```

- To check an entity for example an IP is blocked:
```
if ($guard->isBlock('ip', '127.0.0.1') === true) {
    // ip 127.0.0.1 is blocked
}
```

- To unblock an entity for example an IP:
```
$guard->unBlock('ip', '127.0.0.1');
```