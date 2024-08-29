<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends FrontendController {

    #[Route('/', name: 'default')]
    public function defaultAction(): Response
    {
        return $this->render('default/default.html.twig');
    }

    public function email(): Response
    {
        return $this->render('email/trip_info.html.twig');
    }
}
