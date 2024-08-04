<?php

namespace App\Repository;

use Pimcore\Model\DataObject\HikingAssociation\Listing as HikingAssociationListing;

class HikingAssociationRepository
{
    public function getHikingAssociationsListing($searchTerm, $cityId): HikingAssociationListing
    {
        $listing = new HikingAssociationListing();

        if ($searchTerm !== null) {
            $listing->addConditionParam('Name LIKE :name', ['name' => "%$searchTerm%"]);
        }

        if ($cityId !== null) {
            $listing->addConditionParam('City__id LIKE :cityId', ['cityId' => "%$cityId%"]);
        }

        return $listing;
    }
}
