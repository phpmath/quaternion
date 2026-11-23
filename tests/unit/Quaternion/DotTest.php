<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Tests\Quaternion;

use PHPMath\Quaternion\Quaternion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'dot')]
final class DotTest extends TestCase
{
    public function testReturnsSumOfComponentProducts(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(5, 6, 7, 8);

        // Act
        $result = $a->dot($b);

        // Assert
        self::assertSame(70.0, $result);
    }
}
