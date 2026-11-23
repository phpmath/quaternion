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
#[CoversMethod(Quaternion::class, 'toRotationMatrix')]
final class ToRotationMatrixTest extends TestCase
{
    public function testIdentityGivesIdentityMatrix(): void
    {
        // Arrange
        $q = Quaternion::identity();

        // Act
        $result = $q->toRotationMatrix();

        // Assert
        self::assertEqualsWithDelta([[1.0, 0.0, 0.0], [0.0, 1.0, 0.0], [0.0, 0.0, 1.0]], $result, 1e-9);
    }

    public function testQuarterTurnAboutZ(): void
    {
        // Arrange
        $q = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

        // Act
        $result = $q->toRotationMatrix();

        // Assert
        self::assertEqualsWithDelta([[0.0, -1.0, 0.0], [1.0, 0.0, 0.0], [0.0, 0.0, 1.0]], $result, 1e-9);
    }

    public function testMatrixTimesVectorMatchesRotate(): void
    {
        // Arrange
        $q = Quaternion::fromAxisAngle([1.0, -2.0, 0.5], 1.3);
        $v = [0.5, 2.0, -1.0];
        $m = $q->toRotationMatrix();
        $expected = $q->rotate($v);

        // Act
        $result = [
            $m[0][0] * $v[0] + $m[0][1] * $v[1] + $m[0][2] * $v[2],
            $m[1][0] * $v[0] + $m[1][1] * $v[1] + $m[1][2] * $v[2],
            $m[2][0] * $v[0] + $m[2][1] * $v[1] + $m[2][2] * $v[2],
        ];

        // Assert
        self::assertEqualsWithDelta($expected, $result, 1e-9);
    }

    public function testThrowsOnZeroQuaternion(): void
    {
        // Arrange
        $q = Quaternion::of(0, 0, 0, 0);
        $this->expectException(ZeroQuaternionException::class);
        $this->expectExceptionMessage('A zero quaternion cannot be converted to a rotation matrix.');

        // Act
        $q->toRotationMatrix();
    }
}
