<?php

namespace App\Controller;

use App\Entity\Subscription;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class SubscriptionsController extends AbstractController
{

    public function list(Request $request, ManagerRegistry $mr): Response
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

    public function add(Request $request, ManagerRegistry $mr): Response
    {
        $subscritionName = $request->request->get('name');

        if (is_string($subscritionName)) {

            $subscription = (new Subscription())
                ->setName($subscritionName)
                ->setStartDate(new DateTime())
                ->setPayments('Card')
                ->setCancelDate(new DateTime('31.12.2022'));

            $em = $mr->getManager();

            $em->persist($subscription);
            $em->flush();

            if ($subscription->getId()) {
                return $this->json(['success' => true, 'subscription' => $subscription], status: 201);
            }
        }



        return $this->json(['success' => false], status: 400);
    }
}
