<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class SubscriptionsController
{
    /**
     * @Route("/subscriptions", name="subscriptions")
     */
    public function list(): Response
    {
        
        return new Response('list subscritions');
    }
}