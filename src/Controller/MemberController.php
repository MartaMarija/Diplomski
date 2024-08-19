<?php

namespace App\Controller;

use App\FormType\MemberFormType;
use App\Model\Member;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MemberController extends BaseController
{
    public function __construct(
        private Security $security,
    )
    {
    }

    #[Route('/my-profile', name: 'my_profile')]
    public function myProfile(Request $request): Response
    {
        $member = $this->security->getUser();

        if (empty($member) || !$this->isAjaxRequest($request)) {
            return $this->getMainFrameView();
        }

        $message = null;

        $form = $this->createForm(MemberFormType::class, $member, [
            'myProfile' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Member $member */
            $member = $form->getData();
            $member->save();

            $message = 'Uspješno ste ažurirali podatke.';
        }

        $htmlString = $this->renderView('user/my-profile.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
            'message' => $message,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/my-profile/trips', name: 'my_profile_trips')]
    public function myProfileTrips(Request $request): Response
    {
        $member = $this->security->getUser();

        if (empty($member) ||! $this->isAjaxRequest($request)) {
            return $this->getMainFrameView();
        }



        $htmlString = $this->renderView('user/trips.html.twig', [

        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/my-profile/memberships', name: 'my_profile_memberships')]
    public function myProfileMemberships(Request $request): Response
    {
        $member = $this->security->getUser();

        if (empty($member) || !$this->isAjaxRequest($request)) {
            return $this->getMainFrameView();
        }

        $htmlString = $this->renderView('user/memberships.html.twig', [

        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
