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

use const M_PI;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'fromEuler')]
final class FromEulerTest extends TestCase
{
    public function testZeroAnglesGiveIdentity(): void
    {
        // Act
        $result = Quaternion::fromEuler(0.0, 0.0, 0.0);

        // Assert
        self::assertTrue(Quaternion::identity()->equals($result, 1e-9));
    }

    public function testYawRotatesAboutZ(): void
    {
        // Act
        $result = Quaternion::fromEuler(M_PI / 2, 0.0, 0.0);

        // Assert
        self::assertTrue(Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2)->equals($result, 1e-9));
    }

    public function testPitchRotatesAboutY(): void
    {
        // Act
        $result = Quaternion::fromEuler(0.0, M_PI / 2, 0.0);

        // Assert
        self::assertTrue(Quaternion::fromAxisAngle([0.0, 1.0, 0.0], M_PI / 2)->equals($result, 1e-9));
    }

    public function testRollRotatesAboutX(): void
    {
        // Act
        $result = Quaternion::fromEuler(0.0, 0.0, M_PI / 2);

        // Assert
        self::assertTrue(Quaternion::fromAxisAngle([1.0, 0.0, 0.0], M_PI / 2)->equals($result, 1e-9));
    }

    public function testEqualsProductOfAxisRotations(): void
    {
        // Arrange
        $yaw = 0.3;
        $pitch = -0.7;
        $roll = 1.1;
        $expected = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], $yaw)
            ->multiply(Quaternion::fromAxisAngle([0.0, 1.0, 0.0], $pitch))
            ->multiply(Quaternion::fromAxisAngle([1.0, 0.0, 0.0], $roll));

        // Act
        $result = Quaternion::fromEuler($yaw, $pitch, $roll);

        // Assert
        self::assertTrue($expected->equals($result, 1e-9));
    }
}
