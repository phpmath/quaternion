---
title: Rotating vectors
intro: Turn points and directions with rotate(), or get the same rotation as a matrix.
group: Guides
order: 40
---

## Rotating a vector

`rotate()` takes a vector as `[x, y, z]` and returns the rotated vector. It normalizes the quaternion first, so the quaternion does not have to be a unit quaternion. A positive angle turns counter-clockwise when you look along the axis towards the origin.

```php
use PHPMath\Quaternion\Quaternion;

$rotation = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

echo json_encode($rotation->rotate([1.0, 0.0, 0.0])); // [2.220446049250313e-16,1,0]
```

A quaternion such as `(2, 0, 0, 0)` is not a unit quaternion, but it still means "no rotation" after normalizing.

```php
use PHPMath\Quaternion\Quaternion;

echo json_encode(Quaternion::of(2.0, 0.0, 0.0, 0.0)->rotate([1.0, 2.0, 3.0])); // [1,2,3]
```

## Undoing a rotation

Rotating by the conjugate turns the vector back. For a unit quaternion the conjugate is the inverse rotation.

```php
use PHPMath\Quaternion\Quaternion;

$rotation = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);
$turned = $rotation->rotate([1.0, 2.0, 3.0]);

echo json_encode(array_map(fn (float $v) => round($v, 6), $rotation->conjugate()->rotate($turned)));
// [1,2,3]
```

## Rotation matrices

`toRotationMatrix()` returns the same rotation as a 3x3 matrix, as a list of three rows. It is made for column vectors, so `v' = M * v`. The quaternion is normalized first.

```php
use PHPMath\Quaternion\Quaternion;

$rotation = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

echo json_encode($rotation->toRotationMatrix());
// [[2.220446049250313e-16,-1,0],[1,2.220446049250313e-16,0],[0,0,1]]
```

Use the matrix when you need to hand a rotation to code that does not know quaternions, such as a graphics API. Use `rotate()` for a few vectors and the matrix when you rotate many.

## Invalid input

A vector must be a list with exactly three numbers. Strings, associative arrays and arrays of another length throw [`InvalidVectorException`](errors.md). The zero quaternion cannot be normalized, so `rotate()` and `toRotationMatrix()` throw [`ZeroQuaternionException`](errors.md) for it.

```php
use PHPMath\Quaternion\Exception\InvalidVectorException;
use PHPMath\Quaternion\Quaternion;

try {
    Quaternion::identity()->rotate([1.0, 0.0]);
} catch (InvalidVectorException $exception) {
    echo $exception->getMessage(); // A vector must be a list of exactly three floats.
}
```
