<?php

namespace App\Controller;

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
            'subscriptions' => [

            ]
            ];

        return $this->json($dataArray);

    }
}