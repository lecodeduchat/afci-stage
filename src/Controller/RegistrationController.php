<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $user = new Users();
        // $created_at ajouté manuellement car il manquait la fonction construct dans Users !!!
        // $created_at = new \DateTimeImmutable();
        // $user->setCreatedAt($created_at);

        // Injections des rôles par défaut
        // $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Ajout du role ROLE_USER par défaut
        $user->setRoles(['ROLE_USER']);
        // Vérification du numéro de téléphone
        $phone = $user->getPhone();
        // Suppression des points
        $phone = str_replace('.', '', $phone);
        $user->setPhone($phone);

        // Vérification de la date de naissance
        $birthday = $user->getBirthday();
        if ($birthday) {
            $year = $birthday->format('Y');
            $month = $birthday->format('m');
            $day = $birthday->format('d');
            if ($year < 1900 || $year > 2021 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
                // dump($birthday);
                $this->addFlash('danger', 'La date de naissance est incorrecte');
                // TODO : Trouver comment rediriger vers la page d'inscription avec les données déjà saisies
                return $this->redirectToRoute('app_register');
            }
        }
        // dd($user);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => '',
        ]);
    }
}
