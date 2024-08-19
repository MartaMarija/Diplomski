<?php

namespace App\Controller;

use App\FormType\TripFilter;
use App\Repository\CityRepository;
use App\Repository\GuideRepository;
use App\Repository\HikingAssociationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\HikingAssociation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class HikingAssociationController extends BaseController
{

    public function __construct(
        private HikingAssociationRepository $hikingAssociationRepository,
        private PaginatorInterface $paginator,
        private GuideRepository $guideRepository,
        private CityRepository $cityRepository,
    )
    {
    }

    #[Route('/hiking-association/search-form', name: 'hiking_association_search_form', methods: ['GET'])]
    public function form(Request $request): Response
    {
        $htmlString = $this->renderView('hiking-association/search/hiking-association-view.html.twig', [
            'cities' => $this->cityRepository->getCitiesWithHikingAssociations(),
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/search', name: 'hiking_association_search', methods: ['GET'])]
    public function search(Request $request): Response
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
    public function nav(HikingAssociation $hikingAssociation): Response
    {
        return $this->getMainFrameView($hikingAssociation);
    }

    #[Route('/hiking-association/{hikingAssociation}/info', name: 'hiking_association_info')]
    public function info(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $htmlString = $this->renderView('hiking-association/info.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/trips', name: 'hiking_association_trips')]
    public function tripsForm(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }
        $form = $this->createForm(TripFilter::class, null, [
            'hikingAssociation' => $hikingAssociation
        ]);

        $htmlString = $this->renderView('trip/trip-view.html.twig', [
            'hikingAssociation' => $hikingAssociation,
            'form' => $form->createView()
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/news', name: 'hiking_association_news')]
    public function news(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $htmlString = $this->renderView('hiking-association/news.html.twig');

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/guides', name: 'hiking_association_guides')]
    public function guides(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $guides = $this->guideRepository->getGuidesByHikingAssociation($hikingAssociation);

        $htmlString = $this->renderView('hiking-association/guides.html.twig', [
            'hikingAssociation' => $hikingAssociation,
            'guides' => $guides
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/membership', name: 'hiking_association_membership')]
    public function membership(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $htmlString = $this->renderView('hiking-association/membership.html.twig');

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/contact', name: 'hiking_association_contact')]
    public function contact(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $htmlString = $this->renderView('hiking-association/contact.html.twig', [
            'hikingAssociation' => $hikingAssociation,
            'contactsInformation' => $hikingAssociation->getContactInformation()->getItems()
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
