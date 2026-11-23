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
#[CoversMethod(Quaternion::class, 'add')]
final class AddTest extends TestCase
{
    public function testAddsComponents(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);
        $b = Quaternion::of(5, 6, 7, 8);

        // Act
        $result = $a->add($b);

        // Assert
        self::assertSame([6.0, 8.0, 10.0, 12.0], [$result->w(), $result->x(), $result->y(), $result->z()]);
    }

    public function testDoesNotChangeOriginal(): void
    {
        // Arrange
        $a = Quaternion::of(1, 2, 3, 4);

        // Act
        $a->add(Quaternion::of(1, 1, 1, 1));

        // Assert
        self::assertSame(1.0, $a->w());
    }
}
