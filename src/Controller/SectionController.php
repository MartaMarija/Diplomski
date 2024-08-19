<?php

namespace App\Controller;

use App\Repository\SectionRepository;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Section;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SectionController extends BaseController
{
    public function __construct(
        private SectionRepository $sectionRepository,
    )
    {
    }

    #[Route('/hiking-association/{hikingAssociation}/sections', name: 'hiking_association_sections')]
    public function sections(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $sections = $this->sectionRepository->getSectionsByHikingAssociation($hikingAssociation);

        $htmlString = $this->renderView('hiking-association/sections.html.twig', [
            'hikingAssociation' => $hikingAssociation,
            'sections' => $sections,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/sections/{section}', name: 'sections_single')]
    public function section(Request $request, Section $section): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($section->getHikingAssociation());
        }

        $htmlString = $this->renderView('section/section.html.twig', [
            'section' => $section,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
