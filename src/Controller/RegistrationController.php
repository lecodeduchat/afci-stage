<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\JWTService;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Form\PatientsSearchFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UsersAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt,
        UsersRepository $usersRepository,
        TokenGeneratorInterface $tokenGeneratorInterface
    ): Response {
        $user = new Users();

        $patientsSearchForm = $this->createForm(PatientsSearchFormType::class);
        $patientsSearchForm->handleRequest($request);

        if ($patientsSearchForm->isSubmitted() && $patientsSearchForm->isValid()) {
            $patient = $patientsSearchForm->getData();
            $email = $patient->getEmail();
            $firstname = $patient->getFirstname();
            $lastname = $patient->getLastname();
            $user = $usersRepository->findPatientBy($email, $firstname, $lastname, '["ROLE_PATIENT"]');
            // TODO: Si un patient est trouvé, on lui envoie un email avec un lien sécurisé pour qu'il puisse mettre à jour son profil et créer son mot de passe
            if ($user) {
                // generation du jwt de l'utilisateur
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];
                $payload = [
                    'user_id' => $user->getId()
                ];
                $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // generation d'un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('app_register_patient', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // creation des donnée du mail
                $context = compact('url', 'user');

                // on envoie le mail
                $mail->send(
                    'no-reply@site.fr',
                    $user->getEmail(),
                    'Création de votre espace patient',
                    'inscription_patient',
                    $context
                );
                $this->addFlash('success', 'Un email vous a été envoyé avec un lien pour finaliser votre Espace patient.');
                return $this->redirectToRoute('app_login');
            }
            $this->addFlash('danger', 'Un problème est survenue');
            return $this->redirectToRoute('app_login');
        } else {
            // Si pas de patient trouvé, on affiche un message flash et on invite le patient à s'inscrire
        }

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Ajout du role ROLE_USER par défaut
        $user->setRoles(['ROLE_USER']);
        // Ajout de la valeur par défaut pour le champs is_verified
        $user->setIsVerified(false);
        // Vérification du numéro de téléphone fixe
        $home_phone = $user->getHomePhone();
        // Suppression des points
        $home_phone = str_replace('.', '', $home_phone);
        // J'enlève le premier 0 s'il y en a un
        $home_phone = ltrim($home_phone, '0');
        $user->setHomePhone($home_phone);

        // Vérification du numéro de téléphone portable
        $cell_phone = $user->getCellPhone();
        // Suppression des points
        $cell_phone = str_replace('.', '', $cell_phone);
        // J'enlève le premier 0 s'il y en a un
        $cell_phone = ltrim($cell_phone, '0');
        $user->setCellPhone($cell_phone);

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

            // generation du jwt de l'utilisateur
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];
            $payload = [
                'user_id' => $user->getId()
            ];
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            // Pour l'envoie du mail 
            $mail->send(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Activation de votre compte sur le site Charlotte Vandermersch',
                'register',
                compact('user', 'token')
            );
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        return $this->render('registration/register.html.twig', [
            'patientsSearchForm' => $patientsSearchForm->createView(),
            'registrationForm' => $form->createView(),
            'user' => ''
        ]);
    }

    #[Route('/inscription-patient/{token}', name: 'app_register_patient')]
    public function registerPatient($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $userPasswordHasher,): Response
    {

        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {

            $payload = $jwt->getPayload($token);
            $user = $usersRepository->find($payload['user_id']);

            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            // Vérification du numéro de téléphone portable
            $cell_phone = $user->getCellPhone();
            // Suppression des points
            $cell_phone = str_replace('.', '', $cell_phone);
            // J'enlève le premier 0 s'il y en a un
            $cell_phone = ltrim($cell_phone, '0');
            $user->setCellPhone($cell_phone);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setIsVerified(true);
                $user->setResetToken('');
                $user->setRoles(['ROLE_USER']);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $usersRepository->update($user, true);

                $this->addFlash('success', 'Votre espace patient a bien été créé.');

                return $this->redirectToRoute('profile_show');
            }
        } else {
            $this->addFlash('danger', 'Le token est invalide ou a expiré');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_patient.html.twig', [
            'user' => $user,
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            $payload = $jwt->getPayload($token);
            $user = $usersRepository->find($payload['user_id']);

            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('profile_index');
            }
            $this->addFlash('danger', 'Le token est invalide ou a expiré');
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/renvoiverif/{route}', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository, $route): Response
    {

        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('profile_index');
        }

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // On crée le Payload
        $payload = [
            'user_id' => $user->getId()
        ];

        // On génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // On envoie un mail
        $mail->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Activation de votre compte sur le site Charlotte Vandermersch',
            'register',
            compact('user', 'token')
        );

        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute($route);
    }
}
