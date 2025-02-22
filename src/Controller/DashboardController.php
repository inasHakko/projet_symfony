<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ProfilUser;
use App\Entity\User;
use App\Entity\Projects;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;
use App\Form\ProjectType;
use App\Service\UploaderService;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //récuperer les projects
        $user = $this->getUser();
        $profil = $doctrine->getRepository(\App\Entity\ProfilUser::class)
                           ->findOneBy(['user' => $user]);
        if ($profil) {
        $tasks= $profil->getTasks();
        //récuperer ts les tasks

        //une condition qui vérifie si la variable $tasks est non null
        
        $taskTermine = 0; //les tâches terminées de l'utilisateur
        for ($i=0; $i<count($tasks); $i++) {
            // compter le nombre de tasks terminéés
            $task = $tasks[$i];
            if ($task->getStatus() == 'Terminée') {
                $taskTermine = $taskTermine +  1;
            }
        }

        $taskRestant = count($tasks) - $taskTermine; //les tâches restantes de l'utilisateur
        
        $entityManager = $doctrine->getManager();
        $allTasks = $entityManager->getRepository(Task::class)->findAll(); //toute les tâches du projet
        $allTasksTermine = 0; //toute les tâches finies  de tous les projets
        for ($i=0; $i<count($allTasks); $i++) {
            // compter le nombre de tasks terminéés
            $task = $allTasks[$i];
            if ($task->getStatus() == 'Terminée') {
                $allTasksTermine = $allTasksTermine +  1;
            }
        }
        $allTaskRestant = count($allTasks) - $allTasksTermine; //toute les tâches restantes  de tous les projets

        $projectsPersonnels = $profil->getProjects(); //ts les projets de l'utilisateur
        $projects = $entityManager->getRepository(Projects::class)->findAll(); //tous les projets
        $members = $entityManager->getRepository(ProfilUser::class)->findAll();
        return $this->render('dashboard/index.html.twig', [
            'projects' => $projects,
            'projectPersonnels' => $projectsPersonnels,
            'tasks' => $tasks,
            'user' => $user,
            'profil' => $profil,
            'members' => $members,
            'taskPersoTermine' => $taskTermine,
            'taskPersoRestant' => $taskRestant,
            'nbrProjectPerso' => count($projects),
            'nbrMembers' => count($members),
            'allTasksTermine' => $allTasksTermine,
            'allTasksRestant' => $allTaskRestant,
            'nbrAllProjects' => count($allTasks),
        ]);
    } else {
        return $this->render('dashboard/index.html.twig', [
            'projects' => [],
            'projectPersonnels' => [],
            'tasks' => [],
            'user' => $user,
            'profil' => $profil,
            'members' => [],
            'taskPersoTermine' => 0,
            'taskPersoRestant' => 0,
            'nbrProjectPerso' => 0,
            'nbrMembers' => 0,
            'allTasksTermine' => 0,
            'allTasksRestant' => 0,
            'nbrAllProjects' => 0,
        ]);

    }
    }

}
