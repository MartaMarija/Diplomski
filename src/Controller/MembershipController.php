<?php

namespace App\Controller;

use Pimcore\Model\DataObject\HikingAssociation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MembershipController extends BaseController
{

    #[Route('/membership/{hikingAssociation}}')]
    public function news(HikingAssociation $hikingAssociation): JsonResponse
    {
        $htmlString = $this->renderView('hiking-association/news.html.twig', [
            'hikingAssociation' => $hikingAssociation,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

}
