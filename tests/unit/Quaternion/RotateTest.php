<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Tests\Quaternion;

use PHPMath\Quaternion\Exception\InvalidVectorException;
use PHPMath\Quaternion\Exception\ZeroQuaternionException;
use PHPMath\Quaternion\Quaternion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use const M_PI;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'rotate')]
final class RotateTest extends TestCase
{
    public function testRotatesQuarterTurnAboutZ(): void
    {
        // Arrange
        $q = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

        // Act
        $result = $q->rotate([1.0, 0.0, 0.0]);

        // Assert
        self::assertEqualsWithDelta([0.0, 1.0, 0.0], $result, 1e-9);
    }

    public function testRotatesQuarterTurnAboutX(): void
    {
        // Arrange
        $q = Quaternion::fromAxisAngle([1.0, 0.0, 0.0], M_PI / 2);

        // Act
        $result = $q->rotate([0.0, 1.0, 0.0]);

        // Assert
        self::assertEqualsWithDelta([0.0, 0.0, 1.0], $result, 1e-9);
    }

    public function testIdentityKeepsVector(): void
    {
        // Arrange
        $q = Quaternion::identity();

        // Act
        $result = $q->rotate([1.0, 2.0, 3.0]);

        // Assert
        self::assertEqualsWithDelta([1.0, 2.0, 3.0], $result, 1e-9);
    }

    public function testMatchesSandwichProduct(): void
    {
        // Arrange
        $q = Quaternion::fromAxisAngle([1.0, 2.0, 3.0], 0.9);
        $p = Quaternion::of(0, 4, -5, 6);
        $expected = $q->multiply($p)->multiply($q->inverse());

        // Act
        $result = $q->rotate([4.0, -5.0, 6.0]);

        // Assert
        self::assertEqualsWithDelta([$expected->x(), $expected->y(), $expected->z()], $result, 1e-9);
    }

    public function testNormalizesNonUnitQuaternion(): void
    {
        // Arrange
        $q = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2)->scale(5.0);

        // Act
        $result = $q->rotate([1.0, 0.0, 0.0]);

        // Assert
        self::assertEqualsWithDelta([0.0, 1.0, 0.0], $result, 1e-9);
    }

    public function testThrowsOnZeroQuaternion(): void
    {
        // Arrange
        $q = Quaternion::of(0, 0, 0, 0);
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion cannot be used to rotate a vector.');

        // Act
        $q->rotate([1.0, 0.0, 0.0]);
    }

    public function testThrowsOnInvalidVectorSize(): void
    {
        // Arrange
        $q = Quaternion::identity();
        $this->expectException(InvalidVectorException::class);

        // Act
        // @phpstan-ignore argument.type
        $q->rotate([1.0, 0.0]);
    }

    public function testRejectsAVectorWithANonNumericComponent(): void
    {
        // Arrange
        $quaternion = Quaternion::identity();

        // Assert
        $this->expectException(InvalidVectorException::class);
        $this->expectExceptionMessage('A vector must be a list of exactly three floats.');

        // Act
        // @phpstan-ignore argument.type (the test passes an invalid component on purpose)
        $quaternion->rotate([1.0, 'two', 3.0]);
    }
}
