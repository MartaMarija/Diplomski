<?php

namespace App\Controller;

use Pimcore\Model\DataObject\Section;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SectionController extends BaseController
{
    #[Route('/sections/{section}', name: 'sections_single')]
    public function section(Request $request, Section $section): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($section->getHikigAssociation());
        }

        $htmlString = $this->renderView('section/section.html.twig', [
            'section' => $section,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
