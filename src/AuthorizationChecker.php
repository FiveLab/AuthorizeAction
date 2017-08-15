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
use FiveLab\Component\AuthorizeAction\UserProvider\UserProviderInterface;
use FiveLab\Component\AuthorizeAction\Verifier\AuthorizeActionVerifierInterface;

/**
 * Default authorization checker.
 *
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @var AuthorizeActionVerifierInterface
     */
    private $verifier;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * Constructor.
     *
     * @param AuthorizeActionVerifierInterface $verifier
     * @param UserProviderInterface            $userProvider
     */
    public function __construct(AuthorizeActionVerifierInterface $verifier, UserProviderInterface $userProvider)
    {
        $this->verifier = $verifier;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(AuthorizeActionInterface $action): void
    {
        $user = $this->userProvider->getUser();

        if (!$this->verifier->supports($action, $user)) {
            throw new ActionNotSupportedException(sprintf(
                'The verifier not support verify the action "%s" with user "%s".',
                get_class($action),
                get_class($user)
            ));
        }

        $this->verifier->verify($action, $user);
    }
}
