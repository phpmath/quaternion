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
 * Thrown when a vector is not a list of exactly three floats.
 */
final class InvalidVectorException extends InvalidArgumentException implements QuaternionException
{
}
