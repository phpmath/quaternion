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

use function ini_get;
use function ini_set;

use const M_SQRT1_2;
use const PHP_FLOAT_MAX;

#[CoversClass(Quaternion::class)]
#[CoversMethod(Quaternion::class, '__toString')]
final class ToStringTest extends TestCase
{
    public function testFormatsWithSigns(): void
    {
        // Arrange
        $q = Quaternion::of(1, -2, 3.5, 4);

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('1 - 2i + 3.5j + 4k', $result);
    }

    public function testFormatsIdentity(): void
    {
        // Arrange
        $q = Quaternion::identity();

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('1 + 0i + 0j + 0k', $result);
    }

    public function testKeepsSmallValuesAsPlainDecimals(): void
    {
        // Arrange
        $q = Quaternion::of(0.0000001, -0.0000001, 1.0E-7, 0.5);

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('0.0000001 - 0.0000001i + 0.0000001j + 0.5k', $result);
    }

    public function testPrintsNegativeZeroAsZero(): void
    {
        // Arrange
        $q = Quaternion::of(-0.0, -0.0, -0.0, -0.0);

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('0 + 0i + 0j + 0k', $result);
    }

    public function testRoundsTinyNegativeValuesToZeroWithoutSign(): void
    {
        // Arrange
        $q = Quaternion::of(-1.0E-20, -1.0E-20, 1.0E-20, 1);

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('0 + 0i + 0j + 1k', $result);
    }

    public function testLimitsToFourteenDecimals(): void
    {
        // Arrange
        $q = Quaternion::of(M_SQRT1_2, 0, 0, M_SQRT1_2);

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('0.70710678118655 + 0i + 0j + 0.70710678118655k', $result);
    }

    public function testKeepsTrailingZerosOfWholeNumbers(): void
    {
        // Arrange
        $q = Quaternion::of(100, -20, 3000, 10);

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('100 - 20i + 3000j + 10k', $result);
    }

    public function testIgnoresPrecisionIniSetting(): void
    {
        // Arrange
        $q = Quaternion::of(M_SQRT1_2, 0.0000001, 0, 123456789.5);
        $original = ini_get('precision');
        ini_set('precision', '3');

        try {
            // Act
            $result = (string) $q;
        } finally {
            ini_set('precision', (string) $original);
        }

        // Assert
        self::assertSame('0.70710678118655 + 0.0000001i + 0j + 123456789.5k', $result);
    }

    public function testPrintsNotANumberAndInfinity(): void
    {
        // Arrange
        $overflow = Quaternion::of(PHP_FLOAT_MAX, 0, 0, 0)->scale(10.0);
        $q = $overflow->subtract($overflow)->add(Quaternion::of(0, 0, 0, 0));

        // Act
        $result = (string) $q;

        // Assert
        self::assertSame('NAN + 0i + 0j + 0k', $result);
        self::assertSame('INF + 0i + 0j + 0k', (string) $overflow);
        self::assertSame('-INF + 0i + 0j + 0k', (string) $overflow->scale(-1.0));
    }
}
