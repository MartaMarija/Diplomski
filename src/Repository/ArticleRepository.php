<?php

namespace App\Repository;

use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\NewsArticle\Listing;

class ArticleRepository
{
    public function getNewsArticleListingByHikingAssociation(HikingAssociation $hikingAssociation)
    {
        $listing = new Listing();
        $listing->addConditionParam('HikingAssociation__id = :hikingAssociation', [
            'hikingAssociation' => $hikingAssociation->getId()
        ]);

        return $listing;
    }
}
