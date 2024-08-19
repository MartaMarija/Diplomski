<?php

namespace App\Repository;

use App\Constant\PaymentConstant;
use Doctrine\DBAL\Query\QueryBuilder;
use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Payment\Listing;
use Pimcore\Model\DataObject\Service;

class MemberRepository
{
    public const PATH = '/Korisnici';

    public function createMember(Member $member)
    {
        $path = Service::createFolderByPath(self::PATH);

        $member->setParent($path);
        $member->setKey($member->getEmail());
        $member->setPublished(true);

        return $member->save();
    }

    public function getMemberMembershipsListing(Member $member): Listing
    {
        $listing = new Listing();
        $listing
            ->addConditionParam('Member__id = :memberId', ['memberId' => $member->getId()])
            ->addConditionParam('Description = :description', ['description' => PaymentConstant::MEMBERSHIP])
        ;

        return $listing;
    }

    public function getMemberTripsListing(Member $member): \Pimcore\Model\DataObject\Trip\Listing
    {
        $listing = new \Pimcore\Model\DataObject\Trip\Listing();

        $listing->onCreateQueryBuilder(function (QueryBuilder $queryBuilder) use ($member) {
            $queryBuilder
                ->join(
                    'object_Trip',
                    'object_Payment',
                    'object_Payment',
                    'object_Payment.PaymentObject__id = object_Trip.oo_id'
                )
                ->where('object_Payment.Member__id = :memberId')
                ->setParameter('memberId', $member->getId());
        });

        return $listing;
    }
}
