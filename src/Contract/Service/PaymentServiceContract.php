<?php

namespace App\Contract\Service;

use Pimcore\Model\DataObject\Fieldcollection\Data\PaymentDetailsFC;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Payment;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PaymentServiceContract
{
    /**
     * @return ?PaymentDetailsFC[]
     */
    public function getPaymentDetails(): ?array;

    public function createPayment(Payment $payment, ?UploadedFile $file): Payment;

    public function getSuccessfulPaymentMessage(): string;

    /**
     * @return ?string - message that explains why Member can't see PaymentForm
     */
    public function canMemberCreatePayment(?Member $member): ?string;

    public function setPaymentObject($paymentObject): void;
    public function setHikingAssociation(HikingAssociation $hikingAssociation): void;
}
