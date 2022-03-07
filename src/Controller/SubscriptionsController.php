<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionsController
{
    /**
     * @Route("/subscriptions", name="list")
     */
    public function list(Request $request): Response
    {
        $response = new Response();
        $response->setContent('<p>Rückgabe</p>');
        return $response;
    }
}