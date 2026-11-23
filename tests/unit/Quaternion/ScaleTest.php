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

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, 'scale')]
final class ScaleTest extends TestCase
{
    public function testScalesComponents(): void
    {
        // Arrange
        $q = Quaternion::of(1, -2, 3, -4);

        // Act
        $result = $q->scale(2.0);

        // Assert
        self::assertSame([2.0, -4.0, 6.0, -8.0], [$result->w(), $result->x(), $result->y(), $result->z()]);
    }
}
