<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function registrieren(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry): Response
    {
        $registrationForm = $this->createForm(RegistrationType::class);

        $registrationForm->handleRequest($request);

        if($registrationForm->isSubmitted() && $registrationForm->isValid()){
            $data = $registrationForm->getData();
            $user = (new User())
                ->setEmail($data['email']);
            $role = ['1' => 'ROLE_USER'];
            $user->setRoles($role);
            $user
                ->setPassword($userPasswordHasher->hashPassword($user,$data['password']));
            $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/registration.html.twig', [
            'registration_form' => $registrationForm->createView(),
        ]);
    }
}
