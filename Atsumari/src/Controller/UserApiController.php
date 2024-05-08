<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserApiController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('GET')) {
            return $this->getUsersWithoutCurrent($request, $entityManager);
        }else {
            return new Response(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/users', name: 'users', methods: ['GET'])]
    private function getUsersWithoutCurrent(Request $request, EntityManagerInterface $entityManager)
    {
        $currentUserId = $this->getUser()->getId();

        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        $usersData = [];
        foreach ($users as $user) {
            if ($user->getId() != $currentUserId) {
                $usersData[] = [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'first_name' => $user->getIdUserDetails()->getFirstName(),
                    'last_name' => $user->getIdUserDetails()->getLastName()
                ];
            }
        }
        $data = [];
        $data['currentUserId'] = $currentUserId;
        $data['usersData'] = $usersData;

        return new JsonResponse($data);
    }
}