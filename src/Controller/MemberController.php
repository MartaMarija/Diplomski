<?php

namespace App\Controller;

use App\FormType\MemberFormType;
use App\Model\Member;
use App\Repository\MemberRepository;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\HikingAssociation;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MemberController extends BaseController
{
    public function __construct(
        private Security $security,
        private MemberRepository $memberRepository,
        private PaginatorInterface $paginator
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

        $hikingAssociationId = $request->get('hikingAssociationId');
        $page = $request->query->get('page', 1);
        $limit = 2;

        $tripListing = $this->memberRepository->getMemberTripsListing($member);
        $trips = $this->paginator->paginate(
            $tripListing,
            $page,
            $limit
        );

        $htmlString = $this->renderView('user/trips.html.twig', [
            'hikingAssociation' => HikingAssociation::getById($hikingAssociationId),
            'trips' => $trips->getItems(),
            'totalPageCount' => ceil($trips->getTotalItemCount() / $limit),
            'currentPage' => $page,
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

        $page = $request->query->get('page', 1);
        $limit = 2;

        $membershipListing = $this->memberRepository->getMemberMembershipsListing($member);
        $memberships = $this->paginator->paginate(
            $membershipListing,
            $page,
            $limit
        );

        $htmlString = $this->renderView('user/memberships.html.twig', [
            'memberships' => $memberships->getItems(),
            'totalPageCount' => ceil($memberships->getTotalItemCount() / $limit),
            'currentPage' => $page,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
