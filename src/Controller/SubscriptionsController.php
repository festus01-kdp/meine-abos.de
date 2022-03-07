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
        // var_dump($request);
        return new Response('list subscritions');
    }
}