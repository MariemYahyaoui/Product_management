<?php

namespace App\Security;

use App\Entity\User;
use App\Service\JwtService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private JwtService $jwtService,
        private RouterInterface $router,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        /** @var User $user */
        $user = $token->getUser();

        $jwt = $this->jwtService->createToken($user);

        $response = new RedirectResponse($this->router->generate('app_index'));

        $response->headers->setCookie(
            Cookie::create('AUTH_TOKEN')
                ->withValue($jwt)
                ->withExpires(time() + 3600 * 24)
                ->withPath('/')
                ->withHttpOnly(true)
                ->withSameSite('lax')
        );

        return $response;
    }
}
