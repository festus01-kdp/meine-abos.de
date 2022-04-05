<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends AbstractController
{
    #[Route('/subscription/{id}/addPayment', name: 'add_payment')]
    public function index(Request $request, EntityManagerInterface $em, int $id): Response
    {

        $payment = $this->paymentFactory($request);
        /** @var Subscription $subscription */
        $subscription = $em->getRepository(Subscription::class)->find($id);
        $payment->setSubscription($subscription);

        $em->persist($payment);
        $em->flush();

        return $this->render('payment/list.html.twig',[
            'payments' => $subscription->getPayments(),
        ]);

    }

    private function paymentFactory(Request $request):Payment {
        // x = request->request->get('payment') BUG in symfony 6
        // deshalb x = request->request->all() und dann
        // x[payment] auf array zugreifen
        $formData = $request->request->all();
        $date = $formData['payment']['date'];

        $amount = $formData['payment']['amount'];

        $date = new \DateTime($date);

        return (new Payment())
            ->setAmount(floatval(str_replace(',','.',$amount)))
            ->setDate($date);
    }
}
