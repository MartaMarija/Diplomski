<?php

namespace App\Controller;

use App\Constant\EmailConstant;
use App\Repository\TripRepository;
use App\Service\EmailService;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Trip;
use Pimcore\Model\Document;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TripController extends BaseController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private TripRepository $tripRepository,
        private Security $security,
        private EmailService $emailService,
    )
    {
    }

    #[Route('/hiking-association/{hikingAssociation}/trips-list', name: 'hiking_association_trips_list')]
    public function trips(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $page = intval($request->get('page', 1));
        $limit = 2;

        $tripListing = $this->tripRepository->getTripListingByHikingAssociation(
            $hikingAssociation,
            $request->get('name'),
            intval($request->get('section')),
            $request->get('type'),
            $request->get('startDate'),
            $request->get('sort', 'DESC'),
        );

        $trips = $this->paginator->paginate(
            $tripListing,
            $page,
            $limit
        );

        $htmlString = $this->renderView('trip/trip-list.html.twig', [
            'hikingAssociation' => $hikingAssociation,
            'trips' => $trips->getItems(),
            'totalPageCount' => ceil($trips->getTotalItemCount() / $limit),
            'currentPage' => $page,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/trips/{trip}', name: 'trips_single')]
    public function section(Request $request, Trip $trip): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($trip->getHikingAssociation());
        }

        $freeSpace = $trip->getAvailableCapacity() - $this->tripRepository->getNumberOfApplicantsForTrip($trip);

        $htmlString = $this->renderView('trip/trip-single.html.twig', [
            'trip' => $trip,
            'freeSpace' => $freeSpace
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }


    #[Route('/hiking-association/{hikingAssociation}/trips/{trip}/info-mail', name: 'trips_info_mail')]
    public function infoMail(Request $request, Trip $trip): Response
    {
        $user = $this->security->getUser();

        if (empty($user)) {
            return new JsonResponse(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $emailDocument = Document::getByPath(EmailConstant::TRIP_DETAILS_PATH);

        $this->emailService->sendEmail(
            $emailDocument,
            $user->getUserIdentifier(),
            'Informacije o izletu ' . $trip->getName(),
            [
                'tripName' => $trip->getName(),
                'tripStartDate' => $trip->getStartDate()->format('d.m.Y. H:i'),
                'tripEndDate' => $trip->getEndDate()->format('d.m.Y. H:i'),
                'tripDescription' => $trip->getDescription(),
                'tripTransport' => $trip->getTransportDescription()
            ]
        );

        return $this->respondWithSuccess(['message' => 'Email s detaljima izleta je uspješno poslan!']);
    }
}
