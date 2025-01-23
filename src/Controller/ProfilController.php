<?php

namespace App\Controller;

use App\Entity\ProfilUser;
use App\Entity\User;
use App\Entity\Projects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TaskRepository;
class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        // dd($user);
        // $project = $user->getProjects();

        // Récupérer le profil associé à l'utilisateur
        $profil = $doctrine->getRepository(\App\Entity\ProfilUser::class)
                           ->findOneBy(['user' => $user]);
        // dd($profil->getUser()->getId());
        $projects = $profil->getProjects();
        // Vérifier le contenu des objets récupérés
        // dd($user, $profil);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'profil' => $profil,
            'projects' => $projects
        ]);
    }

    #[Route('/profil/project_Details/{id}', name: 'app_project_detaills')]
    public function projectDetails(ManagerRegistry $doctrine, $id, TaskRepository $repository): Response
    {
        // Récupérer les informations du projet
        $project = $doctrine->getRepository(\App\Entity\Projects::class)
                        ->find($id);

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $profil = $doctrine->getRepository(\App\Entity\ProfilUser::class)
                           ->findOneBy(['user' => $user]);
        // $email = $user->getEmail();
        // $user = new User();
        // dd($user);
        // Récupérer les tâches de l'utilisateur pour le projet sélectionné
        $tasks = $repository->findUserTasksForProject($profil->getId(), $project->getId());
        // dd($tasks);

        return $this->render('profil/projectDetails.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'user' => $user
        ]);
    }



}
