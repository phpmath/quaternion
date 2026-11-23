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
#[CoversMethod(Quaternion::class, 'norm')]
final class NormTest extends TestCase
{
    public function testReturnsEuclideanLength(): void
    {
        // Arrange
        $q = Quaternion::of(1, 2, 2, 4);

        // Act
        $result = $q->norm();

        // Assert
        self::assertSame(5.0, $result);
    }
}
