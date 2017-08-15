<?php

/*
 * This file is part of the FiveLab AuthorizeAction package
 *
 * (c) FiveLab
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FiveLab\Component\AuthorizeAction\Tests\Verifier;

use FiveLab\Component\AuthorizeAction\Action\AuthorizeActionInterface;
use FiveLab\Component\AuthorizeAction\Verifier\AuthorizeActionVerifierChain;
use FiveLab\Component\AuthorizeAction\Verifier\AuthorizeActionVerifierInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
class AuthorizeActionVerifierChainTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessSupports(): void
    {
        $user = $this->createMock(UserInterface::class);
        $action = $this->createMock(AuthorizeActionInterface::class);

        $verifier1 = $this->createMock(AuthorizeActionVerifierInterface::class);
        $verifier2 = $this->createMock(AuthorizeActionVerifierInterface::class);
        $verifier3 = $this->createMock(AuthorizeActionVerifierInterface::class);

        $verifier1->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(false);

        $verifier2->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(true);

        $verifier3->expects(self::never())
            ->method('supports');

        $chain = new AuthorizeActionVerifierChain();
        $chain->add($verifier1);
        $chain->add($verifier2);
        $chain->add($verifier3);

        $result = $chain->supports($action, $user);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldNotSupportsIfAllVerifiersNotSupported(): void
    {
        $user = $this->createMock(UserInterface::class);
        $action = $this->createMock(AuthorizeActionInterface::class);

        $verifier1 = $this->createMock(AuthorizeActionVerifierInterface::class);
        $verifier2 = $this->createMock(AuthorizeActionVerifierInterface::class);

        $verifier1->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(false);

        $verifier2->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(false);

        $chain = new AuthorizeActionVerifierChain();
        $chain->add($verifier1);
        $chain->add($verifier2);

        $result = $chain->supports($action, $user);

        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldSuccessVerify(): void
    {
        $user = $this->createMock(UserInterface::class);
        $action = $this->createMock(AuthorizeActionInterface::class);

        $verifier1 = $this->createMock(AuthorizeActionVerifierInterface::class);
        $verifier2 = $this->createMock(AuthorizeActionVerifierInterface::class);
        $verifier3 = $this->createMock(AuthorizeActionVerifierInterface::class);

        $verifier1->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(true);

        $verifier1->expects(self::once())
            ->method('verify')
            ->with($action, $user);

        $verifier2->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(false);

        $verifier2->expects(self::never())
            ->method('verify');

        $verifier3->expects(self::once())
            ->method('supports')
            ->with($action, $user)
            ->willReturn(true);

        $verifier3->expects(self::once())
            ->method('verify')
            ->with($action, $user);

        $chain = new AuthorizeActionVerifierChain();
        $chain->add($verifier1);
        $chain->add($verifier2);
        $chain->add($verifier3);

        $chain->verify($action, $user);
    }
}
