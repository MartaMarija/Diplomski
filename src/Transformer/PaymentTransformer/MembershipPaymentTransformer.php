<?php

namespace App\Transformer\PaymentTransformer;

use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Payment;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MembershipPaymentTransformer extends AbstractPaymentTransformer
{

    /**
     * @inheritDoc
     */
    public function getPaymentDetails(): array
    {
        return $this->paymentObject->getMembershipDetails()->getItems();
    }

    public function createPayment(Payment $payment, ?UploadedFile $file): Payment
    {
        $path = sprintf('/Planinarska društva/%s/Članarine/%s', $this->hikingAssociation->getKey(), date('Y'));
        $payment->setParent(Service::createFolderByPath($path));

        return $this->paymentRepository->createPayment($this->hikingAssociation, $payment, $file);
    }

    public function getSuccessfulPaymentMessage(): string
    {
        return 'Uspješno ste se učlanili u planianrskog društvo ' . $this->paymentObject->getName();
    }

    public function canMemberCreatePayment(?Member $member): ?string
    {
        $message = parent::canMemberCreatePayment($member);

        if (null !== $message) {
            return $message;
        }

        if ($this->paymentRepository->isUserMember($member, $this->hikingAssociation)) {
            return 'Korisnik je već član društva.';
        }

        return null;
    }
}
