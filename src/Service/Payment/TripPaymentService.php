<?php

namespace App\Service\Payment;

use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Payment;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TripPaymentService extends AbstractPaymentService
{
    /**
     * @inheritDoc
     */
    public function getPaymentDetails(): ?array
    {
        return $this->paymentObject->getPriceDetails()?->getItems();
    }

    public function createPayment(Payment $payment, ?UploadedFile $file): Payment
    {
        $year = date('Y');
        $path = sprintf('/Planinarska društva/%s/Izleti/%s/%s/Uplate',
            $this->hikingAssociation->getKey(), $year, $payment->getPaymentObject()->getKey()
        );
        $payment->setParent(Service::createFolderByPath($path));

        return $this->paymentRepository->createPayment($this->hikingAssociation, $payment, $file);
    }

    public function getSuccessfulPaymentMessage(): string
    {
        return 'Uspješno ste se zabilježeli za izlet ' . $this->paymentObject->getName();
    }

    public function canMemberCreatePayment(?Member $member): ?string
    {
        $message = parent::canMemberCreatePayment($member);

        if (null !== $message) {
            return $message;
        }

        if ($this->paymentRepository->isMemberAppliedForTrip($member, $this->paymentObject)) {
            return 'Korisnik je već prijavljen za izlet.';
        }

        if (!$this->paymentRepository->tripHasCapacity($this->paymentObject)) {
            return 'Sva mjesta za ovaj izlet su već popunjena';
        }

        if ($this->paymentObject->getStartDate() < new \DateTime()) {
            return 'Izlet se već održao';
        }

        return null;
    }
}
