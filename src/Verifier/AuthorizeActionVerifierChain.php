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
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * The chain for collect authorize action verifiers.
 *
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
class AuthorizeActionVerifierChain implements AuthorizeActionVerifierInterface
{
    /**
     * @var array|AuthorizeActionVerifierInterface[]
     */
    private $verifiers = [];

    /**
     * Add verifier to chain
     *
     * @param AuthorizeActionVerifierInterface $verifier
     */
    public function add(AuthorizeActionVerifierInterface $verifier): void
    {
        $this->verifiers[] = $verifier;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AuthorizeActionInterface $action, UserInterface $user): bool
    {
        foreach ($this->verifiers as $verifier) {
            if ($verifier->supports($action, $user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(AuthorizeActionInterface $action, UserInterface $user): void
    {
        foreach ($this->verifiers as $verifier) {
            if ($verifier->supports($action, $user)) {
                $verifier->verify($action, $user);
            }
        }
    }
}
