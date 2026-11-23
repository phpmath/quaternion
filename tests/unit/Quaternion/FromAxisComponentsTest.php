<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Tests\Quaternion;

use PHPMath\Quaternion\Exception\InvalidAxisException;
use PHPMath\Quaternion\Quaternion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use const M_PI;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'fromAxisComponents')]
final class FromAxisComponentsTest extends TestCase
{
    public function testCreatesFromComponents(): void
    {
        // Arrange
        $angle = M_PI;

        // Act
        $result = Quaternion::fromAxisComponents(0.0, 1.0, 0.0, $angle);

        // Assert
        self::assertTrue(Quaternion::of(0, 0, 1, 0)->equals($result, 1e-9));
    }

    public function testNormalizesAxis(): void
    {
        // Arrange
        $angle = M_PI / 3;

        // Act
        $result = Quaternion::fromAxisComponents(0.0, 0.0, 10.0, $angle);

        // Assert
        self::assertTrue(Quaternion::fromAxisAngle([0.0, 0.0, 1.0], $angle)->equals($result, 1e-9));
    }

    public function testThrowsOnZeroAxis(): void
    {
        // Arrange
        $this->expectException(InvalidAxisException::class);
        $this->expectExceptionMessage('The rotation axis must not have zero length.');

        // Act
        Quaternion::fromAxisComponents(0.0, 0.0, 0.0, 1.0);
    }
}
