<?php

namespace App\Serializer;

use App\Entity\Subscription;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class SubscriptionNormalizer implements ContextAwareNormalizerInterface
{
    protected RouterInterface $router;

    public  function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Subscription $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = []):array
    {
        $returnData = [];
        $returnData['type'] = 'subscription';
        $returnData['id'] = $object->getId();
        $returnData['attributes'] = [
            'name' => $object->getName(),
            'startDate' => $object->getStartDate(),
        ];
        $returnData['links'] = [
            'self' => $this->router->generate('readSubscription', ['id' => $object->getId()])
        ];
        /*$returnData['relationships'] = [
            'paymentType' => [
                'links' =>  [
                    'related' => 'Andern'
                ]
            ]
        ];*/

        $this->createRelationLinks($returnData, $object);

        return $returnData;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {

        return $data instanceof Subscription;
    }

    protected function createRelationLinks(array &$returnData, Subscription $subscription):void
    {
        if ($subscription->getPaymentType()) {
            $returnData['relationships'] = [
                'paymentType' => [
                    'links' =>  [
                        'related' => $this->router->generate('readPaymentType', ['id' => $subscription->getPaymentType()->getId()])
                    ]
                ]
            ];
        }

    }
}