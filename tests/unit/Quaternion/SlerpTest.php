<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Tests\Quaternion;

use PHPMath\Quaternion\Exception\ZeroQuaternionException;
use PHPMath\Quaternion\Quaternion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use const M_PI;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'slerp')]
final class SlerpTest extends TestCase
{
    public function testZeroReturnsStart(): void
    {
        // Arrange
        $a = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], 0.2);
        $b = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], 2.0);

        // Act
        $result = $a->slerp($b, 0.0);

        // Assert
        self::assertTrue($a->equals($result, 1e-9));
    }

    public function testOneReturnsEnd(): void
    {
        // Arrange
        $a = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], 0.2);
        $b = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], 2.0);

        // Act
        $result = $a->slerp($b, 1.0);

        // Assert
        self::assertTrue($b->equals($result, 1e-9));
    }

    public function testHalfwayBisectsAngle(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $b = Quaternion::fromAxisAngle([0.0, 1.0, 0.0], M_PI / 2);

        // Act
        $result = $a->slerp($b, 0.5);

        // Assert
        self::assertTrue(Quaternion::fromAxisAngle([0.0, 1.0, 0.0], M_PI / 4)->equals($result, 1e-9));
    }

    public function testTakesShortestArc(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $b = Quaternion::fromAxisAngle([0.0, 1.0, 0.0], M_PI / 2)->scale(-1.0);

        // Act
        $result = $a->slerp($b, 0.5);

        // Assert
        self::assertTrue(Quaternion::fromAxisAngle([0.0, 1.0, 0.0], M_PI / 4)->equals($result, 1e-9));
    }

    public function testCloseQuaternionsStayUnit(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $b = Quaternion::fromAxisAngle([1.0, 0.0, 0.0], 1e-4);

        // Act
        $result = $a->slerp($b, 0.5);

        // Assert
        self::assertEqualsWithDelta(1.0, $result->norm(), 1e-12);
    }

    public function testThrowsOnZeroQuaternion(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion cannot be used for interpolation.');

        // Act
        $a->slerp(Quaternion::of(0, 0, 0, 0), 0.5);
    }

    public function testThrowsWhenStartIsZero(): void
    {
        // Arrange
        $a = Quaternion::of(0, 0, 0, 0);
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion cannot be used for interpolation.');

        // Act
        $a->slerp(Quaternion::identity(), 0.5);
    }

    public function testMayReturnEndWithFlippedSigns(): void
    {
        // Arrange
        $a = Quaternion::identity();
        $b = Quaternion::of(-1, 0, 0, 0);

        // Act
        $result = $a->slerp($b, 1.0);

        // Assert
        self::assertTrue($result->isSameRotation($b));
        self::assertFalse($result->equals($b));
    }
}
