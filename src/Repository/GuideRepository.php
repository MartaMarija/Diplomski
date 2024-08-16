<?php

namespace App\Repository;

use Pimcore\Model\DataObject\Guide;
use Pimcore\Model\DataObject\Guide\Listing;
use Pimcore\Model\DataObject\HikingAssociation;

class GuideRepository
{
    /**
     * @return Guide[]
     */
    public function getGuidesByHikingAssociation(HikingAssociation $hikingAssociation): array
    {
        $listing = new Listing();
        $listing->addConditionParam('HikingAssociation__id = :hikingAssociationId', [
            'hikingAssociationId' => $hikingAssociation->getId()]
        );

        return $listing->getObjects();
    }
}
