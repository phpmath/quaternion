---
title: Combining rotations
intro: Chain rotations with multiply() and undo them with conjugate() and inverse().
group: Guides
order: 50
---

## The Hamilton product

`multiply()` combines two rotations into one. The result applies `$other` first, then `$this`. The order matters, because the product is not commutative.

```php
use PHPMath\Quaternion\Quaternion;

$rz = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);
$rx = Quaternion::fromAxisAngle([1.0, 0.0, 0.0], M_PI / 2);

$zAfterX = $rz->multiply($rx);
$xAfterZ = $rx->multiply($rz);

echo json_encode(array_map(fn (float $v) => round($v, 6), $zAfterX->rotate([0.0, 1.0, 0.0]))); // [-0,0,1]
echo json_encode(array_map(fn (float $v) => round($v, 6), $xAfterZ->rotate([0.0, 1.0, 0.0]))); // [-1,0,0]
```

Multiplying is the same as rotating twice in a row. `$rz->multiply($rx)` gives the same result as `$rz->rotate($rx->rotate($v))`.

> [!WARNING]
> `a->multiply(b)` applies `b` first. If you read your code left to right and expect `a` to happen first, the result is wrong.

## Conjugate

The conjugate keeps `w` and flips the sign of `x`, `y` and `z`. For a unit quaternion it describes the opposite rotation, which is the cheap way to undo it.

```php
use PHPMath\Quaternion\Quaternion;

echo Quaternion::of(1.0, 2.0, 3.0, 4.0)->conjugate(); // 1 - 2i - 3j - 4k
```

## Inverse

The inverse is the quaternion that multiplies with the original to give the identity. For a unit quaternion it equals the conjugate. For any other quaternion it also corrects the length, so use `inverse()` when the quaternion might not be a unit quaternion.

```php
use PHPMath\Quaternion\Quaternion;

$q = Quaternion::of(1.0, 2.0, 3.0, 4.0);

echo $q->inverse();
// 0.03333333333333 - 0.06666666666667i - 0.1j - 0.13333333333333k

echo $q->multiply($q->inverse());
// 1 + 0i + 0j + 0k
```

The zero quaternion has no inverse. `inverse()` throws [`ZeroQuaternionException`](errors.md) for it.
