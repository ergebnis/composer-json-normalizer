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

namespace Ergebnis\Composer\Json\Normalizer\Test\AutoReview;

use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @coversNothing
 */
final class TestCodeTest extends Framework\TestCase
{
    use Helper;

    public function testTestClassesAreAbstractOrFinal(): void
    {
        self::assertClassesAreAbstractOrFinal(__DIR__ . '/..');
    }
}
