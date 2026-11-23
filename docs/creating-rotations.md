---
title: Creating rotations
intro: Build a rotation from raw components, an axis and an angle, or Euler angles.
group: Guides
order: 30
---

## Raw components and the identity

`of()` creates a quaternion from its four components, in the order `w, x, y, z`. It does not normalize them. `identity()` returns `(1, 0, 0, 0)`, the rotation that does nothing.

```php
use PHPMath\Quaternion\Quaternion;

echo Quaternion::of(1.0, 2.0, 3.0, 4.0); // 1 + 2i + 3j + 4k
echo Quaternion::identity();             // 1 + 0i + 0j + 0k
```

## Axis and angle

An axis-angle rotation turns by an angle (in radians) around an axis, which is a line through the origin. `fromAxisAngle()` returns a unit quaternion. The axis is normalized for you, so its length does not matter.

```php
use PHPMath\Quaternion\Quaternion;

echo Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);
// 0.70710678118655 + 0i + 0j + 0.70710678118655k

echo Quaternion::fromAxisAngle([0.0, 0.0, 2.0], M_PI);
// 0 + 0i + 0j + 1k
```

## Axis as separate numbers

`fromAxisComponents()` takes the three components of the axis as separate floats, followed by the angle. It gives the same result as `fromAxisAngle()` with an array.

```php
use PHPMath\Quaternion\Quaternion;

echo Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);
echo Quaternion::fromAxisComponents(0.0, 0.0, 1.0, M_PI / 2);
// both: 0.70710678118655 + 0i + 0j + 0.70710678118655k
```

An axis of zero length throws [`InvalidAxisException`](errors.md) in both constructors. An array that is not a list of exactly three numbers throws [`InvalidVectorException`](errors.md) in `fromAxisAngle()`.

```php
use PHPMath\Quaternion\Exception\InvalidAxisException;
use PHPMath\Quaternion\Quaternion;

try {
    Quaternion::fromAxisAngle([0.0, 0.0, 0.0], 1.0);
} catch (InvalidAxisException $exception) {
    echo $exception->getMessage(); // The rotation axis must not have zero length.
}
```

## Euler angles

Euler angles describe a rotation as three turns: yaw, pitch and roll. `fromEuler()` takes them in radians, in the order `$yaw, $pitch, $roll`. The turns are intrinsic, which means each one is made around an axis that already moved with the previous turns. The order is yaw about Z, then pitch about the new Y, then roll about the new X (the aerospace Z-Y'-X'' sequence).

```php
use PHPMath\Quaternion\Quaternion;

echo Quaternion::fromEuler(0.3, 0.2, 0.1);
// 0.98334744325636 + 0.03427079855048i + 0.1060205110618j + 0.14357217502739k
```

The result equals the product of three axis-angle rotations, `qz(yaw) * qy(pitch) * qx(roll)`. See [Combining rotations](combining-rotations.md).

```php
use PHPMath\Quaternion\Quaternion;

$product = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], 0.3)
    ->multiply(Quaternion::fromAxisAngle([0.0, 1.0, 0.0], 0.2))
    ->multiply(Quaternion::fromAxisAngle([1.0, 0.0, 0.0], 0.1));

var_dump(Quaternion::fromEuler(0.3, 0.2, 0.1)->equals($product)); // bool(true)
```

> [!NOTE]
> Euler angles suffer from gimbal lock. When the pitch is 90 degrees, the yaw and roll axes line up and one degree of freedom is lost. Quaternions do not have this problem, so prefer them to store and combine rotations and use Euler angles only for input.
