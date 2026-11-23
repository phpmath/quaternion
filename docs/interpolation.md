---
title: Interpolation
intro: Blend two rotations smoothly with slerp.
group: Guides
order: 60
---

## Spherical linear interpolation

Slerp (spherical linear interpolation) finds a rotation between two others. `$from->slerp($to, $t)` returns `$from` when `$t` is 0 and `$to` when `$t` is 1. Values in between move at a constant speed along the shortest arc, which makes it the right tool for smooth animation.

```php
use PHPMath\Quaternion\Quaternion;

$from = Quaternion::identity();
$to = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

echo $from->slerp($to, 0.5); // 0.92387953251129 + 0i + 0j + 0.38268343236509k
```

The halfway rotation turns by 45 degrees, so it rotates the X axis halfway to the Y axis.

```php
use PHPMath\Quaternion\Quaternion;

$halfway = Quaternion::identity()->slerp(Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2), 0.5);

echo json_encode(array_map(fn (float $v) => round($v, 6), $halfway->rotate([1.0, 0.0, 0.0])));
// [0.707107,0.707107,0]
```

## Stepping through an animation

Call `slerp()` in a loop to produce frames. Every result has a norm of 1, so it stays a valid rotation.

```php
use PHPMath\Quaternion\Quaternion;

$from = Quaternion::identity();
$to = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

foreach ([0.0, 0.25, 0.5, 0.75, 1.0] as $t) {
    $frame = $from->slerp($to, $t);

    echo $t, ': ', json_encode(array_map(fn (float $v) => round($v, 5), $frame->rotate([1.0, 0.0, 0.0]))), "\n";
}
// 0: [1,0,0]
// 0.25: [0.92388,0.38268,0]
// 0.5: [0.70711,0.70711,0]
// 0.75: [0.38268,0.92388,0]
// 1: [0,1,0]
```

## The shortest arc

A rotation can be written as `q` or as `-q`, which is the same rotation. Slerp always picks the sign that gives the shorter path. As a result, `slerp($to, 1.0)` may return `$to` with all signs flipped. It is the same rotation, but `equals()` does not see it that way. Use `isSameRotation()` to compare the result with the end rotation.

```php
use PHPMath\Quaternion\Quaternion;

$to = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);
$flipped = $to->scale(-1.0);

$result = Quaternion::identity()->slerp($flipped, 1.0);

echo $result; // 0.70710678118655 + 0i + 0j + 0.70710678118655k

var_dump($result->equals($flipped));         // bool(false)
var_dump($result->isSameRotation($flipped)); // bool(true)
```

## Values outside 0 to 1

A `$t` below 0 or above 1 extrapolates. The rotation keeps going past the end points.

```php
use PHPMath\Quaternion\Quaternion;

$to = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

echo Quaternion::identity()->slerp($to, 2.0); // 0 + 0i + 0j + 1k
```

That is a turn of 180 degrees, twice the original 90 degrees.

## Inputs are normalized

Slerp normalizes both quaternions first, so it works with quaternions that are not unit length. It throws [`ZeroQuaternionException`](errors.md) when either quaternion is zero.
