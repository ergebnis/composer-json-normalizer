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

namespace Localheinz\Composer\Json\Normalizer\Test\AutoReview;

use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 * @coversNothing
 */
final class TestCodeTest extends Framework\TestCase
{
    use Helper;

    public function testTestClassesAreAbstractOrFinal(): void
    {
        $this->assertClassesAreAbstractOrFinal(__DIR__ . '/..');
    }
}
