<?php

/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion;

use PHPMath\Quaternion\Exception\InvalidAxisException;
use PHPMath\Quaternion\Exception\InvalidToleranceException;
use PHPMath\Quaternion\Exception\InvalidVectorException;
use PHPMath\Quaternion\Exception\ZeroQuaternionException;

use function abs;
use function acos;
use function array_is_list;
use function cos;
use function count;
use function is_float;
use function is_infinite;
use function is_int;
use function is_nan;
use function number_format;
use function rtrim;
use function sin;
use function sqrt;
use function str_starts_with;
use function substr;

/**
 * An immutable quaternion with float components, used to represent 3D rotations.
 *
 * A quaternion is written as `w + xi + yj + zk`. Rotations use the right-handed coordinate system and the
 * Hamilton convention (`ij = k`). A positive angle rotates counter-clockwise when looking along the axis
 * towards the origin. Vectors and matrices are plain float arrays: a vector is `[x, y, z]` and a matrix is
 * a list of three rows of three floats.
 */
final readonly class Quaternion
{
    private function __construct(
        private float $w,
        private float $x,
        private float $y,
        private float $z,
    ) {
    }

    public static function of(float $w, float $x, float $y, float $z): self
    {
        return new self($w, $x, $y, $z);
    }

    /**
     * The identity quaternion (1, 0, 0, 0), which represents "no rotation".
     */
    public static function identity(): self
    {
        return new self(1.0, 0.0, 0.0, 0.0);
    }

    /**
     * Creates a unit quaternion from a rotation axis and an angle in radians. The axis is normalized.
     *
     * @param array{float, float, float} $axis  The axis as a list of three floats.
     * @param float                      $angle The angle in radians.
     *
     * @throws InvalidAxisException   When the axis has zero length.
     * @throws InvalidVectorException When the axis is not a list of exactly three floats.
     */
    public static function fromAxisAngle(array $axis, float $angle): self
    {
        $vector = self::assertVector($axis);

        return self::fromAxisComponents($vector[0], $vector[1], $vector[2], $angle);
    }

    /**
     * Creates a unit quaternion from the three components of a rotation axis and an angle in radians. The axis is
     * normalized.
     *
     * @throws InvalidAxisException When the axis has zero length.
     */
    public static function fromAxisComponents(float $x, float $y, float $z, float $angle): self
    {
        $length = sqrt($x ** 2 + $y ** 2 + $z ** 2);

        if ($length === 0.0) {
            throw new InvalidAxisException('The rotation axis must not have zero length.');
        }

        $factor = sin($angle / 2) / $length;

        return new self(cos($angle / 2), $x * $factor, $y * $factor, $z * $factor);
    }

    /**
     * Creates a unit quaternion from Tait-Bryan angles in radians.
     *
     * Convention: right-handed, intrinsic rotations applied in the order yaw about Z, then pitch about the new Y,
     * then roll about the new X (the aerospace Z-Y'-X'' sequence). This is equivalent to the extrinsic product
     * `q = qz(yaw) * qy(pitch) * qx(roll)`, so a vector is rotated by roll about X first, then pitch about Y, then
     * yaw about Z, all about the fixed world axes.
     */
    public static function fromEuler(float $yaw, float $pitch, float $roll): self
    {
        $cy = cos($yaw / 2);
        $sy = sin($yaw / 2);
        $cp = cos($pitch / 2);
        $sp = sin($pitch / 2);
        $cr = cos($roll / 2);
        $sr = sin($roll / 2);

        return new self(
            $cr * $cp * $cy + $sr * $sp * $sy,
            $sr * $cp * $cy - $cr * $sp * $sy,
            $cr * $sp * $cy + $sr * $cp * $sy,
            $cr * $cp * $sy - $sr * $sp * $cy,
        );
    }

    public function w(): float
    {
        return $this->w;
    }

    public function x(): float
    {
        return $this->x;
    }

    public function y(): float
    {
        return $this->y;
    }

    public function z(): float
    {
        return $this->z;
    }

    public function add(self $other): self
    {
        return new self($this->w + $other->w, $this->x + $other->x, $this->y + $other->y, $this->z + $other->z);
    }

    public function subtract(self $other): self
    {
        return new self($this->w - $other->w, $this->x - $other->x, $this->y - $other->y, $this->z - $other->z);
    }

    /**
     * The Hamilton product `$this * $other`. It is not commutative: the result applies `$other` first, then `$this`.
     */
    public function multiply(self $other): self
    {
        return new self(
            $this->w * $other->w - $this->x * $other->x - $this->y * $other->y - $this->z * $other->z,
            $this->w * $other->x + $this->x * $other->w + $this->y * $other->z - $this->z * $other->y,
            $this->w * $other->y - $this->x * $other->z + $this->y * $other->w + $this->z * $other->x,
            $this->w * $other->z + $this->x * $other->y - $this->y * $other->x + $this->z * $other->w,
        );
    }

    public function scale(float $factor): self
    {
        return new self($this->w * $factor, $this->x * $factor, $this->y * $factor, $this->z * $factor);
    }

    public function conjugate(): self
    {
        return new self($this->w, -$this->x, -$this->y, -$this->z);
    }

    public function norm(): float
    {
        return sqrt($this->dot($this));
    }

    /** @throws ZeroQuaternionException When the norm is zero. */
    public function normalize(): self
    {
        return $this->unit('A zero quaternion cannot be normalized.');
    }

    /** @throws ZeroQuaternionException When the norm is zero. */
    public function inverse(): self
    {
        $squared = $this->dot($this);

        if ($squared === 0.0) {
            throw new ZeroQuaternionException('A zero quaternion has no inverse.');
        }

        return $this->conjugate()->scale(1 / $squared);
    }

    public function dot(self $other): float
    {
        return $this->w * $other->w + $this->x * $other->x + $this->y * $other->y + $this->z * $other->z;
    }

    /**
     * Rotates a vector by this quaternion. The quaternion is normalized first, so it does not have to be a unit
     * quaternion.
     *
     * @param array{float, float, float} $vector
     *
     * @return array{float, float, float}
     *
     * @throws InvalidVectorException   When the vector is not a list of exactly three floats.
     * @throws ZeroQuaternionException When the quaternion is zero.
     */
    public function rotate(array $vector): array
    {
        $vector = self::assertVector($vector);
        $q = $this->unit('A zero quaternion cannot be used to rotate a vector.');

        // v' = v + 2w(u x v) + 2(u x (u x v)) where u is the vector part of q.
        $cx = $q->y * $vector[2] - $q->z * $vector[1];
        $cy = $q->z * $vector[0] - $q->x * $vector[2];
        $cz = $q->x * $vector[1] - $q->y * $vector[0];

        return [
            $vector[0] + 2 * ($q->w * $cx + $q->y * $cz - $q->z * $cy),
            $vector[1] + 2 * ($q->w * $cy + $q->z * $cx - $q->x * $cz),
            $vector[2] + 2 * ($q->w * $cz + $q->x * $cy - $q->y * $cx),
        ];
    }

    /**
     * The 3x3 rotation matrix as three rows, for use with column vectors (`v' = M * v`). The quaternion is
     * normalized first.
     *
     * @return array{array{float, float, float}, array{float, float, float}, array{float, float, float}}
     *
     * @throws ZeroQuaternionException When the quaternion is zero.
     */
    public function toRotationMatrix(): array
    {
        $q = $this->unit('A zero quaternion cannot be converted to a rotation matrix.');

        return [
            [
                1 - 2 * ($q->y ** 2 + $q->z ** 2),
                2 * ($q->x * $q->y - $q->z * $q->w),
                2 * ($q->x * $q->z + $q->y * $q->w),
            ],
            [
                2 * ($q->x * $q->y + $q->z * $q->w),
                1 - 2 * ($q->x ** 2 + $q->z ** 2),
                2 * ($q->y * $q->z - $q->x * $q->w),
            ],
            [
                2 * ($q->x * $q->z - $q->y * $q->w),
                2 * ($q->y * $q->z + $q->x * $q->w),
                1 - 2 * ($q->x ** 2 + $q->y ** 2),
            ],
        ];
    }

    /**
     * Spherical linear interpolation along the shortest arc. Both quaternions are normalized first. A `$t` of 0
     * returns `$this` and 1 returns `$to` (possibly with all signs flipped, which is the same rotation). Values
     * outside 0..1 extrapolate.
     *
     * @throws ZeroQuaternionException When either quaternion is zero.
     */
    public function slerp(self $to, float $t): self
    {
        $from = $this->unit('A zero quaternion cannot be used for interpolation.');
        $to = $to->unit('A zero quaternion cannot be used for interpolation.');
        $cos = $from->dot($to);

        if ($cos < 0.0) {
            $to = $to->scale(-1);
            $cos = -$cos;
        }

        if ($cos > 0.9995) {
            return $from->scale(1 - $t)->add($to->scale($t))->normalize();
        }

        $theta = acos($cos);
        $sin = sin($theta);

        return $from->scale(sin((1 - $t) * $theta) / $sin)->add($to->scale(sin($t * $theta) / $sin));
    }

    /**
     * Compares the components with an absolute tolerance. Note that `q` and `-q` describe the same rotation but
     * are not equal, use `isSameRotation()` to compare rotations.
     *
     * @throws InvalidToleranceException When the tolerance is negative, NAN or infinite.
     */
    public function equals(self $other, float $tolerance = 1e-9): bool
    {
        self::assertTolerance($tolerance);

        return $this->componentsWithin($other, $tolerance);
    }

    /**
     * Whether both quaternions describe the same rotation: their normalized forms are equal, or one is the negation
     * of the other, within an absolute tolerance per component.
     *
     * @throws InvalidToleranceException When the tolerance is negative, NAN or infinite.
     * @throws ZeroQuaternionException   When either quaternion is zero.
     */
    public function isSameRotation(self $other, float $tolerance = 1e-9): bool
    {
        self::assertTolerance($tolerance);

        $message = 'A zero quaternion cannot be compared as a rotation.';
        $a = $this->unit($message);
        $b = $other->unit($message);

        return $a->componentsWithin($b, $tolerance) || $a->componentsWithin($b->scale(-1.0), $tolerance);
    }

    /**
     * Formats the quaternion like `1 - 2i + 3j + 4k`. Every component is a plain decimal with at most 14 decimals,
     * without exponent and trailing zeros. The `precision` ini setting is not used.
     */
    public function __toString(): string
    {
        return self::decimal($this->w)
            . self::term($this->x, 'i')
            . self::term($this->y, 'j')
            . self::term($this->z, 'k');
    }

    private function componentsWithin(self $other, float $tolerance): bool
    {
        return abs($this->w - $other->w) <= $tolerance
            && abs($this->x - $other->x) <= $tolerance
            && abs($this->y - $other->y) <= $tolerance
            && abs($this->z - $other->z) <= $tolerance;
    }

    /** @throws ZeroQuaternionException With the given message when the norm is zero. */
    private function unit(string $message): self
    {
        $norm = $this->norm();

        if ($norm === 0.0) {
            throw new ZeroQuaternionException($message);
        }

        return $this->scale(1 / $norm);
    }

    private static function assertTolerance(float $tolerance): void
    {
        if ($tolerance < 0.0 || is_nan($tolerance) || is_infinite($tolerance)) {
            throw new InvalidToleranceException('The tolerance must be a finite number that is not negative.');
        }
    }

    private static function decimal(float $value): string
    {
        if (is_nan($value)) {
            return 'NAN';
        }

        if (is_infinite($value)) {
            return $value > 0.0 ? 'INF' : '-INF';
        }

        $text = rtrim(rtrim(number_format(abs($value), 14, '.', ''), '0'), '.');

        return $value < 0 && $text !== '0' ? '-' . $text : $text;
    }

    private static function term(float $value, string $unit): string
    {
        $text = self::decimal($value);

        return str_starts_with($text, '-') ? ' - ' . substr($text, 1) . $unit : ' + ' . $text . $unit;
    }

    /**
     * @param array<mixed> $vector
     *
     * @return array{float, float, float}
     */
    private static function assertVector(array $vector): array
    {
        if (count($vector) !== 3 || !array_is_list($vector)) {
            throw new InvalidVectorException('A vector must be a list of exactly three floats.');
        }

        foreach ($vector as $component) {
            if (!is_int($component) && !is_float($component)) {
                throw new InvalidVectorException('A vector must be a list of exactly three floats.');
            }
        }

        return [(float) $vector[0], (float) $vector[1], (float) $vector[2]];
    }
}
