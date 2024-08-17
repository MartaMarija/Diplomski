<?php

namespace App\Controller;

use App\FormType\MemberFormType;
use App\Model\Member;
use App\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends BaseController
{
    public function __construct(
        private MemberRepository $memberRepository,
    )
    {
    }

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

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
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

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
    }

    #[Route('/registration', name: 'registration')]
    public function registration(Request $request)
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView();
        }

        $form = $this->createForm(MemberFormType::class, new Member());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Member $member */
            $member = $form->getData();
            $this->memberRepository->createMember($member);

            return $this->respondWithSuccess(['redirectUrl' => $this->generateUrl('login')]);
        }

        $htmlString = $this->renderView('auth/registration.html.twig', [
            'form' => $form->createView(),
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
