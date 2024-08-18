<?php

namespace App\Transformer\PaymentTransformer;

use App\Contract\Transformer\PaymentTransformerContract;
use App\Repository\PaymentRepository;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Member;

abstract class AbstractPaymentTransformer implements PaymentTransformerContract
{
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected $paymentObject,
        protected HikingAssociation $hikingAssociation
    )
    {
    }

    public function canMemberCreatePayment(?Member $member): ?string
    {
        if (empty($member)) {
            return 'Za učlanjenje se potrebno prijaviti u aplikaciju.';
        }

        return null;
    }
}
