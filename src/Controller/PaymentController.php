<?php

namespace App\Controller;

use App\Contract\Service\PaymentServiceContract;
use App\FormType\PaymentFormType;
use App\Service\Payment\MembershipPaymentService;
use App\Service\Payment\TripPaymentService;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Payment;
use Pimcore\Model\DataObject\Trip;
use Symfony\Bundle\SecurityBundle\Security;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends BaseController
{
    public function __construct(
        private Security $security,
        #[AutowireLocator([
            HikingAssociation::class => MembershipPaymentService::class,
            Trip::class => TripPaymentService::class
        ])]
        private ContainerInterface $handlers,
    )
    {
    }

    #[Route('/payment/{hikingAssociation}')]
    public function payment(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $paymentDescription = $request->get('paymentDescription');
        $paymentObjectId = $request->get('paymentObject');
        $paymentObject = DataObject::getById($paymentObjectId);

        /** @var PaymentServiceContract $service */
        $service = $this->handlers->get($paymentObject::class);
        $service->setPaymentObject($paymentObject);
        $service->setHikingAssociation($hikingAssociation);

        $paymentDetails = $service->getPaymentDetails();

        $message = $service->canMemberCreatePayment($this->security->getUser());
        if (!empty($message)) {
            $htmlString = $this->renderView('payment/payment.html.twig', [
                'hikingAssociation' => $hikingAssociation,
                'paymentDetails' => $paymentDetails,
                'message' => $message
            ]);

            return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
        }

        $form = $this->createForm(PaymentFormType::class, new Payment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Payment $payment */
            $payment = $form->getData();
            $payment->setPaymentObject($paymentObject);
            $payment->setDescription($paymentDescription);

            $service->createPayment($payment, $form['File']->getData());

            return $this->respondWithSuccess(['message' => $service->getSuccessfulPaymentMessage()]);
        }

        $htmlString = $this->renderView('payment/payment.html.twig', [
            'hikingAssociation' => $hikingAssociation,
            'paymentDetails' => $paymentDetails,
            'form' => $form->createView(),
            'message' => null
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
