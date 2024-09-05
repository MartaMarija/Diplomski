<?php

namespace App\Service;

use App\Repository\TripRepository;
use Pimcore\Model\DataObject\Trip;

class TripService
{
    public function __construct(
        private TripRepository $tripRepository,
    )
    {
    }

    public function tripHasCapacity(Trip $trip): bool
    {
        $numberOfApplicants = $this->tripRepository->getNumberOfApplicantsForTrip($trip);

        if ($numberOfApplicants >= $trip->getAvailableCapacity()) {
            return false;
        }

        return true;
    }
}
