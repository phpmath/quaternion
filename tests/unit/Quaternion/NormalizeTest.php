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

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'normalize')]
final class NormalizeTest extends TestCase
{
    public function testReturnsUnitQuaternion(): void
    {
        // Arrange
        $q = Quaternion::of(1, 2, 2, 4);

        // Act
        $result = $q->normalize();

        // Assert
        self::assertEqualsWithDelta(1.0, $result->norm(), 1e-12);
    }

    public function testKeepsDirection(): void
    {
        // Arrange
        $q = Quaternion::of(0, 0, 3, 0);

        // Act
        $result = $q->normalize();

        // Assert
        self::assertTrue(Quaternion::of(0, 0, 1, 0)->equals($result, 1e-9));
    }

    public function testThrowsOnZero(): void
    {
        // Arrange
        $q = Quaternion::of(0, 0, 0, 0);
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion cannot be normalized.');

        // Act
        $q->normalize();
    }
}
