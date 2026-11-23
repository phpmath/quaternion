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
#[CoversMethod(Quaternion::class, 'w')]
final class WTest extends TestCase
{
    public function testReturnsComponent(): void
    {
        // Arrange
        $q = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $q->w();

        // Assert
        self::assertSame(1.0, $result);
    }
}
