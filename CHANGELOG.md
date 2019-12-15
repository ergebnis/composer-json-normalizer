# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`1.0.2...master`][1.0.2...master].

### Changed

* Started using `ergebnis/json-normalizer` instead of `localheinz/json-normalizer` ([#44]), by [@localheinz]

### Fixed

* Dropped support for PHP 7.1 ([#30]), by [@localheinz]
* Required implicit dependency `ext-json` explicitly ([#41]), by [@localheinz]

## [`1.0.2`][1.0.2]

For a full diff see [`1.0.1...1.0.2`][1.0.1...1.0.2].

### Removed

* Removed dependency on [`composer/composer`](https://github.com/composer/composer) ([#21]), by [@localheinz]

## [`1.0.1`][1.0.1]

For a full diff see [`1.0.0...1.0.1`][1.0.0...1.0.1].

### Fixed

* Removed duplicated conditions ([#2]), by [@localheinz]

## [`1.0.0`][1.0.0]

For a full diff see [`149a393...1.0.0`][149a393...1.0.0].

### Added

* Imported all of the normalizers from [`localheinz/json-normalizer`](https://github.com/localheinz/composer-normalize/tree/dcf55c24e2dfa49f7be594bfe50aa3c636b84501) ([#1]), by [@localheinz]

[1.0.0]: https://github.com/localheinz/composer-json-normalizer/releases/tag/1.0.0
[1.0.1]: https://github.com/localheinz/composer-json-normalizer/releases/tag/1.0.1
[1.0.2]: https://github.com/localheinz/composer-json-normalizer/releases/tag/1.0.2

[149a393...1.0.0]: https://github.com/localheinz/composer-json-normalizer/compare/149a393...1.0.0
[1.0.0...1.0.1]: https://github.com/localheinz/composer-json-normalizer/compare/1.0.0...1.0.1
[1.0.1...1.0.2]: https://github.com/localheinz/composer-json-normalizer/compare/1.0.1...1.0.2
[1.0.2...master]: https://github.com/localheinz/composer-json-normalizer/compare/1.0.2...master

[#1]: https://github.com/localheinz/composer-json-normalizer/pull/1
[#2]: https://github.com/localheinz/composer-json-normalizer/pull/2
[#21]: https://github.com/localheinz/composer-json-normalizer/pull/21
[#30]: https://github.com/localheinz/composer-json-normalizer/pull/30
[#41]: https://github.com/localheinz/composer-json-normalizer/pull/41
[#44]: https://github.com/localheinz/composer-json-normalizer/pull/44

[@localheinz]: https://github.com/localheinz
