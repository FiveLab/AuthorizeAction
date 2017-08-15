<?php

declare(strict_types = 1);

/*
 * This file is part of the FiveLab AuthorizeAction package
 *
 * (c) FiveLab
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FiveLab\Component\AuthorizeAction\Exception;

/**
 * Throw this exception if any verifier not supported action for verify.
 *
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
class ActionNotSupportedException extends \Exception
{
}
