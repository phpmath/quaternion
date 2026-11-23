---
title: Core concepts
intro: The few ideas you need to use Quaternion well.
group: Getting started
order: 20
---

## What a quaternion is

A quaternion is written `w + xi + yj + zk`. It has four float components. In 3D graphics and robotics it is used to store a rotation. A quaternion with a norm (length) of 1 is called a unit quaternion, and only unit quaternions are pure rotations.

The library uses a right-handed coordinate system and the Hamilton convention (`ij = k`). A positive angle rotates counter-clockwise when you look along the axis towards the origin. Angles are always in radians.

## Quaternions are immutable

`Quaternion` is a `final readonly` class. Once created, a quaternion never changes. Methods such as `add()` or `conjugate()` return a new instance and leave the original untouched.

```php
use PHPMath\Quaternion\Quaternion;

$a = Quaternion::of(1.0, 2.0, 3.0, 4.0);
$b = $a->scale(2.0);

echo $a; // 1 + 2i + 3j + 4k
echo $b; // 2 + 4i + 6j + 8k
```

## Creating quaternions

The constructor is private. Use one of the named constructors: `of()` for raw components, `identity()` for "no rotation", and `fromAxisAngle()`, `fromAxisComponents()` and `fromEuler()` for rotations. They are explained in [Creating rotations](creating-rotations.md).

```php
use PHPMath\Quaternion\Quaternion;

Quaternion::of(1.0, 0.0, 0.0, 0.0);
Quaternion::identity();
Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);
Quaternion::fromAxisComponents(0.0, 0.0, 1.0, M_PI / 2);
Quaternion::fromEuler(0.3, 0.2, 0.1);
```

## Reading values

`w()`, `x()`, `y()` and `z()` return the components as floats. A quaternion can also be turned into text with `echo`, see [Quaternion algebra](algebra.md).

```php
use PHPMath\Quaternion\Quaternion;

$q = Quaternion::of(1.0, 2.0, 3.0, 4.0);

echo json_encode([$q->w(), $q->x(), $q->y(), $q->z()]); // [1,2,3,4]
```

## Vectors and matrices are arrays

The library has no vector or matrix class. A vector is a plain list of three numbers, `[x, y, z]`. A matrix is a list of three rows of three floats. Integers are accepted in a vector and are converted to floats.

```php
use PHPMath\Quaternion\Quaternion;

$q = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

echo json_encode($q->rotate([1, 0, 0])); // [2.220446049250313e-16,1,0]
```

## Floating point precision

All components are floats, so results carry tiny rounding errors. A rotation of exactly 90 degrees gives `2.2e-16` where you might expect `0`. Never compare quaternions or vectors with `==` or `===`. Use [`equals()`](algebra.md) for quaternions (or [`isSameRotation()`](algebra.md) to compare rotations) and round or compare with a tolerance for vectors.

```php
use PHPMath\Quaternion\Quaternion;

$q = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

var_dump($q->rotate([1.0, 0.0, 0.0]) === [0.0, 1.0, 0.0]); // bool(false)
```

## Errors

Every exception the library throws implements `QuaternionException`, so one `catch` block handles them all. See [Errors and exceptions](errors.md).

> [!NOTE]
> Rotation methods such as `rotate()`, `toRotationMatrix()` and `slerp()` normalize the quaternion first, so they also work with quaternions that are not unit length. They throw for the zero quaternion.
