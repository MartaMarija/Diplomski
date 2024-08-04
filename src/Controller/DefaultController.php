<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends FrontendController {

    #[Route('/')]
    public function defaultAction(Request $request): Response
    {
        return $this->render('default/default.html.twig');
    }    
    
    /**
     * Forwards the request to admin login
     */
    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }
}
