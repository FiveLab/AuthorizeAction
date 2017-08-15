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

namespace FiveLab\Component\AuthorizeAction;

use FiveLab\Component\AuthorizeAction\Action\AuthorizeActionInterface;
use FiveLab\Component\AuthorizeAction\Exception\ActionNotSupportedException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * All authorization checkers should implement this interface.
 *
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
interface AuthorizationCheckerInterface
{
    /**
     * Verify the action before execution
     *
     * @param AuthorizeActionInterface $action
     *
     * @throws ActionNotSupportedException
     * @throws AccessDeniedException
     */
    public function verify(AuthorizeActionInterface $action): void;
}
