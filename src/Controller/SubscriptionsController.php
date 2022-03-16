<?php

namespace App\Controller;

use App\Entity\PaymentType;
use App\Entity\Subscription;
use App\Serializer\SubscriptionNormalizer;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionsController extends AbstractController
{

    public function list(ManagerRegistry $mr, RouterInterface $router): Response
    {

        $subscriptions = $mr->getRepository(Subscription::class)->findAll();

        if (!$subscriptions) {
            return $this->json(['success' => false], status: 404);
        }
        $serializer = new Serializer([new SubscriptionNormalizer($router)]);
        $subscriptionsCollection = [];

        foreach ($subscriptions as $subscription) {
            $normalize = $serializer->normalize($subscription, null, ['circular_reference_handler' => function ($object) {
                return $object->getId();
            }]);

            $subscriptionsCollection[] = $normalize;

        }

        $dataArray = [
            'data' => $subscriptionsCollection,
            'links' => $router->generate('listSubscriptions')
        ];

        return $this->json($dataArray);

    }

    public function create(Request $request, ManagerRegistry $mr, ValidatorInterface $validator, RouterInterface $router): Response
    {

        $routerInterface = $router->getRouteCollection()->get('createSubscription');
        $subscription = new Subscription($router);

        $this->setDataToSubscription($request->request->all(), $subscription);

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

        $em->persist($subscription);
        $em->flush();

        return $this->json(['success' => true, 'subscription' => $subscription], status: 201);

    }

    public function read(int $id, Request $request, ManagerRegistry $mr): Response
    {
        $subscription = $mr->getRepository(Subscription::class)->find($id);

        if (!$subscription) {
            return $this->json([], 400);
        }

        return $this->json(['data' => $subscription]);
    }

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
