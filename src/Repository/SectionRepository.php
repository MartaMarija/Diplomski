<?php

namespace App\Repository;

use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Section;
use Pimcore\Model\DataObject\Section\Listing;

class SectionRepository
{
    /**
     * @return Section[]
     */
    public function getSectionsByHikingAssociation(HikingAssociation $hikingAssociation): array
    {
        $listing = new Listing();
        $listing->addConditionParam('HikingAssociation__id = :hikingAssociationId', [
            'hikingAssociationId' => $hikingAssociation->getId()]
        );

        return $listing->getObjects();
    }
}
