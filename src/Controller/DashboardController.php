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
        $tasks= $profil->getTasks();
        //récuperer ts les tasks
        
        $taskTermine = 0;
        for ($i=0; $i<count($tasks); $i++) {
            // compter le nombre de tasks terminéés
            $task = $tasks[$i];
            if ($task->getStatus() == 'Terminée') {
                $taskTermine = $taskTermine +  1;
            }
        }
        $taskRestant = count($tasks) - $taskTermine;
        $entityManager = $doctrine->getManager();
        $allTasks = $entityManager->getRepository(Task::class)->findAll();
        $allTasksTermine = 0;
        for ($i=0; $i<count($allTasks); $i++) {
            // compter le nombre de tasks terminéés
            $task = $allTasks[$i];
            if ($task->getStatus() == 'Terminée') {
                $allTasksTermine = $allTasksTermine +  1;
            }
        }
        $allTaskRestant = count($allTasks) - $allTasksTermine;
        $projectsPersonnels = $profil->getProjects();
        $projects = $entityManager->getRepository(Projects::class)->findAll();
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
    }

}
