---
title: API reference
intro: Every public method of Quaternion, grouped by purpose.
group: Reference
order: 80
layout: reference
---

Every method returns a new `Quaternion` unless the return type says otherwise. All methods belong to `PHPMath\Quaternion\Quaternion`. A vector is `array{float, float, float}`. Angles are in radians.

## Construction

Named constructors that create a quaternion. The constructor itself is private.

### of()

```php
public static function of(float $w, float $x, float $y, float $z): self
```

Creates a quaternion from its four components. The values are not normalized.

- **Parameters:** `$w`, `$x`, `$y`, `$z` the components
- **Returns:** `Quaternion`

### identity()

```php
public static function identity(): self
```

Returns the identity quaternion `(1, 0, 0, 0)`, which means no rotation.

- **Returns:** `Quaternion`

### fromAxisAngle()

```php
public static function fromAxisAngle(array $axis, float $angle): self
```

Creates a unit quaternion from a rotation axis and an angle. The axis is normalized.

- **Parameters:** `$axis` the axis as a list of three numbers `[x, y, z]`; `$angle` the angle in radians
- **Returns:** `Quaternion`
- **Throws:** `InvalidAxisException` if the axis has zero length, `InvalidVectorException` if the axis is not a list of exactly three numbers

### fromAxisComponents()

```php
public static function fromAxisComponents(float $x, float $y, float $z, float $angle): self
```

Creates a unit quaternion from the three components of a rotation axis and an angle. The axis is normalized.

- **Parameters:** `$x`, `$y`, `$z` the components of the axis; `$angle` the angle in radians
- **Returns:** `Quaternion`
- **Throws:** `InvalidAxisException` if the axis has zero length

### fromEuler()

```php
public static function fromEuler(float $yaw, float $pitch, float $roll): self
```

Creates a unit quaternion from intrinsic Z-Y'-X'' angles: yaw about Z, then pitch about the new Y, then roll about the new X.

- **Parameters:** `$yaw` the rotation about Z; `$pitch` the rotation about Y; `$roll` the rotation about X
- **Returns:** `Quaternion`

## Reading values

Methods that return the components.

### w()

```php
public function w(): float
```

Returns the real component.

- **Returns:** `float`

### x()

```php
public function x(): float
```

Returns the i component.

- **Returns:** `float`

### y()

```php
public function y(): float
```

Returns the j component.

- **Returns:** `float`

### z()

```php
public function z(): float
```

Returns the k component.

- **Returns:** `float`

## Arithmetic

Component-wise arithmetic, the Hamilton product, conjugate and inverse.

### add()

```php
public function add(Quaternion $other): self
```

Adds another quaternion component by component.

- **Parameters:** `$other` the other operand
- **Returns:** `Quaternion`

### subtract()

```php
public function subtract(Quaternion $other): self
```

Subtracts another quaternion component by component.

- **Parameters:** `$other` the other operand
- **Returns:** `Quaternion`

### multiply()

```php
public function multiply(Quaternion $other): self
```

Returns the Hamilton product `$this * $other`. It is not commutative. As a rotation it applies `$other` first, then `$this`.

- **Parameters:** `$other` the right-hand operand
- **Returns:** `Quaternion`

### scale()

```php
public function scale(float $factor): self
```

Multiplies all four components by a factor.

- **Parameters:** `$factor` the scale factor
- **Returns:** `Quaternion`

### conjugate()

```php
public function conjugate(): self
```

Keeps `w` and flips the sign of `x`, `y` and `z`.

- **Returns:** `Quaternion`

### inverse()

```php
public function inverse(): self
```

Returns the quaternion that multiplies with this one to give the identity.

- **Returns:** `Quaternion`
- **Throws:** `ZeroQuaternionException` if the quaternion is zero

## Length

The norm, normalization and the dot product.

### norm()

```php
public function norm(): float
```

Returns the length of the quaternion.

- **Returns:** `float`

### normalize()

```php
public function normalize(): self
```

Scales the quaternion to a norm of 1.

- **Returns:** `Quaternion`
- **Throws:** `ZeroQuaternionException` if the quaternion is zero

### dot()

```php
public function dot(Quaternion $other): float
```

Returns the dot product with another quaternion.

- **Parameters:** `$other` the other operand
- **Returns:** `float`

## Rotation

Rotate vectors, build matrices and interpolate. All three normalize the quaternion first, and each has its own message for the zero quaternion.

### rotate()

```php
public function rotate(array $vector): array
```

Rotates a vector. The quaternion does not have to be a unit quaternion.

- **Parameters:** `$vector` a list of three numbers `[x, y, z]`
- **Returns:** `array{float, float, float}`
- **Throws:** `InvalidVectorException` if the vector is not a list of exactly three numbers, `ZeroQuaternionException` if the quaternion is zero

### toRotationMatrix()

```php
public function toRotationMatrix(): array
```

Returns the rotation as a 3x3 matrix of three rows, for use with column vectors.

- **Returns:** `array{array{float, float, float}, array{float, float, float}, array{float, float, float}}`
- **Throws:** `ZeroQuaternionException` if the quaternion is zero

### slerp()

```php
public function slerp(Quaternion $to, float $t): self
```

Spherical linear interpolation along the shortest arc. A `$t` of 0 returns this rotation and 1 returns `$to`, possibly with all signs flipped (the same rotation, so compare with `isSameRotation()` and not `equals()`). Values outside 0 to 1 extrapolate.

- **Parameters:** `$to` the end rotation; `$t` the interpolation factor
- **Returns:** `Quaternion`
- **Throws:** `ZeroQuaternionException` if either quaternion is zero

## Comparison and output

Compare quaternions and convert them to text.

### equals()

```php
public function equals(Quaternion $other, float $tolerance = 1e-9): bool
```

Compares the components with an absolute tolerance. `q` and `-q` are not equal, use `isSameRotation()` to compare rotations.

- **Parameters:** `$other` the other quaternion; `$tolerance` the largest allowed difference per component
- **Returns:** `bool`
- **Throws:** `InvalidToleranceException` if the tolerance is negative, `NAN` or infinite

### isSameRotation()

```php
public function isSameRotation(Quaternion $other, float $tolerance = 1e-9): bool
```

Returns true when both quaternions describe the same rotation: their normalized forms are equal, or one is the negation of the other, within the tolerance per component.

- **Parameters:** `$other` the other quaternion; `$tolerance` the largest allowed difference per component
- **Returns:** `bool`
- **Throws:** `ZeroQuaternionException` if either quaternion is zero, `InvalidToleranceException` if the tolerance is negative, `NAN` or infinite

### __toString()

```php
public function __toString(): string
```

Formats the quaternion like `1 - 2i + 3j + 4k`. Each component is a plain decimal with at most 14 decimals, without exponent or trailing zeros, and negative zero is written as `0`. The `precision` ini setting has no effect.

- **Returns:** `string`
