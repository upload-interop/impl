# Upload-Interop Reference Implementation Package

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)
[![PDS Composer Script Names](https://img.shields.io/badge/pds-composer--script--names-blue?style=flat-square)](https://github.com/php-pds/composer-script-names)

Reference implementation of [upload-interop/interface][].

## Installation

Install this package via [Composer][]:

```
$ composer require upload-interop/impl
```

## Usage

Create an array of _Upload_ instances from `$_FILES` like so:

```php
use UploadInterop\Impl\UploadFactory;

$uploads = new UploadFactory()->newUploadsFromFiles($_FILES);
```

* * *

[upload-interop/interface]: https://packagist.org/packages/upload-interop/interface
[Composer]: https://getcomposer.org
