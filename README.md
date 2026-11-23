# quaternion

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![CI][ico-ci]][link-ci]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP library to work with immutable quaternions for 3D rotations.

## Documentation

The full documentation is at https://phpmath.github.io/quaternion/. Every release is described on the [releases page](https://github.com/phpmath/quaternion/releases).

## Install

```bash
composer require phpmath/quaternion
```

## Requirements

- PHP 8.4 or newer

## Usage

Vectors and matrices are plain float arrays:
a vector is `[x, y, z]` and a matrix is a list of three rows of three floats.

Every quaternion is an immutable value object. Operations never change the instance they are called on,
they return a new instance.

``` php
use PHPMath\Quaternion\Quaternion;

// Rotate 90 degrees around the Z axis.
$rotation = Quaternion::fromAxisAngle([0.0, 0.0, 1.0], M_PI / 2);

$rotation->rotate([1.0, 0.0, 0.0]); // [0.0, 1.0, 0.0] (within floating point precision)
$rotation->toRotationMatrix();      // [[0, -1, 0], [1, 0, 0], [0, 0, 1]]

// Combine rotations with the Hamilton product: the right-hand side is applied first.
$combined = $rotation->multiply(Quaternion::fromAxisAngle([1.0, 0.0, 0.0], M_PI / 2));

// Interpolate along the shortest arc.
$halfway = Quaternion::identity()->slerp($rotation, 0.5);

echo $rotation; // 0.70710678118655 + 0i + 0j + 0.70710678118655k
```

### Conventions

* Right-handed coordinates and the Hamilton convention (`ij = k`). Positive angles rotate counter-clockwise
  when looking along the axis towards the origin.
* `fromEuler($yaw, $pitch, $roll)` uses intrinsic Z-Y'-X'' (aerospace) angles in radians: yaw about Z, pitch about Y,
  roll about X. It equals `qz(yaw) * qy(pitch) * qx(roll)`.
* `rotate()`, `toRotationMatrix()` and `slerp()` normalize their input, so they accept non-unit quaternions.
  They throw a `ZeroQuaternionException` for the zero quaternion.
* `equals()` compares components with an absolute tolerance (default `1e-9`). `q` and `-q` are the same
  rotation but are not equal, use `isSameRotation()` to compare rotations.

## Features

* Creation with `of()`, `identity()`, `fromAxisAngle()`, `fromAxisComponents()` and `fromEuler()`.
* Arithmetic: `add()`, `subtract()`, `multiply()`, `scale()`, `conjugate()` and `inverse()`.
* `norm()`, `normalize()` and `dot()`.
* `rotate()`, `toRotationMatrix()` and `slerp()`.
* `equals()`, `isSameRotation()` and `__toString()`.

## Development

```bash
composer test
composer test-coverage
composer analyse
composer check-style
composer fix-style
```

## Contributing

All contributions are welcome. Open an issue or a pull request on GitHub. Please follow the coding standard and add tests.

## Security

If you discover a security issue, please create an issue in the issue tracker.

## Credits

- [Walter Tamboer](https://github.com/waltertamboer)
- [All contributors](https://github.com/phpmath/quaternion/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/phpmath/quaternion.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-ci]: https://img.shields.io/github/actions/workflow/status/phpmath/quaternion/ci.yml?branch=master&style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/phpmath/quaternion.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/phpmath/quaternion
[link-ci]: https://github.com/phpmath/quaternion/actions/workflows/ci.yml
[link-downloads]: https://packagist.org/packages/phpmath/quaternion/stats
