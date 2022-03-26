<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class AppFormAuthenticator extends AbstractLoginFormAuthenticator
{

    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private RouterInterface $router;

    public function __construct(UserRepository $userRepository,
                                UserPasswordHasherInterface $userPasswordHasher,
                                RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->router = $router;
    }

    public function supports(Request $request): bool
    {

        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');

    }


public function authenticate(Request $request): Passport
    {
        // aus symfony 6.0 doc
        $username = $request->request->get('email');
        $password = $request->request->get('password');

        return new Passport(
            new UserBadge($username, function ($userIdentifier) {
                return $this->userRepository->findOneBy(['email' => $userIdentifier]);
            }),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // TODO: Implement onAuthenticationSuccess() method.
        return new RedirectResponse($this->router->generate('listSubscriptions'));
        //dd('onAuthenticationSuccess');
    }

//    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
//    {
//        // TODO: Implement onAuthenticationFailure() method.
//
//    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
        //dd('getLoginUrl');
    }
}
