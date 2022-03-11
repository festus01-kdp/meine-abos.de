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

    public function create(Request $request, ManagerRegistry $mr, ValidatorInterface $validator): Response
    {

        $subscription = new Subscription();

        $this->setDataToSubscription($request->request->all(),$subscription);

        $errors = $validator->validate($subscription);

        if (sizeof($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath().":".$violation->getMessage();
            }
            return $this->json(['success' => false, 'errors' => $errorMessages], status: 400);
        }

        $em = $mr->getManager();

        $em->persist($subscription);
        $em->flush();

        return $this->json(['success' => true, 'subscription' => $subscription], status: 201);

    }

    public function update(int $id, Request $request, ManagerRegistry $mr, ValidatorInterface $validator): Response
    {
        $subscription = $mr->getRepository(Subscription::class)->find($id);

        if(!$subscription) {
            return $this->json(['success' => false, 'message' => 'ID: '.$id.' not Found']);
        }

        $requestData = $request->request->all();

        $this->setDataToSubscription($requestData, $subscription);

        $errors = $validator->validate($subscription);

        if (sizeof($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath().":".$violation->getMessage();
            }
            return $this->json(['success' => false, 'errors' => $errorMessages], status: 400);
        }

        $em = $mr->getManager();
        $em->flush();

        return $this->json(['success' => true, 'Id' => $id]);
    }

    protected function setDataToSubscription(array $requestData, mixed $subscription)
    {
        foreach ($requestData as $key => $data) {
            /** TODO
             * falls NULL aktuelles Datum setzen
             * mach man das hier oder schon in setter von subscription?
             */
            if($key === 'startdate' && !$data) {
                $data = new DateTime('now');
            }
            if($key === 'canceldate' && !$data) {
                $data = new DateTime('now');
            }
            $methodName = 'set' . ucfirst($key);
            if(!empty($data) && method_exists($subscription, $methodName)) {
                $subscription->{$methodName}($data);
            }
        }
    }
}