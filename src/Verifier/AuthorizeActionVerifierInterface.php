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

namespace FiveLab\Component\AuthorizeAction\Verifier;

use FiveLab\Component\AuthorizeAction\Action\AuthorizeActionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * All authorize action verifiers should implement this interface.
 *
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
interface AuthorizeActionVerifierInterface
{
    /**
     * If the verifier supports action and user.
     *
     * @param AuthorizeActionInterface $action
     * @param UserInterface            $user
     *
     * @return bool
     */
    public function supports(AuthorizeActionInterface $action, UserInterface $user): bool;

    /**
     * Verify the action before execution
     *
     * @param AuthorizeActionInterface $action
     * @param UserInterface            $user
     *
     * @throws AccessDeniedException
     */
    public function verify(AuthorizeActionInterface $action, UserInterface $user): void;
}
