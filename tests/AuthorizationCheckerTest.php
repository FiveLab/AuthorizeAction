<?php

/*
 * This file is part of the FiveLab AuthorizeAction package
 *
 * (c) FiveLab
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FiveLab\Component\AuthorizeAction\Tests;

use FiveLab\Component\AuthorizeAction\Action\AuthorizeActionInterface;
use FiveLab\Component\AuthorizeAction\AuthorizationChecker;
use FiveLab\Component\AuthorizeAction\UserProvider\UserProviderInterface;
use FiveLab\Component\AuthorizeAction\Verifier\AuthorizeActionVerifierInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
class AuthorizationCheckerTest extends TestCase
{
    /**
     * @var AuthorizeActionVerifierInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $verifier;

    /**
     * @var UserProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $userProvider;

    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->verifier = $this->createMock(AuthorizeActionVerifierInterface::class);
        $this->userProvider = $this->createMock(UserProviderInterface::class);

        $this->authorizationChecker = new AuthorizationChecker($this->verifier, $this->userProvider);
    }

    /**
     * @test
     */
    public function shouldSuccessVerify(): void
    {
        $user = $this->createMock(UserInterface::class);
        $action = $this->createMock(AuthorizeActionInterface::class);

        $this->userProvider->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $this->verifier->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(true);

        $this->verifier->expects(self::once())
            ->method('verify')
            ->with($action, $user);

        $this->authorizationChecker->verify($action);
    }

    /**
     * @test
     *
     * @expectedException \FiveLab\Component\AuthorizeAction\Exception\ActionNotSupportedException
     * @expectedExceptionMessage The verifier not support verify the action
     */
    public function shouldFailIfVerifierNotSupportsAction()
    {
        $user = $this->createMock(UserInterface::class);
        $action = $this->createMock(AuthorizeActionInterface::class);

        $this->userProvider->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $this->verifier->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(false);

        $this->verifier->expects(self::never())
            ->method('verify');

        $this->authorizationChecker->verify($action);
    }
}
