<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Tests\Quaternion;

use PHPMath\Quaternion\Exception\InvalidAxisException;
use PHPMath\Quaternion\Exception\InvalidVectorException;
use PHPMath\Quaternion\Quaternion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use const M_PI;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'fromAxisAngle')]
final class FromAxisAngleTest extends TestCase
{
    public function testCreatesFromArrayAxis(): void
    {
        // Arrange
        $angle = M_PI;

        // Act
        $result = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], $angle);

        // Assert
        self::assertTrue(Quaternion::of(0, 0, 0, 1)->equals($result, 1e-9));
    }

    public function testNormalizesAxis(): void
    {
        // Arrange
        $angle = M_PI / 3;

        // Act
        $result = Quaternion::fromAxisAngle([0.0, 0.0, 10.0], $angle);

        // Assert
        self::assertTrue(Quaternion::fromAxisAngle([0.0, 0.0, 1.0], $angle)->equals($result, 1e-9));
    }

    public function testResultIsUnit(): void
    {
        // Arrange
        $angle = 1.234;

        // Act
        $result = Quaternion::fromAxisAngle([1.0, 2.0, 3.0], $angle);

        // Assert
        self::assertEqualsWithDelta(1.0, $result->norm(), 1e-12);
    }

    public function testThrowsOnZeroAxis(): void
    {
        // Arrange
        $this->expectException(InvalidAxisException::class);

        // Act
        Quaternion::fromAxisAngle([0.0, 0.0, 0.0], 1.0);
    }

    public function testThrowsOnArrayWithWrongSize(): void
    {
        // Arrange
        $this->expectException(InvalidVectorException::class);

        // Act
        // @phpstan-ignore argument.type
        Quaternion::fromAxisAngle([1.0, 0.0], 1.0);
    }
}
