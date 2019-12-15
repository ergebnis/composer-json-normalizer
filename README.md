# composer-json-normalizer

[![Continuous Integration](https://github.com/localheinz/composer-json-normalizer/workflows/Continuous%20Integration/badge.svg)](https://github.com/localheinz/composer-json-normalizer/actions)
[![Code Coverage](https://codecov.io/gh/localheinz/composer-json-normalizer/branch/master/graph/badge.svg)](https://codecov.io/gh/localheinz/composer-json-normalizer)
[![Latest Stable Version](https://poser.pugx.org/localheinz/composer-json-normalizer/v/stable)](https://packagist.org/packages/localheinz/composer-json-normalizer)
[![Total Downloads](https://poser.pugx.org/localheinz/composer-json-normalizer/downloads)](https://packagist.org/packages/localheinz/composer-json-normalizer)

Provides normalizers for normalizing [`composer.json`](https://getcomposer.org/doc/04-schema.md).

## Installation

Run

```
$ composer require localheinz/composer-json-normalizer
```

## Usage

Create an instance of `Localheinz\Composer\Json\Normalizer\ComposerJsonNormalizer`
and use it to normalize the contents of a `composer.json`:

```php
<?php

use Ergebnis\Json\Normalizer\Json;
use Localheinz\Composer\Json\Normalizer\ComposerJsonNormalizer;

$normalizer = new ComposerJsonNormalizer();

$json = Json::fromEncoded(file_get_contents(__DIR__ . '/composer.json'));

$normalized = $normalizer->normalize($json);

echo $normalized->encoded();
```

:bulb: Looking for the composer plugin? Head over to [`localheinz/composer-normalize`](https://github.com/localheinz/composer-normalize).

## Normalizers

The `ComposerJsonNormalizer` composes normalizers provided by [`localheinz/json-normalizer`](https://github.com/localheinz/json-normalizer):

* [`Ergebnis\Json\Normalizer\ChainNormalizer`](https://github.com/localheinz/json-normalizer#chainnormalizer)
* [`Ergebnis\Json\Normalizer\SchemaNormalizer`](https://github.com/localheinz/json-normalizer#schemanormalizer)

as well as the following normalizers provided by this package:

* [`Localheinz\Composer\Json\Normalizer\BinNormalizer`](#binnormalizer)
* [`Localheinz\Composer\Json\Normalizer\ConfigHashNormalizer`](#confighashnormalizer)
* [`Localheinz\Composer\Json\Normalizer\PackageHashNormalizer`](#packagehashnormalizer)
* [`Localheinz\Composer\Json\Normalizer\VersionConstraintNormalizer`](#versionconstraintnormalizer)

### `BinNormalizer`

If `composer.json` contains an array of scripts in the `bin` section,
the `BinNormalizer` will sort the elements of the `bin` section by value in ascending order.

:bulb: Find out more about the `bin` section at https://getcomposer.org/doc/04-schema.md#bin.

### `ConfigHashNormalizer`

If `composer.json` contains any configuration in the

* `config`
* `extra`
* `scripts-descriptions`

sections, the `ConfigHashNormalizer` will sort the content of these sections
by key in ascending order.

:bulb: Find out more about the `config` section at https://getcomposer.org/doc/06-config.md.

### `PackageHashNormalizer`

If `composer.json` contains any configuration in the

* `conflict`
* `provide`
* `replace`
* `require`
* `require-dev`
* `suggest`

sections, the `PackageHashNormalizer` will sort the content of these sections.

:bulb: This transfers the behaviour from using the `--sort-packages` or
`sort-packages` configuration flag to other sections. Find out more about
the `--sort-packages` flag and configuration at https://getcomposer.org/doc/06-config.md#sort-packages
and https://getcomposer.org/doc/03-cli.md#require.

### `VersionConstraintNormalizer`

If `composer.json` contains version constraints in the

* `conflict`
* `provide`
* `replace`
* `require`
* `require-dev`

sections, the `VersionConstraintNormalizer` will ensure that

* all constraints are trimmed
* *and* constraints are separated by a single space (` `) or a comma (`,`)
* *or* constraints are separated by double-pipe with a single space before and after (` || `)
* *range* constraints are separated by a single space (` `)

:bulb: Find out more about version constraints at https://getcomposer.org/doc/articles/versions.md.

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](.github/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

## Credits

The algorithm for sorting packages in the [`PackageHashNormalizer`](src/PackageHashNormalizer.php) has
been adopted from [`Composer\Json\JsonManipulator::sortPackages()`](https://github.com/composer/composer/blob/1.6.2/src/Composer/Json/JsonManipulator.php#L110-L146)
(originally licensed under MIT by [Nils Adermann](https://github.com/naderman) and [Jordi Boggiano](https://github.com/seldaek)),
which I initially contributed to `composer/composer` with [`composer/composer#3549`](https://github.com/composer/composer/pull/3549)
and [`composer/composer#3872`](https://github.com/composer/composer/pull/3872).
