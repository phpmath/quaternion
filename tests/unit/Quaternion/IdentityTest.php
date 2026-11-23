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
#[CoversMethod(Quaternion::class, 'identity')]
final class IdentityTest extends TestCase
{
    public function testReturnsOneAndZeros(): void
    {
        // Act
        $q = Quaternion::identity();

        // Assert
        self::assertSame([1.0, 0.0, 0.0, 0.0], [$q->w(), $q->x(), $q->y(), $q->z()]);
    }
}
