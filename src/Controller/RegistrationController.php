<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function registrieren(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager): Response
    {
        $registrationForm = $this->createForm(RegistrationType::class);

        $registrationForm->handleRequest($request);

        if($registrationForm->isSubmitted() && $registrationForm->isValid()){

            $data = $registrationForm->getData();
            /**TODO: Auf doppelte E-MAil prüfen
             *
             */
            $user =
                $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if (!$user){
                $user = new User();
                $user->setEmail($data['email']);
                $role = ['USER' => 'ROLE_USER'];
                $user->setRoles($role);
                $user->setPassword($userPasswordHasher->hashPassword($user,$data['password']));

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_login');
            } else {
                //TODO: Hier eine schöne HTML-Fehlerseite bauen
                return new Response('<h1>Ups, da ist ein Fehler aufgetreten</h1>',404,['Header1' => 'Was ist da los']);

            }

        }

        return $this->render('registration/registration.html.twig', [
            'registration_form' => $registrationForm->createView(),
        ]);
    }
}
