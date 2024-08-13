<?php

namespace App\Controller;

use App\Repository\HikingAssociationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\City\Listing;
use Pimcore\Model\DataObject\HikingAssociation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HikingAssociationController extends BaseController
{

    public function __construct(
        private HikingAssociationRepository $hikingAssociationRepository,
        private PaginatorInterface $paginator,
    )
    {
    }

    #[Route('/hiking-association/search-form', name: 'hiking_association_search_form', methods: ['GET'])]
    public function form(Request $request): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/search/hiking-association-view.html.twig', [
            'cities' => (new Listing())->getObjects(),
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/search', name: 'hiking_association_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->get('searchTerm');
        $cityId = $request->get('city');
        $page = $request->get('page', 1);
        $limit = 2;

        $hikingAssociationsListing = $this->hikingAssociationRepository->getHikingAssociationsListing($searchTerm, $cityId);
        $hikingAssociations = $this->paginator->paginate(
            $hikingAssociationsListing,
            $page,
            $limit
        );

        $htmlString = $this->renderView('hiking-association/search/hiking-association-list.html.twig', [
            'hikingAssociations' => $hikingAssociations->getItems(),
            'totalPageCount' => ceil($hikingAssociations->getTotalItemCount() / $limit),
            'currentPage' => $page,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}', name: 'hiking_association')]
    public function nav(Request $request, string $hikingAssociation): Response
    {
        return $this->render('default/default.html.twig', [
            'hikingAssociationId' => $hikingAssociation,
        ]);
    }

    #[Route('/hiking-association/{hikingAssociation}/info', name: 'hiking_association_info')]
    public function info(HikingAssociation $hikingAssociation): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/info.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/sections', name: 'hiking_association_sections')]
    public function sections(HikingAssociation $hikingAssociation): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/sections.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/contact', name: 'hiking_association_contact')]
    public function contact(HikingAssociation $hikingAssociation): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/contact.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/guides', name: 'hiking_association_guides')]
    public function guides(HikingAssociation $hikingAssociation): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/guides.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/news', name: 'hiking_association_news')]
    public function news(HikingAssociation $hikingAssociation): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/news.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/trips', name: 'hiking_association_trips')]
    public function trips(HikingAssociation $hikingAssociation): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/trips.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
