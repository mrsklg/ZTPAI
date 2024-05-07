<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserDetails;
use App\Entity\UserType;
use App\Form\UserRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
//    #[Route('/login', name: 'app_login')]
//    public function apiLogin(#[CurrentUser] $user = null): Response
//    {
//        return $this->json([
//            'user' => $user ? $user->getId() : null,
//        ]);
//    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, #[CurrentUser] $user = null): Response
    {
        if ($request->isMethod('POST')) {
            if (!$user) {
                return $this->json([
                    'error' => 'Invalid login request: check that the Content-Type header is "application/json".',
                ], 401);
            }

            return $this->json([
                'user' => $user->getId(),
                'redirect' => $this->generateUrl('dashboard')
            ]);
        }
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setIdUserDetails($form->get('id_user_details')->getData());
            $user->setUserTypeId($entityManager->getRepository(UserType::class)->findOneBy(['id' => 1]));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/signup.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        throw new \Exception('logout() should never be reached');
//        return $this->redirectToRoute('start_page');
    }
}
