<?php

namespace App\Controller;

use App\Security\Voter\MemberVoter;
use Pimcore\Model\DataObject\Member;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MemberController extends BaseController
{
    #[Route('/my-profile/{member}', name: 'my_profile')]
    #[IsGranted(MemberVoter::MEMBER, 'member')]
    public function myProfile(Member $member): Response
    {
        // TODO dohvatiti formu za usera, dohvatiti izlete
        $htmlString = $this->renderView('user/my-profile.html.twig', [

        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
