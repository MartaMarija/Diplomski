<?php

namespace App\Service;

use App\Repository\GuideRepository;
use Pimcore\Cache;
use Pimcore\Model\DataObject\Guide;
use Pimcore\Model\DataObject\HikingAssociation;

class GuideService
{
    public function __construct(
        private GuideRepository $guideRepository,
    )
    {
    }

    public static function getGuidesCacheKey(int $hikingAssociationId): string
    {
        return 'guides_' . $hikingAssociationId;
    }

    /**
     * @return Guide[]
     */
    public function getCachedGuidesByHikingAssociation(HikingAssociation $hikingAssociation): array
    {
        $cacheKey = self::getGuidesCacheKey($hikingAssociation->getId());
        $guides = Cache::load($cacheKey);

        if (!empty($guides)) {
            return $guides;
        }

        $data = $this->guideRepository->getGuidesByHikingAssociation($hikingAssociation);

        /**
         * 604800 is 7 days in seconds
         */
        Cache::save($data, $cacheKey, [], 604800);

        return $data;
    }
}
