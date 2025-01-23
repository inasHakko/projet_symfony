<?php

namespace App\Controller;
use App\Entity\ProfilUser;
use App\Entity\User;
use App\Entity\Projects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;



class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'app_projects')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //récuperer les projects
        $entityManager = $doctrine->getManager();
        $projects = $entityManager->getRepository(Projects::class)->findAll();
        return $this->render('projects/index.html.twig', [
            'projects' => $projects
        ]);
    }

    //récuperer les détails du project
    #[Route('/project/{id}', name: 'app_project_showDetails', requirements: ['id' => '\d+'])]
    public function show(Projects $project, ManagerRegistry $doctrine, $id): Response
    {
        $project = $doctrine->getRepository(\App\Entity\Projects::class)
                           ->find($id);

        $members = $project->getUsers();
        return $this->render('projects/showDetails.html.twig', [
            'project' => $project,
            'project_members' => $members
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function test(): Response
    {
        return $this->render('projects/test.html.twig', [
        ]);
    }

    #[Route('/tasks/{idProject}/{idUser}', name: 'app_tasks')]
    public function getUserTasks(Request $request, ManagerRegistry $doctrine, $idUser,$idProject, TaskRepository $repository): Response
    {
        $user = $doctrine->getRepository(User::class)->find($idUser);
        $project = $doctrine->getRepository(Projects::class)->find($idProject);
        $tasks = $repository->findUserTasksForProject($user->getId(), $project->getId());

        // $tasks = $user->getTasks();
        dd($tasks);
        return $this->render('projects/test.html.twig', [
            'tasks' => $tasks
        ]);
    }

    #[Route('/task/{idProject}/{idUser}', name: 'app_task', methods: ['GET'])]
    public function viewUserTasks(Request $request, ManagerRegistry $doctrine, $idUser, $idProject, TaskRepository $repository): JsonResponse
    {
        $user = $doctrine->getRepository(User::class)->find($idUser);
        $project = $doctrine->getRepository(Projects::class)->find($idProject);
        $tasks = $repository->findUserTasksForProject($user->getId(), $project->getId());

        // Transformer les tâches en un tableau simple
        $tasksData = array_map(function ($task) {
            return [
                'id' => $task->getId(),
                'name' => $task->getTitle(),
                'status' => $task->getStatus(),
            ];
        }, $tasks);

        // Retourner une réponse JSON
        return new JsonResponse(['tasks' => $tasksData]);
    }


}
