<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DbAuthenticator extends AbstractGuardAuthenticator
{
    protected $userProvider;
    protected $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }


    public function supports(Request $request)
    {
        if ($request->query->has('email') && $request->query->has('password')) {
            return true;
        }

        return false;
    }

    public function getCredentials(Request $request)
    {
        return [
            "email" => $request->query->get('email'),
            "password" => $request->query->get('password')
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // 1. UserRepository
        $user = $this->userRepository->findOneBy(['email' => $credentials['email']]);

        return $user;

        // 2. Chercher un user grace a son email
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Voir si les données concordent
        if ($this->encoder->isPasswordValid($user, $credentials['password'])) {
            return true;
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse("/");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
