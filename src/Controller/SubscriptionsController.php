<?php

namespace App\Controller;

use App\Entity\PaymentType;
use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Serializer\SubscriptionNormalizer;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Class SubscriptionsController
 * @package App\Controller
 * @Route("/app")
 */
class SubscriptionsController extends AbstractController
{

    /**
     * @Route("/subscriptions", name="listSubscriptions")
     */

    public function list(ManagerRegistry $mr): Response
    {

        $subscriptions = $mr->getRepository(Subscription::class)->findBy(['user' => $this->getUser()]);


        return $this->render('subscription/list.html.twig', [
            'subscriptions' => $subscriptions
        ]);

    }
    /**
     * @Route("/subscription/new", name="newSubscription", methods={"GET","POST"})
     */
    public function new(Request $request, RouterInterface $router, ManagerRegistry $mr): Response
    {

        $subscription = new Subscription($router);

        $eingabeFormular = $this->createForm(SubscriptionType::class, $subscription);

        $eingabeFormular->handleRequest($request);

        if ($eingabeFormular->isSubmitted() && $eingabeFormular->isValid()) {
            $em = $mr->getManager();
            $em->persist($subscription);

            return $this->redirectToRoute('listSubscriptions');

        }

        return $this->render('subscription/new.html.twig', [
            'formular' => $eingabeFormular->createView()
        ]);


    }
    /**
     * @Route("/subscription/{id}", name="detailSubscription", methods={"GET","POST"})
     */
    public function detail(int $id, Request $request, ManagerRegistry $mr): Response
    {
        $subscription = $mr->getRepository(Subscription::class)->find($id);
        $this->isGranted('POST_MANAGE', $subscription);

        $eingabeFormular = $this->createForm(SubscriptionType::class, $subscription);

        $eingabeFormular->handleRequest($request);

        if ($eingabeFormular->isSubmitted() && $eingabeFormular->isValid()) {
            $em = $mr->getManager();
            $em->flush();
        }

        return $this->render('subscription/detail.html.twig', [
            'formular' => $eingabeFormular->createView(),
            'title' => $subscription->getName()
        ]);

    }
    /**
     * @Route("/subscription/{id}", name="updateSubscription", methods={"PUT"})
     */
    public function update(int $id, Request $request, ManagerRegistry $mr, ValidatorInterface $validator): Response
    {
        $subscription = $mr->getRepository(Subscription::class)->find($id);
        $req = null;
        if (!$subscription) {
            return $this->json(['success' => false, 'message' => 'ID: ' . $id . ' not Found']);
        }
        /** TODO
         * FK_PaymentType manuell laden
         * Automatisch muss noch
         * Bitte anpassen
         */
        $paymentTypeId = (int)$request->request->get('paymentType');
        if ($paymentTypeId) {
            $paymentType = $mr->getRepository(PaymentType::class)->find($paymentTypeId);
            if ($paymentType) {
                /* Hier den request[paymentType] mit der übergebenen ID
                   gegen das Object $paymentType austauschen,
                    damit die function setPaymentType korrekt aufgerufen werden kann
                 */
                $req = $request->request->all();
                $req['paymentType'] = $paymentType;

            } else {
                $request->request->remove('paymentType');
            }
        }

        $this->setDataToSubscription($req, $subscription);

        $errors = $validator->validate($subscription);

        if (sizeof($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ":" . $violation->getMessage();
            }
            return $this->json(['success' => false, 'errors' => $errorMessages], status: 400);
        }

        $em = $mr->getManager();
        $em->flush();

        return $this->json(['success' => true, 'data' => $subscription]);
    }

    protected function setDataToSubscription(array $requestData, Subscription $subscription)
    {
        foreach ($requestData as $key => $data) {
            /** TODO
             * falls NULL aktuelles Datum setzen
             * mach man das hier oder schon in setter von subscription?
             */
            if ($key === 'startdate' && !$data) {
                $data = new DateTime('now');
            }
            if ($key === 'canceldate' && !$data) {
                $data = new DateTime('now');
            }
            $methodName = 'set' . ucfirst($key);
            if (!empty($data) && method_exists($subscription, $methodName)) {
                $subscription->{$methodName}($data);
            }
        }
    }
}
