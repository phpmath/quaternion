---
title: Introduction
intro: Immutable quaternions for 3D rotation in PHP, with axis-angle, vector rotation and slerp. Every quaternion is an immutable value object.
group: Getting started
order: 10
---

## Requirements

The library needs a current PHP version. It has no runtime dependencies.

- PHP 8.4 or newer

## Installation

Install the library with Composer.

```bash
composer require phpmath/quaternion
```

## A first example

A quaternion is four numbers, `w`, `x`, `y` and `z`, that can describe a rotation in 3D space. Here we build a rotation of 90 degrees around the Z axis and use it to turn a vector.

```php
use PHPMath\Quaternion\Quaternion;

$rotation = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

$rotated = $rotation->rotate([1.0, 0.0, 0.0]);

echo json_encode($rotated); // [2.220446049250313e-16,1,0]
echo $rotation;             // 0.70710678118655 + 0i + 0j + 0.70710678118655k
```

The vector `[1, 0, 0]` now points along the Y axis. The tiny `2.2e-16` is normal floating point noise, see [Core concepts](concepts.md). Calling `rotate()` did not change `$rotation`. It returned a new array. Every operation in the library works this way.

## Where to go next

Start with the core concepts, or jump straight to the topic you need.

<div class="cards"><a href="concepts.md"><strong>Core concepts</strong><span>Immutability, creating quaternions and reading values.</span></a><a href="creating-rotations.md"><strong>Creating rotations</strong><span>Axis-angle, Euler angles and the identity.</span></a><a href="rotating-vectors.md"><strong>Rotating vectors</strong><span>Rotate points and build rotation matrices.</span></a><a href="interpolation.md"><strong>Interpolation</strong><span>Blend two rotations smoothly with slerp.</span></a><a href="api.md"><strong>API reference</strong><span>Every method with its parameters and errors.</span></a></div>
