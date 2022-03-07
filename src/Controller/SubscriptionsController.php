<?php

namespace App\Controller;

use App\Model\Subscription;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionsController extends AbstractController
{
    /**
     * @Route("/subscriptions", name="list")
     */
    public function list(Request $request): Response
    {

        $dataArray = [
             'success' => true,
             'subscriptions' => $this->generateSubscriptions()
            ];

        return $this->json($dataArray);

    }

    protected function generateSubscriptions(): array{

        $returnArray = [];

        $returnArray[] = (new Subscription)
            ->setName("Netflix")
            ->setStartDate(new DateTime())
            ->toArray();

            $returnArray[] = (new Subscription)
            ->setName("Amazon Prime")
            ->setStartDate(new DateTime())
            ->toArray();
            
            $returnArray[] = (new Subscription)
            ->setName("Spotify")
            ->setStartDate(new DateTime())
            ->toArray();    
        
        return $returnArray;

    }
}