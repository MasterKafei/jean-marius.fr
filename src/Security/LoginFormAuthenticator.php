<?php

namespace App\Security;

use App\Form\Type\User\LoginType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    const LOGIN_ROUTE = 'app_authentication_login';

    private FormFactoryInterface $formFactory;

    /** @required */
    public function setFormBuilder(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function supports(Request $request): ?bool
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        return $request->get('_route') === self::LOGIN_ROUTE && $form->isSubmitted() && $form->isValid();
    }

    public function authenticate(Request $request): PassportInterface
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        return new Passport(
            new UserBadge($form->get('username')->getData()),
            new PasswordCredentials($form->get('password')->getData())
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], Response::HTTP_UNAUTHORIZED);
    }
}
