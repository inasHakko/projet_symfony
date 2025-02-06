<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ProfilUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class MembersController extends AbstractController
{
    #[Route('/members', name: 'app_members')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(ProfilUser::class)->findAll();
        return $this->render('members/index.html.twig', [
            'users' => $users,
            'project_members' => $users
        ]);
    }


    // #[Route('/user/{id}', name: 'update_role', methods: ['PUT'])]
    // public function updateTask(Request $request, User $user, ManagerRegistry $doctrine): JsonResponse
    // {
    //     $em = $doctrine->getManager();
    //     $data = json_decode($request->getContent(), true);

    //     // if (isset($data['name'])) {
    //     //     $task->setTitle($data['name']);
    //     // }
    //     // if (isset($data['description'])) {
    //     //     $task->setDescription($data['description']);
    //     // }
    //     if (isset($data['status'])) {
    //         $user->setRoles($data['status']);
    //     }

    //     $em->persist($user);
    //     $em->flush();

    //     return new JsonResponse(['message' => 'Task updated successfully!'], Response::HTTP_OK);
    // }


    #[Route('/user/{id}', name: 'update_role', methods: ['PUT'])]
    public function updateRole(Request $request, User $user, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        if (isset($data['status'])) {
            $user->setRoles($data['status']);
        }

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'Task updated successfully!'], Response::HTTP_OK);
    }



#[Route('/delete-user/{id}', name: 'delete_user', methods: ['GET'])]
public function deleteUser($id, ManagerRegistry $doctrine, User $userRepository): Response
{
    // Récupérer l'utilisateur depuis la base de données
    $entityManager = $doctrine->getManager();
    $user = $doctrine->getRepository(User::class)->find($id);

    // Vérifier si l'utilisateur existe
    if (!$user) {
        $this->addFlash('error', 'Utilisateur non trouvé.');
        return $this->redirectToRoute('app_members'); // Redirige vers la liste des utilisateurs
    }

    // Supprimer l'utilisateur
    $entityManager->remove($user);
    $entityManager->flush();

    // Ajouter un message flash
    $this->addFlash('success', 'Utilisateur supprimé avec succès.');

    // Rediriger vers la liste des utilisateurs
    return $this->redirectToRoute('app_members');
}

}
