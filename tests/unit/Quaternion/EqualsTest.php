<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Tests\Quaternion;

use PHPMath\Quaternion\Exception\InvalidToleranceException;
use PHPMath\Quaternion\Quaternion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use const INF;
use const NAN;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'equals')]
final class EqualsTest extends TestCase
{
    public function testEqualComponentsAreEqual(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $a->equals($b);

        // Assert
        self::assertTrue($result);
    }

    public function testWithinToleranceIsEqual(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(1.0000000001, 2, 3, 4);

        // Act
        $result = $a->equals($b);

        // Assert
        self::assertTrue($result);
    }

    public function testOutsideToleranceIsNotEqual(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(1, 2, 3, 4.1);

        // Act
        $result = $a->equals($b);

        // Assert
        self::assertFalse($result);
    }

    public function testUsesCustomTolerance(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(1, 2, 3, 4.1);

        // Act
        $result = $a->equals($b, 0.5);

        // Assert
        self::assertTrue($result);
    }

    public function testNegatedQuaternionIsNotEqual(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $a->equals($a->scale(-1.0));

        // Assert
        self::assertFalse($result);
    }

    public function testThrowsOnNegativeTolerance(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $a->equals($a, -0.1);
    }

    public function testThrowsOnNanTolerance(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $a->equals($a, NAN);
    }

    public function testThrowsOnInfiniteTolerance(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $a->equals($a, INF);
    }

    public function testZeroToleranceIsAllowed(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $a->equals(Quaternion::of(1, 2, 3, 4), 0.0);

        // Assert
        self::assertTrue($result);
    }
}
