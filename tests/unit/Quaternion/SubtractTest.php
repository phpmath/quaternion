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
#[CoversMethod(Quaternion::class, 'subtract')]
final class SubtractTest extends TestCase
{
    public function testSubtractsComponents(): void
    {
        // Arrange
        $a = Quaternion::of(5, 6, 7, 8);
        $b = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $a->subtract($b);

        // Assert
        self::assertSame([4.0, 4.0, 4.0, 4.0], [$result->w(), $result->x(), $result->y(), $result->z()]);
    }
}
