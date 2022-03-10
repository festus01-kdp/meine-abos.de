<?php

namespace App\Controller;

use App\Entity\Subscription;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionsController extends AbstractController
{

    public function list(ManagerRegistry $mr): Response
    {

        $subscriptions = $mr->getRepository(Subscription::class)->findAll();

        if (!$subscriptions) {
            return $this->json(['success' => false], status: 404);
        }

        $dataArray = [
            'success' => true,
            'subscriptions' => $subscriptions
        ];

        return $this->json($dataArray);
    }

    public function add(Request $request, ManagerRegistry $mr, ValidatorInterface $validator): Response
    {

        $subscriptionName = $request->request->get('name');
        $subscriptionPayments = $request->request->get('payments');

        $subscription = (new Subscription())
            ->setName($subscriptionName)
            ->setStartDate(new DateTime())
            ->setPayments($subscriptionPayments)
            ->setCancelDate(new DateTime('31.12.2022'));

        $errors = $validator->validate($subscription);

        if (sizeof($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getMessage();
                $errorMessages[] = $violation->getPropertyPath();
            }
            return $this->json(['success' => false, 'errors' => $errorMessages], status: 400);
        }

        $em = $mr->getManager();

        $em->persist($subscription);
        $em->flush();

        return $this->json(['success' => true, 'subscription' => $subscription], status: 201);

    }
}
