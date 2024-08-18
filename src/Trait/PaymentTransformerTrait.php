<?php

namespace App\Trait;

use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Trip;

trait PaymentTransformerTrait
{
    public $transformer = [
        HikingAssociation::class => 'App\Transformer\PaymentTransformer\MembershipPaymentTransformer',
        Trip::class => 'App\Transformer\PaymentTransformer\TripPaymentTransformer'
    ];
}
