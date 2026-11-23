---
title: Errors and exceptions
intro: What can go wrong and how to catch it.
group: Reference
order: 90
---

## Library exceptions

Every exception the library throws implements the marker interface `PHPMath\Quaternion\Exception\QuaternionException`, so one `catch` block handles all of them. Each exception also extends an exception from PHP, which says what kind of problem it is:

- `InvalidArgumentException`: an argument has an invalid value or shape.
- `DomainException`: the arguments are fine in general, but the result is mathematically undefined for them.
- `OutOfRangeException`: an index is outside its range. The library does not use it yet.

| Exception | Extends | Thrown when |
|---|---|---|
| `PHPMath\Quaternion\Exception\InvalidAxisException` | `InvalidArgumentException` | The rotation axis has zero length. |
| `PHPMath\Quaternion\Exception\InvalidVectorException` | `InvalidArgumentException` | A vector is not a list of exactly three numbers. |
| `PHPMath\Quaternion\Exception\InvalidToleranceException` | `InvalidArgumentException` | A comparison tolerance is negative, `NAN` or infinite. |
| `PHPMath\Quaternion\Exception\ZeroQuaternionException` | `DomainException` | The operation is undefined for the zero quaternion. |

```php
use PHPMath\Quaternion\Exception\QuaternionException;
use PHPMath\Quaternion\Quaternion;

try {
    Quaternion::of(0, 0, 0, 0)->normalize();
} catch (QuaternionException $exception) {
    echo $exception::class . ': ' . $exception->getMessage();
    // PHPMath\Quaternion\Exception\ZeroQuaternionException: A zero quaternion cannot be normalized.
}
```

## When each exception is thrown

This table lists every case where a method rejects its input, with the exception and the message you get.

| Method | Condition | Exception | Message |
|---|---|---|---|
| `fromAxisAngle()`, `fromAxisComponents()` | The axis has zero length. | `InvalidAxisException` | `The rotation axis must not have zero length.` |
| `fromAxisAngle()` | The axis array is not a list of exactly three numbers. | `InvalidVectorException` | `A vector must be a list of exactly three floats.` |
| `rotate()` | The vector is not a list of exactly three numbers. | `InvalidVectorException` | `A vector must be a list of exactly three floats.` |
| `normalize()` | The quaternion is the zero quaternion. | `ZeroQuaternionException` | `A zero quaternion cannot be normalized.` |
| `inverse()` | The quaternion is the zero quaternion. | `ZeroQuaternionException` | `A zero quaternion has no inverse.` |
| `rotate()` | The quaternion is the zero quaternion. | `ZeroQuaternionException` | `A zero quaternion cannot be used to rotate a vector.` |
| `toRotationMatrix()` | The quaternion is the zero quaternion. | `ZeroQuaternionException` | `A zero quaternion cannot be converted to a rotation matrix.` |
| `slerp()` | Either quaternion is the zero quaternion. | `ZeroQuaternionException` | `A zero quaternion cannot be used for interpolation.` |
| `isSameRotation()` | Either quaternion is the zero quaternion. | `ZeroQuaternionException` | `A zero quaternion cannot be compared as a rotation.` |
| `equals()`, `isSameRotation()` | The tolerance is negative, `NAN` or infinite. | `InvalidToleranceException` | `The tolerance must be a finite number that is not negative.` |

## Handling untrusted input

Check user input before it reaches the library, or catch the exception.

```php
use PHPMath\Quaternion\Exception\InvalidAxisException;
use PHPMath\Quaternion\Quaternion;

try {
    $rotation = Quaternion::fromAxisAngle([0.0, 0.0, 0.0], 1.0);
} catch (InvalidAxisException $exception) {
    echo $exception->getMessage(); // The rotation axis must not have zero length.
}
```
