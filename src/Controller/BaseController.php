<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends FrontendController
{
    public function respondWithSuccess($data = null, $message = 'Success', $status_code = Response::HTTP_OK, array $headers = [])
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $member = $this->getUser();

        $memberData = null;
        if ($member) {
            $memberData = [];
            $memberData["status"] = 'auth';
            $memberData["id"] = $member->getId();
        }

        $response = [
            "meta" => [
                'status' => 'success',
                "message"   => $message,
                "http_code" => $status_code,
                "method"    => $request->getMethod(),
                "uri"       => ltrim($request->getRequestUri(), '/'),
                "session"   => $memberData
            ],
            "data" => $data
        ];

        return new JsonResponse($response, $status_code, $headers);
    }

    public function getMainFrameView($hikingAssociationId = null): Response
    {
        return $this->render('default/default.html.twig');
    }

    public function isAjaxRequest(Request $request): bool
    {
        return boolval($request->get('ajax')) === true;
    }
}
