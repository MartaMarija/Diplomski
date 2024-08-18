<?php

namespace App\Controller;

use App\Contract\Transformer\PaymentTransformerContract;
use App\FormType\PaymentFormType;
use App\Repository\PaymentRepository;
use App\Trait\PaymentTransformerTrait;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Payment;
use Pimcore\Model\DataObject\Trip;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends BaseController
{
    use PaymentTransformerTrait;

    public function __construct(
        private PaymentRepository $paymentRepository,
        private Security $security,
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

        $transformerClass = $this->transformer[$paymentObject::class];
        /** @var PaymentTransformerContract $transformer */
        $transformer = new $transformerClass(
            $this->paymentRepository,
            $paymentObject,
            $hikingAssociation
        );

        $paymentDetails = $transformer->getPaymentDetails();

        $message = $transformer->canMemberCreatePayment($this->security->getUser());
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

            $transformer->createPayment($payment, $form['File']->getData());

            return $this->respondWithSuccess(['message' => $transformer->getSuccessfulPaymentMessage()]);
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
