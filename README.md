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

  - [PHP](https://php.net) >= 7.0


Install with Composer
---------------------

```bash
composer require phlak/semver
```

Initializing
------------

```php
use PHLAK\SemVer;

$version = new SemVer\Version(); // Initilializes to '0.1.0'
```

Or initialize with a custom version by passing a version string on creation.
Accepts any valid semantic version string with or without a preceding 'v'.

```php
$version = new SemVer\Version('v1.2.3-alpha.5-sha.8d31ff4');
```

Usage
-----

#### Retrieve the version or individual values

```php
$version = new SemVer\Version('v1.2.3-beta.4+007');

echo $version;             // '1.2.3-beta.4+007'
echo $version->major;      // 1
echo $version->minor;      // 2
echo $version->patch;      // 3
echo $version->preRelease; // 'beta.4'
echo $version->build;      // '007'
```

#### Increment the version

```php
$version = new SemVer\Version('v1.2.3');

$version->incrementMajor(); // v1.2.3 -> v2.0.0
$version->incrementMinor(); // v1.2.3 -> v1.3.0
$version->incrementPatch(); // v1.2.3 -> v1.2.4
```

#### Set (override) the version or individual values

```php
$version = new SemVer\Version();

$version->setVersion('v1.2.3');  // v1.2.3
$version->setMajor(3);           // v1.2.3 -> v3.0.0
$version->setMinor(5);           // v1.2.3 -> v1.5.0
$version->setPatch(7);           // v1.2.3 -> 1.2.7
$version->setPreRelease('rc.2'); // v1.2.3 -> v1.2.3-rc.2
$version->setBuild('007');       // v1.2.3 -> v1.2.3+007
```

#### Clear pre-release / build values

```php
$version->setPreRelease(null); // v1.2.3-rc.2 -> v1.2.3
$version->setBuild(null);      // v1.2.3+007 -> v1.2.3
```

#### Compare two SemVer objects

```php
$version1 = new SemVer('v1.2.3');
$version2 = new SemVer('v3.2.1');

$version1->gt($version2);  // false
$version1->lt($version2);  // true
$version1->eq($version2);  // false
$version1->neq($version2); // true
$version1->gte($version2); // false
$version1->lte($version2); // true
```

#### Tag Git Branch with SemVer

```php
$version = new SemVer\Version();

$version->setVersion('v1.2.3', true);  // v1.2.3 git tag: v1.2.3
$version->setMajor(3);           // v1.2.3 -> v3.0.0 git tag: v3.0.0
$version->setMinor(5);           // v1.2.3 -> v1.5.0 git tag: v1.5.0
$version->setPatch(7);           // v1.2.3 -> 1.2.7 git tag: v1.2.7
$version->setPreRelease('rc.2'); // v1.2.3 -> v1.2.3-rc.2 git tag: v1.2.3-rc.2
$version->setBuild('007');       // v1.2.3 -> v1.2.3+007 git tag: v1.2.3+007
```

Troubleshooting
---------------

Please report bugs to the [GitHub Issue Tracker](https://github.com/PHLAK/SemVer/issues).

Copyright
---------

This project is liscensed under the [MIT License](https://github.com/PHLAK/SemVer/blob/master/LICENSE).

