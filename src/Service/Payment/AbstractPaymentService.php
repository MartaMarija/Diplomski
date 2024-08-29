<?php

namespace App\Service\Payment;

use App\Contract\Service\PaymentServiceContract;
use App\Repository\PaymentRepository;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Member;

abstract class AbstractPaymentService implements PaymentServiceContract
{
    protected $paymentObject;
    protected HikingAssociation $hikingAssociation;

    public function __construct(
        protected PaymentRepository $paymentRepository,
    )
    {
    }

    public function setPaymentObject($paymentObject): void
    {
        $this->paymentObject = $paymentObject;
    }

    public function setHikingAssociation(HikingAssociation $hikingAssociation): void
    {
        $this->hikingAssociation = $hikingAssociation;
    }

    public function canMemberCreatePayment(?Member $member): ?string
    {
        if (empty($member)) {
            return 'Za učlanjenje se potrebno prijaviti u aplikaciju.';
        }

        return null;
    }
}
