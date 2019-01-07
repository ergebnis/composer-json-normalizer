<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/composer-json-normalizer
 */

namespace Localheinz\Composer\Json\Normalizer\Test\Unit;

use Localheinz\Composer\Json\Normalizer\BinNormalizer;
use Localheinz\Composer\Json\Normalizer\ComposerJsonNormalizer;
use Localheinz\Composer\Json\Normalizer\ConfigHashNormalizer;
use Localheinz\Composer\Json\Normalizer\PackageHashNormalizer;
use Localheinz\Composer\Json\Normalizer\VersionConstraintNormalizer;
use Localheinz\Json\Normalizer\ChainNormalizer;
use Localheinz\Json\Normalizer\Json;
use Localheinz\Json\Normalizer\NormalizerInterface;
use Localheinz\Json\Normalizer\SchemaNormalizer;

/**
 * @internal
 */
final class ComposerJsonNormalizerTest extends AbstractNormalizerTestCase
{
    public function testComposesNormalizers(): void
    {
        $normalizer = new ComposerJsonNormalizer();

        $this->assertComposesNormalizer(ChainNormalizer::class, $normalizer);

        $chainNormalizer = $this->composedNormalizer($normalizer);

        $normalizerClassNames = [
            SchemaNormalizer::class,
            BinNormalizer::class,
            ConfigHashNormalizer::class,
            PackageHashNormalizer::class,
            VersionConstraintNormalizer::class,
        ];

        $this->assertComposesNormalizers($normalizerClassNames, $chainNormalizer);

        $chainedNormalizers = $this->composedNormalizers($chainNormalizer);

        $schemaNormalizer = \array_shift($chainedNormalizers);

        self::assertInstanceOf(SchemaNormalizer::class, $schemaNormalizer);
    }

    public function testNormalizeNormalizes(): void
    {
        $json = Json::fromEncoded(
<<<'JSON'
{
  "name": "foo/bar",
  "description": "In der Fantasie geht alles",
  "type": "library",
  "license": "MIT",
  "keywords": [
    "null",
    "helmut",
    "körschgen"
  ],
  "authors": [
    {
      "role": "Lieutenant",
      "homepage": "http://example.org",
      "name": "Helmut Körschgen"
    }
  ],
  "config": {
    "sort-packages": true,
    "preferred-install": "dist"
  },
  "repositories": [
    {
      "url": "git@github.com:localheinz/test-util",
      "type": "vcs"
    }
  ],
  "require": {
    "localheinz/json-printer": "^1.0.0",
    "php": "^7.0"
  },
  "require-dev": {
    "localheinz/test-util": "0.6.1",
    "phpunit/phpunit": "^6.5.5",
    "localheinz/php-cs-fixer-config": "~1.0.0|~1.11.0"
  },
  "autoload": {
    "psr-4": {
      "": "/foo",
      "Helmut\\Foo\\Bar\\": "src/"
    }
  },
  "scripts": {
    "foo": "foo.sh",
    "bar": "bar.sh",
    "post-install-cmd": "@foo",
    "pre-install-cmd": [
      "@foo",
      "@bar"
    ]
  },
  "scripts-descriptions": {
    "foo": "Executes foo.sh",
    "bar": "Executes bar.sh",
    "post-install-cmd": "Runs foo",
    "pre-install-cmd": "Runs foo and bar"
  },
  "autoload-dev": {
    "psr-4": {
      "Helmut\\Foo\\Bar\\Test\\": "test/"
    }
  },
  "bin": [
    "scripts/null-null.php",
    "hasenbein.php"
  ]
}
JSON
        );

        $expected = Json::fromEncoded(
<<<'JSON'
{
  "name": "foo/bar",
  "type": "library",
  "description": "In der Fantasie geht alles",
  "keywords": [
    "null",
    "helmut",
    "körschgen"
  ],
  "license": "MIT",
  "authors": [
    {
      "name": "Helmut Körschgen",
      "homepage": "http://example.org",
      "role": "Lieutenant"
    }
  ],
  "require": {
    "php": "^7.0",
    "localheinz/json-printer": "^1.0.0"
  },
  "require-dev": {
    "localheinz/php-cs-fixer-config": "~1.0.0 || ~1.11.0",
    "localheinz/test-util": "0.6.1",
    "phpunit/phpunit": "^6.5.5"
  },
  "config": {
    "preferred-install": "dist",
    "sort-packages": true
  },
  "autoload": {
    "psr-4": {
      "": "/foo",
      "Helmut\\Foo\\Bar\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Helmut\\Foo\\Bar\\Test\\": "test/"
    }
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:localheinz/test-util"
    }
  ],
  "bin": [
    "hasenbein.php",
    "scripts/null-null.php"
  ],
  "scripts": {
    "pre-install-cmd": [
      "@foo",
      "@bar"
    ],
    "post-install-cmd": "@foo",
    "bar": "bar.sh",
    "foo": "foo.sh"
  },
  "scripts-descriptions": {
    "bar": "Executes bar.sh",
    "foo": "Executes foo.sh",
    "post-install-cmd": "Runs foo",
    "pre-install-cmd": "Runs foo and bar"
  }
}
JSON
        );

        $normalizer = new ComposerJsonNormalizer(\sprintf(
            'file://%s',
            \realpath(__DIR__ . '/../Fixture/composer-schema.json')
        ));

        $normalized = $normalizer->normalize($json);

        self::assertSame(\json_encode(\json_decode($expected->encoded())), $normalized->encoded());
    }

    private function assertComposesNormalizer(string $className, NormalizerInterface $normalizer): void
    {
        $this->assertClassExists($className);
        $this->assertClassImplementsInterface(NormalizerInterface::class, $className);

        $attributeName = 'normalizer';

        self::assertObjectHasAttribute($attributeName, $normalizer, \sprintf(
            'Failed asserting that a normalizer has an attribute "%s".',
            $attributeName
        ));

        $composedNormalizer = $this->composedNormalizer($normalizer);

        self::assertInstanceOf($className, $composedNormalizer, \sprintf(
            'Failed asserting that a normalizer composes a normalizer of type "%s".',
            $className
        ));
    }

    private function assertComposesNormalizers(array $classNames, NormalizerInterface $normalizer): void
    {
        foreach ($classNames as $className) {
            $this->assertClassExists($className);
            $this->assertClassImplementsInterface(NormalizerInterface::class, $className);
        }

        $attributeName = 'normalizers';

        self::assertObjectHasAttribute($attributeName, $normalizer, \sprintf(
            'Failed asserting that a normalizer has an attribute "%s".',
            $attributeName
        ));

        $composedNormalizers = $this->composedNormalizers($normalizer);

        $composedNormalizerClassNames = \array_map(static function ($normalizer) {
            return \get_class($normalizer);
        }, $composedNormalizers);

        self::assertSame(
            $classNames,
            $composedNormalizerClassNames,
            'Failed asserting that a normalizer composes normalizers as expected.'
        );
    }

    private function composedNormalizer(NormalizerInterface $normalizer): NormalizerInterface
    {
        $reflection = new \ReflectionObject($normalizer);

        $property = $reflection->getProperty('normalizer');

        $property->setAccessible(true);

        return $property->getValue($normalizer);
    }

    private function composedNormalizers(NormalizerInterface $normalizer): array
    {
        $reflection = new \ReflectionObject($normalizer);

        $property = $reflection->getProperty('normalizers');

        $property->setAccessible(true);

        return $property->getValue($normalizer);
    }
}
