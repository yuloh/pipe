# Pipe

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]

A pipeline implementation

## Install

Via Composer

``` bash
$ composer require yuloh/pipe
```

## Usage

``` php
use Symfony\Component\HttpFoundation\Request;
use function Yuloh\Pipe\pipeline;

$req = Request::createFromGlobals();

pipeline()
    ->pipe(function ($req, $next) {

        if (str_contains($req->getPathInfo(), 'admin')) {
            app(AuditLogger::class)->log($req);
        }

        return $next($req);
    })
    ->pipe(function ($req, $next) {
        $res = $next($req);

        if ($res->getStatusCode() === 418) {
            app(LoggerInterface::class)->info('teapot response');
        }

        return $res;
    })
    ->__invoke($req);
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/yuloh/pipe.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/yuloh/pipe/master.svg?style=flat-square
