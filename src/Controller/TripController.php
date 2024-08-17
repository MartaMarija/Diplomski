<?php

namespace App\Controller;

use Pimcore\Model\DataObject\Trip;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TripController extends BaseController
{
    #[Route('/trips/{trip}', name: 'trips_single')]
    public function section(Request $request, Trip $trip): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($trip->getHikingAssociation());
        }

        $htmlString = $this->renderView('trip/trip-view.html.twig', [
            'trip' => $trip,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
