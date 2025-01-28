<?php

namespace App\Controller;
use App\Entity\ProfilUser;
use App\Entity\User;
use App\Entity\Projects;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;
use App\Form\ProjectType;
use App\Service\UploaderService;



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
        // $profil = $members->getUser();
        // dd($profil);
        // dd($members);
        return $this->render('projects/showDetails.html.twig', [
            'project' => $project,
            'project_members' => $members
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function test(): Response
    {
        // Obtenir la date actuelle
        $date = new \DateTime();

        // Transmettre le mois et le jour au template
        return $this->render('projects/test.html.twig', [
            'currentMonth' => $date->format('F'), // Mois complet (ex: January)
            'currentDay' => $date->format('j'),  // Jour du mois (ex: 24)
        ]);
    }

    // #[Route('/tasks/{idProject}/{idUser}', name: 'app_tasks')]
    // public function getUserTasks(Request $request, ManagerRegistry $doctrine, $idUser,$idProject, TaskRepository $repository): Response
    // {
    //     $user = $doctrine->getRepository(User::class)->find($idUser);
    //     $project = $doctrine->getRepository(Projects::class)->find($idProject);
    //     $tasks = $repository->findUserTasksForProject($user->getId(), $project->getId());

    //     // $tasks = $user->getTasks();
    //     dd($tasks);
    //     return $this->render('projects/test.html.twig', [
    //         'tasks' => $tasks
    //     ]);
    // }

    // afficher les taches de chaque memebre
    #[Route('/task/{idProject}/{idUser}', name: 'app_task', methods: ['GET'])]
    public function viewUserTasks(Request $request, ManagerRegistry $doctrine, $idUser, $idProject, TaskRepository $repository): JsonResponse
    {
        $user = $doctrine->getRepository(User::class)->find($idUser);
        $project = $doctrine->getRepository(Projects::class)->find($idProject);
        $tasks = $repository->findUserTasksForProject($user->getId(), $project->getId());
        // dd($tasks);
        // Transformer les tâches en un tableau simple
        $tasksData = array_map(function ($task) {
            return [
                'id' => $task->getId(),
                'name' => $task->getTitle(),
                'status' => $task->getStatus(),
            ];
        }, $tasks);
        // dd($tasksData);

        // Retourner une réponse JSON
        return new JsonResponse(['tasks' => $tasksData]);
    }


    #[Route('/projectProgress/{id}', name: 'app_project_progress')]
    public function projectProgress(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $project = $doctrine->getRepository(Projects::class)->find($id);
        $tasks = $project->getTasks();

        return $this->render('projects/projectProgress.html.twig', [
            'project' => $project,
            'tasks' => $tasks
        ]);
    }

    #[Route('/taskManager/{idTask}', name: 'app_task_manager', methods: ['GET'])]
    public function viewTaskManager(ManagerRegistry $doctrine, int $idTask, TaskRepository $repository): JsonResponse
    {
        // Récupérer la tâche par son ID
        $task = $repository->find($idTask);

        // Vérifier si la tâche existe
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found!'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer les utilisateurs associés à la tâche
        $managers = $task->getUsers();

        // Mapper les données des utilisateurs
        $managerData = [];
        foreach ($managers as $manager) {
            $managerData[] = [
                'id' => $manager->getId(),
                'firstname' => $manager->getFirstName(),
                'lastname' => $manager->getLastName(),
                'email' => $manager->getUser()->getEmail(),
            ];
        }

        // Retourner une réponse JSON
        return new JsonResponse(['managers' => $managerData]);
    }


    #[Route('/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(Request $request, Task $task, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $task->setTitle($data['name']);
        }
        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }
        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        $em->persist($task);
        $em->flush();

        return new JsonResponse(['message' => 'Task updated successfully!'], Response::HTTP_OK);
    }

    //add new projects
    #[Route('/project/add', name: 'app_project_add')]
    public function addPersonne(ManagerRegistry $doctrine, Request $request, UploaderService $uploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $project=new Projects();
        // $personne = new Personne(); 
        $form = $this->createForm(ProjectType::class, $project);
        $form->remove('created_at');
        $form->remove('updated_at');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $brochureFile */
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            //  so the PDF file must be processed only when a file is uploaded
             if ($photo) {
                 $directory = $this->getParameter('personne_directory');
                 $project->setImage($uploader->uploadFile($photo,$directory));
            }
            $project->setCreatedAt(new \DateTimeImmutable());
            $project->setUpdatedAt(new \DateTimeImmutable());
            // $project->setCreatedBy($this->getUser());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($project);
            $entityManager->flush();
            $this->addFlash('success', 'Le project a été ajoutée avec succès!');
            return $this->redirectToRoute('app_projects');
        }
        return $this->render('projects/FormProject.html.twig',['form' => $form->createView()]);
    }
}
