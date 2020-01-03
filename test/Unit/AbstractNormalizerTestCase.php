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

use Ergebnis\Json\Normalizer\Json;
use Ergebnis\Json\Normalizer\NormalizerInterface;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 */
abstract class AbstractNormalizerTestCase extends Framework\TestCase
{
    use Helper;

    final public function testImplementsNormalizerInterface(): void
    {
        self::assertClassImplementsInterface(NormalizerInterface::class, static::className());
    }

    /**
     * @dataProvider providerJsonNotDecodingToObject
     *
     * @param string $encoded
     */
    final public function testNormalizeDoesNotModifyWhenJsonDecodedIsNotAnObject(string $encoded): void
    {
        $json = Json::fromEncoded($encoded);

        /** @var class-string $className */
        $className = static::className();

        $reflection = new \ReflectionClass($className);

        /** @var NormalizerInterface $normalizer */
        $normalizer = $reflection->newInstanceWithoutConstructor();

        $normalized = $normalizer->normalize($json);

        self::assertSame($json->encoded(), $normalized->encoded());
    }

    /**
     * @return \Generator<array<string>>
     */
    final public function providerJsonNotDecodingToObject(): \Generator
    {
        $faker = self::faker();

        $values = [
            'array' => $faker->words,
            'bool-false' => false,
            'bool-true' => true,
            'float' => $faker->randomFloat(),
            'int' => $faker->randomNumber(),
            'null' => null,
            'string' => $faker->sentence,
        ];

        foreach ($values as $key => $value) {
            $encoded = \json_encode($value);

            if (!\is_string($encoded)) {
                throw new \RuntimeException('Failed encoding a value to JSON.');
            }

            yield $key => [
                $encoded,
            ];
        }
    }

    final protected static function className(): string
    {
        $className = \preg_replace(
            '/Test$/',
            '',
            \str_replace(
                'Ergebnis\\Composer\\Json\\Normalizer\\Test\\Unit\\',
                'Ergebnis\\Composer\\Json\\Normalizer\\',
                static::class
            )
        );

        if (!\is_string($className)) {
            throw new \RuntimeException(\sprintf(
                'Unable to deduce source class name from test class name "%s".',
                static::class
            ));
        }

        return $className;
    }
}
