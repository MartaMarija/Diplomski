<?php

namespace App\Repository;

use Pimcore\Db;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Payment;
use Pimcore\Model\DataObject\Trip;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PaymentRepository
{
    public function __construct(
        private Security $security,
        private TripRepository $tripRepository,
    )
    {
    }

    public function createPayment(HikingAssociation $hikingAssociation, Payment $payment, ?UploadedFile $file)
    {
        $member = $this->security->getUser();

        $payment->setMember($member);
        $payment->setKey($payment->getMember()->getEmail());
        $payment->setPublished(true);
        $payment->setYear(date('Y'));

        if (!empty($file)) {
            $paymentAsset = $this->savePaymentFileAsAsset($hikingAssociation, $file, $payment->getDescription());
            $payment->setPaymentConfirmation($paymentAsset);
        }

        return $payment->save();
    }

    private function savePaymentFileAsAsset(HikingAssociation $hikingAssociation, UploadedFile $file, string $type): Asset
    {
        $path = sprintf('/%s/Uplate/%s', $hikingAssociation->getKey(), $type);
        $parent = Asset\Service::createFolderByPath($path);

        $asset = new Asset();
        $asset->setParent($parent);
        $asset->setData($file->getContent());
        $asset->setFilename($this->security->getUser()->getUserIdentifier() . ' - ' . uniqid() . '.pdf');
        $asset->setMimeType('application/pdf');

        return $asset->save();
    }

    public function isUserMember(?Member $member, HikingAssociation $hikingAssociation)
    {
        $queryBuilder = Db::getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('oo_id')
            ->from('object_Payment')
            ->where('Member__id = :memberId')
            ->andWhere('PaymentObject__id = :hikingAssociationId')
            ->andWhere('Year = :year')
            ->setParameters([
                'memberId' => $member->getId(),
                'hikingAssociationId' => $hikingAssociation->getId(),
                'year' => date('Y')
            ])
            ->setMaxResults(1);

        $result = $queryBuilder->executeQuery()->fetchOne();

        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public function isMemberAppliedForTrip(?Member $member, Trip $trip)
    {
        $queryBuilder = Db::getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('oo_id')
            ->from('object_Payment')
            ->where('Member__id = :memberId')
            ->andWhere('PaymentObject__id = :tripId')
            ->setParameters([
                'memberId' => $member->getId(),
                'tripId' => $trip->getId()
            ])
            ->setMaxResults(1);

        $result = $queryBuilder->executeQuery()->fetchOne();

        if (!empty($result)) {
            return true;
        }

        return false;
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
