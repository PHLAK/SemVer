SemVer
======

[![Latest Stable Version](https://img.shields.io/packagist/v/PHLAK/SemVer.svg)](https://packagist.org/packages/PHLAK/SemVer)
[![Total Downloads](https://img.shields.io/packagist/dt/PHLAK/SemVer.svg)](https://packagist.org/packages/PHLAK/SemVer)
[![Author](https://img.shields.io/badge/author-Chris%20Kankiewicz-blue.svg)](https://www.ChrisKankiewicz.com)
[![License](https://img.shields.io/packagist/l/PHLAK/SemVer.svg)](https://packagist.org/packages/PHLAK/SemVer)
[![Build Status](https://img.shields.io/travis/PHLAK/SemVer.svg)](https://travis-ci.org/PHLAK/SemVer)

[Semantic versioning](http://semver.org) helper library for PHP -- by, [Chris Kankiewicz](https://www.ChrisKankiewicz.com)

Introduction
------------

More info coming soon...

Requirements
------------

  - [PHP](https://php.net) >= 5.4


Install with Composer
---------------------

```bash
composer require phlak/semver
```

Initializing
------------

```php
use SemVer\SemVer;

$semver = new SemVer(); // Initilializes to 'v0.1.0'
```

Or initialize with a custom version by passing a version string on creation.
Accepts any valid semantic version string with or without a preceding 'v'.

```php
$semver = new SemVer('v1.2.3-alpha.5-sha.8d31ff4');
```

Usage
-----

#### Retrieve the version or individual values

```php
$semver->setVersion('v1.2.3-beta.4+007');

$semver->getVersion();     // v1.2.3-beta.4+007
$semver->getMajor();       // 1
$semver->getMinor();       // 2
$semver->getPatch();       // 3
$semver->getPreRelease();  // beta.4
$semver->getBuild();       // 007
```

#### Increment the version

```php
$semver->incrementMajor(); // v1.2.3 -> v2.0.0
$semver->incrementMinor(); // v1.2.3 -> v1.3.0
$semver->incrementPatch(); // v1.2.3 -> v1.2.4
```

#### Set (override) the version or individual values

```php
$semver->setVersion('v1.2.3');  // v1.2.3
$semver->setMajor(3);           // v1.2.3 -> v3.0.0
$semver->setMinor(5);           // v1.2.3 -> v1.4.0
$semver->setPatch(7);           // v1.2.3 -> 1.2.7
$semver->setPreRelease('rc.2'); // v1.2.3 -> v1.2.3-rc.2
$semver->setBuild('007');       // v1.2.3 -> v1.2.3+007
```

#### Clear pre-release / build values

```php
$semver->setPreRelease(null); // v1.2.3-rc.2 -> v1.2.3
$semver->setBuild(null);      // v1.2.3+007 -> v1.2.3
```

#### Compare two SemVer objects

```php
$semver1 = new SemVer('v1.2.3');
$semver2 = new SemVer('v3.2.1');

$semver1->gt($semver2);  // false
$semver1->lt($semver2);  // true
$semver1->eq($semver2);  // false
$semver1->neq($semver2); // true
$semver1->gte($semver2); // false
$semver1->lte($semver2); // true
```

Troubleshooting
---------------

Please report bugs to the [GitHub Issue Tracker](https://github.com/PHLAK/SemVer/issues).

Copyright
---------

This project is liscensed under the [MIT License](https://github.com/PHLAK/SemVer/blob/master/LICENSE).

