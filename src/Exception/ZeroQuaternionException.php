<?php
/**
 * phpmath / quaternion (https://github.com/phpmath/quaternion)
 *
 * @link https://github.com/phpmath/quaternion for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Quaternion\Exception;

use DomainException;

/**
 * Thrown when an operation is undefined for the zero quaternion.
 */
final class ZeroQuaternionException extends DomainException implements QuaternionException
{
}
