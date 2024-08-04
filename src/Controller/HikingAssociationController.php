<?php

namespace App\Controller;

use App\DataMapper\HikingAssociationDataMapper;
use App\Repository\HikingAssociationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\City\Listing;
use Symfony\Component\HttpFoundation\Request;
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
    public function form(Request $request)
    {
        $htmlString = $this->renderView('hiking-association/search/hiking-association-view.html.twig', [
            'cities' => (new Listing())->getObjects(),
        ]);

        return $this->respondWithSuccess(['html_string' => $htmlString]);
    }

    #[Route('/hiking-association/search', name: 'hiking_association_search', methods: ['GET'])]
    public function search(Request $request)
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

        return $this->respondWithSuccess(['html_string' => $htmlString]);
    }
}
