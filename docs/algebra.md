---
title: Quaternion algebra
intro: Add, subtract, scale, measure, compare and print quaternions.
group: Guides
order: 70
---

## Component-wise arithmetic

`add()` and `subtract()` work on each component separately. `scale()` multiplies all four components by a float. These are plain quaternion arithmetic, not rotations, and the results are generally not unit quaternions.

```php
use PHPMath\Quaternion\Quaternion;

$a = Quaternion::of(1.0, 2.0, 3.0, 4.0);
$b = Quaternion::of(0.0, 1.0, 0.0, 0.0);

echo $a->add($b);      // 1 + 3i + 3j + 4k
echo $a->subtract($b); // 1 + 1i + 3j + 4k
echo $a->scale(2.0);   // 2 + 4i + 6j + 8k
```

## Norm and normalize

The norm is the length of the quaternion, `sqrt(w² + x² + y² + z²)`. `normalize()` scales a quaternion to a norm of 1 so it becomes a unit quaternion. The zero quaternion has no direction, so `normalize()` throws [`ZeroQuaternionException`](errors.md) for it.

```php
use PHPMath\Quaternion\Quaternion;

$q = Quaternion::of(0.0, 3.0, 0.0, 4.0);

echo $q->norm();      // 5
echo $q->normalize(); // 0 + 0.6i + 0j + 0.8k
```

## Dot product

`dot()` multiplies matching components and adds them up. For two unit quaternions it tells you how close the rotations are: 1 means the same, 0 means far apart. A negative value means the sign is opposite, which is why slerp flips it.

```php
use PHPMath\Quaternion\Quaternion;

$a = Quaternion::of(1.0, 2.0, 3.0, 4.0);
$b = Quaternion::of(0.0, 1.0, 0.0, 0.0);

echo $a->dot($b); // 2
```

## Comparing quaternions

`equals()` compares the four components with an absolute tolerance. The default is `1e-9`, and you can pass your own as the second argument. It is the right way to compare floats.

```php
use PHPMath\Quaternion\Quaternion;

$a = Quaternion::of(1.0, 0.0, 0.0, 0.0);
$b = Quaternion::of(1.0000001, 0.0, 0.0, 0.0);

var_dump($a->equals($b));       // bool(false)
var_dump($a->equals($b, 1e-3)); // bool(true)
```

The tolerance must be a finite number that is not negative. A negative, `NAN` or infinite tolerance throws [`InvalidToleranceException`](errors.md).

> [!WARNING]
> `q` and `-q` describe the same rotation, but `equals()` treats them as different. Use `isSameRotation()` to compare rotations.

```php
use PHPMath\Quaternion\Quaternion;

$q = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

var_dump($q->equals($q->scale(-1.0))); // bool(false)
```

## Comparing rotations

`isSameRotation()` is true when two quaternions describe the same rotation. It normalizes both quaternions and then checks whether they are equal or one is the negation of the other, using the same absolute tolerance as `equals()` (default `1e-9`). Quaternions that differ only in length or in sign are the same rotation. It throws [`ZeroQuaternionException`](errors.md) when either quaternion is zero, and `InvalidToleranceException` for an invalid tolerance.

```php
use PHPMath\Quaternion\Quaternion;

$q = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

var_dump($q->isSameRotation($q->scale(-1.0))); // bool(true)
var_dump($q->isSameRotation($q->scale(5.0)));  // bool(true)
```

## Printing

A quaternion converts to a string like `1 - 2i + 3j + 4k`. Every component is written as a plain decimal with at most 14 decimals, without exponent and without trailing zeros. Negative zero is written as `0`, and the `precision` ini setting has no effect.

```php
use PHPMath\Quaternion\Quaternion;

echo Quaternion::of(1.0, -2.0, 3.5, -4.0);    // 1 - 2i + 3.5j - 4k
echo Quaternion::of(1.5, 0.25, -0.0, 1e-7);   // 1.5 + 0.25i + 0j + 0.0000001k
```
