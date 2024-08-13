<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends FrontendController {

    #[Route('/', name: 'default')]
    public function defaultAction(): Response
    {
        return $this->render('default/default.html.twig');
    }
}
