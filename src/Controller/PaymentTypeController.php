<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\PaymentType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentTypeController extends AbstractController
{

    public function list(ManagerRegistry $mr): Response
    {
        $paymentTypes = $mr->getRepository(PaymentType::class)->findAll();

        if (!$paymentTypes) {
            return $this->json(['success' => false], status: 404);
        }

        $dataArray = [
            'success' => true,
            'paymentType' => $paymentTypes
        ];

        return $this->json($dataArray);
    }

    public function create(Request $request, ValidatorInterface $validator, ManagerRegistry $mr): Response
    {

        $paymentType = new PaymentType();

        $this->setDataToPaymentType($request->request->all(),$paymentType);

        $errors = $validator->validate($paymentType);

        if (sizeof($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath().":".$violation->getMessage();
            }
            return $this->json(['success' => false, 'errors' => $errorMessages], status: 400);
        }

        $em = $mr->getManager();

        $em->persist($paymentType);
        $em->flush();

        return $this->json(['success' => true, 'paymentType' => $paymentType], status: 201);
    }

    public function read(): Response
    {

    }

    public function update(int $id, Request $request, ManagerRegistry $mr, ValidatorInterface $validator): Response
    {
        $paymentType = $mr->getRepository(PaymentType::class)->find($id);

        if(!$paymentType) {
            return $this->json(['success' => false, 'message' => 'ID: '.$id.' not Found']);
        }

        $requestData = $request->request->all();

        $this->setDataToPaymentType($requestData, $paymentType);

        $errors = $validator->validate($paymentType);

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

    public function delete(): Response
    {

    }
    protected function setDataToPaymentType(array $requestData, mixed $paymentType)
    {
         foreach ($requestData as $key => $data) {
             $methodName = 'set' . ucfirst($key);
             if(!empty($data) && method_exists($paymentType, $methodName)) {
                 $paymentType->{$methodName}($data);
             }
         }
    }

}