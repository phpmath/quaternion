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
#[CoversMethod(Quaternion::class, 'inverse')]
final class InverseTest extends TestCase
{
    public function testMultipliedWithOriginalGivesIdentity(): void
    {
        // Arrange
        $q = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $q->multiply($q->inverse());

        // Assert
        self::assertTrue(Quaternion::identity()->equals($result, 1e-9));
    }

    public function testInverseOfUnitIsConjugate(): void
    {
        // Arrange
        $q = Quaternion::of(1, 2, 3, 4)->normalize();

        // Act
        $result = $q->inverse();

        // Assert
        self::assertTrue($q->conjugate()->equals($result, 1e-9));
    }

    public function testThrowsOnZero(): void
    {
        // Arrange
        $q = Quaternion::of(0, 0, 0, 0);
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion has no inverse.');

        // Act
        $q->inverse();
    }
}
