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
 * Thrown when a rotation axis has zero length.
 */
final class InvalidAxisException extends InvalidArgumentException implements QuaternionException
{
}
