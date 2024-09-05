<?php

namespace App\Service\Payment;

use App\Repository\PaymentRepository;
use App\Service\TripService;
use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Payment;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TripPaymentService extends AbstractPaymentService
{
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected TripService $tripService,
    )
    {
        parent::__construct($paymentRepository);
    }

    /**
     * @inheritDoc
     */
    public function getPaymentDetails(): ?array
    {
        return $this->paymentObject->getPriceDetails()?->getItems();
    }

    public function createPayment(Payment $payment, ?UploadedFile $file): Payment
    {
        $path = sprintf('/Planinarska društva/%s/Izleti/%s/Uplate',
            $this->hikingAssociation->getKey(), $payment->getPaymentObject()->getKey()
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

        if (!$this->tripService->tripHasCapacity($this->paymentObject)) {
            return 'Sva mjesta za ovaj izlet su već popunjena';
        }

        if ($this->paymentObject->getStartDate() < new \DateTime()) {
            return 'Izlet se već održao';
        }

        return null;
    }
}
