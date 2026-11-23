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
#[CoversMethod(Quaternion::class, 'conjugate')]
final class ConjugateTest extends TestCase
{
    public function testNegatesVectorPart(): void
    {
        // Arrange
        $q = Quaternion::of(1, 2, 3, 4);

        // Act
        $result = $q->conjugate();

        // Assert
        self::assertSame([1.0, -2.0, -3.0, -4.0], [$result->w(), $result->x(), $result->y(), $result->z()]);
    }
}
