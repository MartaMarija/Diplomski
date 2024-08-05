<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends BaseController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView();
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();

        $htmlString = $this->renderView('auth/login.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error,
        ]);

        return $this->respondWithSuccess(['html_string' => $htmlString]);
    }

    #[Route('/login-check', name: 'login_check')]
    public function loginCheck(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView();
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();

        $htmlString = $this->renderView('auth/login.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error,
        ]);

        return $this->respondWithSuccess(['html_string' => $htmlString]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
    }
}
