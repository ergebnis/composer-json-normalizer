<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/composer-json-normalizer
 */

namespace Ergebnis\Composer\Json\Normalizer\Test\Unit;

use Ergebnis\Composer\Json\Normalizer\BinNormalizer;
use Ergebnis\Json\Normalizer\Json;

/**
 * @internal
 *
 * @covers \Ergebnis\Composer\Json\Normalizer\BinNormalizer
 */
final class BinNormalizerTest extends AbstractNormalizerTestCase
{
    public function testNormalizeDoesNotModifyOtherProperty(): void
    {
        $json = Json::fromEncoded(
            <<<'JSON'
{
  "foo": {
    "qux": "quux",
    "bar": "baz"
  }
}
JSON
        );

        $normalizer = new BinNormalizer();

        $normalized = $normalizer->normalize($json);

        self::assertSame($json->encoded(), $normalized->encoded());
    }

    public function testNormalizeDoesNotModifyBinIfPropertyExistsAsString(): void
    {
        $json = Json::fromEncoded(
            <<<'JSON'
{
  "bin": "foo.php",
  "foo": {
    "qux": "quux",
    "bar": "baz"
  }
}
JSON
        );

        $normalizer = new BinNormalizer();

        $normalized = $normalizer->normalize($json);

        self::assertSame($json->encoded(), $normalized->encoded());
    }

    public function testNormalizeSortsBinIfPropertyExistsAsArray(): void
    {
        $json = Json::fromEncoded(
            <<<'JSON'
{
  "bin": [
    "script.php",
    "another-script.php"
  ],
  "foo": {
    "qux": "quux",
    "bar": "baz"
  }
}
JSON
        );

        $expected = Json::fromEncoded(
            <<<'JSON'
{
  "bin": [
    "another-script.php",
    "script.php"
  ],
  "foo": {
    "qux": "quux",
    "bar": "baz"
  }
}
JSON
        );

        $normalizer = new BinNormalizer();

        $normalized = $normalizer->normalize($json);

        self::assertSame(\json_encode(\json_decode($expected->encoded())), $normalized->encoded());
    }
}
