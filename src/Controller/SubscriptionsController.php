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

        $dataArray = [
             'success' => true,
             'subscriptions' => $this->generateSubscriptions($mr)
            ];

        return $this->json($dataArray);

    }

    protected function generateSubscriptions(ManagerRegistry $mr): array{

        $returnArray = [];

        $entityManager = $mr->getManager();
        
        

        $subscription1 = (new Subscription)
            ->setName("Netflix")
            ->setPayments("")
            ->setStartDate(new DateTime())
            ->setCancelDate(new DateTime("31.12.2022"));
            // ->setPaymentPeriod(['Monthly']);

        $entityManager->persist($subscription1);

        $subscription2 = (new Subscription)
            ->setName("Amazon Prime")
            ->setPayments("")
            ->setStartDate(new DateTime())
            ->setCancelDate(new DateTime("31.12.2022"));
            // ->setPaymentPeriod(['Quarterly']);

            $entityManager->persist($subscription2);
            
        $subscription3 = (new Subscription)
            ->setName("Spotify")
            ->setPayments("")
            ->setStartDate(new DateTime())
            ->setCancelDate(new DateTime("31.12.2022"));
            // ->setPaymentPeriod(['Weekly']);    

            $entityManager->persist($subscription3);

        // wegschreiben
        $entityManager->flush();        

        return $returnArray;

    }
}