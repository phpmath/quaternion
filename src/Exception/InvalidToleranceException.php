<?php
/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Exception;

use InvalidArgumentException;

/**
 * Thrown when a comparison tolerance is negative, NAN or infinite.
 */
final class InvalidToleranceException extends InvalidArgumentException implements QuaternionException
{
}
