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
#[CoversMethod(Quaternion::class, 'multiply')]
final class MultiplyTest extends TestCase
{
    public function testMultipliesWithHamiltonProduct(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(5, 6, 7, 8);

        // Act
        $result = $a->multiply($b);

        // Assert
        self::assertSame([-60.0, 12.0, 30.0, 24.0], [$result->w(), $result->x(), $result->y(), $result->z()]);
    }

    public function testIsNotCommutative(): void
    {
        // Arrange
        $i = Quaternion::of(0, 1, 0, 0);
        $j = Quaternion::of(0, 0, 1, 0);

        // Act
        $result = $j->multiply($i);

        // Assert
        // ji = -k
        self::assertSame([0.0, 0.0, 0.0, -1.0], [$result->w(), $result->x(), $result->y(), $result->z()]);
    }

    public function testMultiplyingByIdentityKeepsValue(): void
    {
        // Arrange
        $q = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $q->multiply(Quaternion::identity());

        // Assert
        self::assertTrue($q->equals($result, 1e-9));
    }
}
