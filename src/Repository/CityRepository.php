<?php

namespace App\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Pimcore\Model\DataObject\City;
use Pimcore\Model\DataObject\City\Listing;

class CityRepository
{
    /**
     * @return City[]
     */
    public function getCitiesWithHikingAssociations(): array
    {
        // TODO caching. When HikingAssociation City is updated cache is deleted
        $listing = new Listing();

        $listing->onCreateQueryBuilder(function (QueryBuilder $queryBuilder) {
            $queryBuilder
                ->distinct()
                ->join(
                'object_City',
                'object_HikingAssociation',
                'object_HikingAssociation',
                'object_HikingAssociation.City__id = object_City.oo_id'
                )
                ->orderBy('object_City.name')
            ;
        });

        return $listing->getObjects();
    }
}
