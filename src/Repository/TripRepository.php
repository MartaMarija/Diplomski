<?php

namespace App\Repository;

use Carbon\Carbon;
use Pimcore\Db;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Trip;
use Pimcore\Model\DataObject\Trip\Listing;

class TripRepository
{
    /**
     * @return Trip[]
     */
    public function getTripListingByHikingAssociation(
        HikingAssociation $hikingAssociation,
        ?string $name,
        ?int $section,
        ?string $type,
        ?string $startDate,
        ?string $sort,
    ): Listing
    {
        $listing = new Listing();

        $listing->addConditionParam('HikingAssociation__id = :hikingAssociationId', [
            'hikingAssociationId' => $hikingAssociation->getId()
        ]);

        if (!empty($name)) {
            $listing->addConditionParam('Name LIKE :name', [
                'name' => "%$name%"
            ]);
        }

        if (!empty($section)) {
            $listing->addConditionParam('Section__id = :section', [
                'section' => $section
            ]);
        }

        if (!empty($startDate)) {
            $listing->addConditionParam('StartDate >= :startDate AND StartDate <= :endDate', [
                'startDate' => Carbon::parse($startDate)->startOfDay()->timestamp,
                'endDate' => Carbon::parse($startDate)->endOfDay()->timestamp
            ]);
        }

        if ($type === 'finished') {
            $listing->addConditionParam('StartDate < :today', [
                'today' => Carbon::now()->timestamp,
            ]);
        } else if ($type === 'incoming') {
            $listing->addConditionParam('StartDate >= :today', [
                'today' => Carbon::now()->timestamp,
            ]);
        }

        $listing->setOrderKey('StartDate');
        $listing->setOrder($sort);

        return $listing;
    }

    public function getNumberOfApplicantsForTrip(Trip $trip): int
    {
        $queryBuilder = Db::getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('COUNT(*)')
            ->from('object_Payment')
            ->where('PaymentObject__id = :tripId')
            ->setParameters([
                'tripId' => $trip->getId()
            ]);

        return $queryBuilder->executeQuery()->fetchOne();
    }

//    public function getNumberOfApplicantsForTrip(Trip $trip): int
//    {
//        $queryBuilder = Db::getConnection()->createQueryBuilder();
//
//        $queryBuilder
//            ->select('*')
//            ->from('object_Payment')
//            ->where('PaymentObject__id = :tripId')
//            ->setParameters([
//                'tripId' => $trip->getId()
//            ]);
//
//        $results = $queryBuilder->executeQuery()->fetchAllAssociative();
//
//        return count($results);
//    }
}
