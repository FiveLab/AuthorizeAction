<?php

/*
 * This file is part of the FiveLab AuthorizeAction package
 *
 * (c) FiveLab
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FiveLab\Component\AuthorizeAction\Tests\UserProvider;

use FiveLab\Component\AuthorizeAction\UserProvider\SymfonyTokenStorageUserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Vitaliy Zhuk <v.zhuk@fivelab.org>
 */
class SymfonyTokenStorageUserProviderTest extends TestCase
{
    /**
     * @var TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $tokenStorage;

    /**
     * @var SymfonyTokenStorageUserProvider
     */
    private $userProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->userProvider = new SymfonyTokenStorageUserProvider($this->tokenStorage);
    }

    /**
     * @test
     */
    public function shouldSuccessGetUser()
    {
        $user = $this->createMock(UserInterface::class);
        $token = $this->createMock(TokenInterface::class);

        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $result = $this->userProvider->getUser();

        self::assertEquals($user, $result);
    }

    /**
     * @test
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage The token was not found.
     */
    public function shouldFailIfTokenNotFound()
    {
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn(null);

        $this->userProvider->getUser();
    }

    /**
     * @test
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage The user is invalid.
     */
    public function shouldFailIfUserNotFound()
    {
        $token = $this->createMock(TokenInterface::class);

        $token->expects(self::once())
            ->method('getUser')
            ->willReturn(null);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->userProvider->getUser();
    }

    /**
     * @test
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage The user is invalid.
     */
    public function shouldFailIfUserIsRegularString()
    {
        $token = $this->createMock(TokenInterface::class);

        $token->expects(self::once())
            ->method('getUser')
            ->willReturn('anon.');

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->userProvider->getUser();
    }
}
