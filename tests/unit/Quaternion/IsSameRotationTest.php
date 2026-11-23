<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Tests\Quaternion;

use PHPMath\Quaternion\Exception\InvalidToleranceException;
use PHPMath\Quaternion\Exception\ZeroQuaternionException;
use PHPMath\Quaternion\Quaternion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use const INF;
use const NAN;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'isSameRotation')]
final class IsSameRotationTest extends TestCase
{
    public function testEqualQuaternionsAreTheSameRotation(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $a->isSameRotation($b);

        // Assert
        self::assertTrue($result);
    }

    public function testNegatedQuaternionIsTheSameRotation(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $a->isSameRotation($a->scale(-1.0));

        // Assert
        self::assertTrue($result);
    }

    public function testScaledQuaternionIsTheSameRotation(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $a->isSameRotation($a->scale(5.0));

        // Assert
        self::assertTrue($result);
    }

    public function testDifferentRotationsAreNotTheSame(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(1, 2, 3, 5);

        // Act
        $result = $a->isSameRotation($b);

        // Assert
        self::assertFalse($result);
    }

    public function testUsesCustomTolerance(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $b = Quaternion::of(1, 0, 0, 0.05);

        // Act
        $result = $a->isSameRotation($b, 0.1);

        // Assert
        self::assertTrue($result);
    }

    public function testThrowsWhenThisIsZero(): void
    {
        // Arrange
        $a = Quaternion::of(0, 0, 0, 0);
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion cannot be compared as a rotation.');

        // Act
        $a->isSameRotation(Quaternion::identity());
    }

    public function testThrowsWhenOtherIsZero(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion cannot be compared as a rotation.');

        // Act
        $a->isSameRotation(Quaternion::of(0, 0, 0, 0));
    }

    public function testThrowsOnNegativeTolerance(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $a->isSameRotation($a, -1.0);
    }

    public function testThrowsOnNanTolerance(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $a->isSameRotation($a, NAN);
    }

    public function testThrowsOnInfiniteTolerance(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $a->isSameRotation($a, INF);
    }
}
