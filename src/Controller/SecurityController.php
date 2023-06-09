<?php

namespace App\Controller;

use App\Classes\Soins;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, CaresRepository $caresRepository): Response
    {
        $user = "";
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Je remplace le message d'erreur par défaut par un message personnalisé en français!!!
        if($error !== null){
            $error = "";
            $this->addFlash('danger', 'Identifiants incorrects. Veuillez réessayer.');
        }
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Je récupère la liste des soins pour la carte de réservation
        $soins = new Soins($caresRepository);
        $soins = $soins->getSoins();

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'soins' => $soins,
                'user' => $user,
            ],
        );
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UsersRepository $usersRepository,
        TokenGeneratorInterface $tokenGeneratorInterface,
        EntityManagerInterface $entityManager,
        SendMailService $mail
    ): Response {
        $form =  $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // generation d'un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // creation des donnée du mail
                $context = compact('url', 'user');

                // on envoie le mail
                $mail->send(
                    'no-reply@site.fr',
                    $user->getEmail(),
                    'Réinitilisation de mot de passe',
                    'password_reset',
                    $context
                );
                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }
            $this->addFlash('danger', 'Un problème est survenue');
            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'security/reset_password_request.html.twig',
            [
                'requestPassForm' => $form->createview(),
                'user' => ''
            ]
        );
    }

    #[Route('oublie/{token}', name: 'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UsersRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $usersRepository->findOneByResetToken($token);

        if ($user) {
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView(),

                'user' => $user

            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
}
