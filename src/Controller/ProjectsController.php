<?php

namespace App\Controller;
use App\Entity\ProfilUser;
use App\Entity\User;
use App\Entity\Projects;
use App\Entity\Task;
use App\Form\AddMemberType;
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
        return $this->render('projects/showDetails.html.twig', [
            'project' => $project,
            'project_members' => $members
        ]);
    }

    //retrouver les tâches du user avec son id dans un projet avec son id
    #[Route('/tasks/{idProject}/{idUser}', name: 'app_tasks',methods: ['GET'])]
    public function getUserTasks(Request $request, ManagerRegistry $doctrine, $idUser,$idProject, TaskRepository $repository): JsonResponse
    {
        $user = $doctrine->getRepository(User::class)->find($idUser);
        $project = $doctrine->getRepository(Projects::class)->find($idProject);
        $tasks = $repository->findUserTasksForProject($user->getId(), $project->getId());

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

    // afficher les taches de chaque memebre
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


    #[Route('/projectProgress/{id}', name: 'app_project_progress')]
    public function projectProgress(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $project = $doctrine->getRepository(Projects::class)->find($id);
        $tasks = $project->getTasks();

        return $this->render('projects/projectProgress.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'idProject' => $project->getId(),
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



    #[Route('/delete-task/{idProject}/{idTask}', name: 'delete_task', methods: ['GET'])]
    public function deleteTask($idTask,$idProject, ManagerRegistry $doctrine): Response
    {
        // Récupérer l'utilisateur depuis la base de données
        $entityManager = $doctrine->getManager();
        $task = $doctrine->getRepository(Task::class)->find($idTask);
        $project = $doctrine->getRepository(Projects::class)->find($idProject);
        // Vérifier si l'utilisateur existe
        if (!$task) {
            $this->addFlash('error', 'tâche non trouvé.');
            return $this->redirectToRoute('app_project_progress',['id' => $idProject]); // Redirige vers la liste des utilisateurs
        }

        // Supprimer l'utilisateur
        $entityManager->remove($task);
        $entityManager->flush();

        // Ajouter un message flash
        $this->addFlash('success', 'tâche supprimé avec succès.');

        // Rediriger vers la liste des utilisateurs
        return $this->redirectToRoute('app_project_progress', ['id' => $idProject]);

    }

    // delete member from the project
    #[Route('/delete-member/{idProject}/{idUser}', name: 'delete_member', methods: ['GET'])]
    public function deleteMember($idUser,$idProject, ManagerRegistry $doctrine): Response
    {
        // Récupérer l'utilisateur depuis la base de données
        $entityManager = $doctrine->getManager();
        $user = $doctrine->getRepository(ProfilUser::class)->find($idUser);
        $project = $doctrine->getRepository(Projects::class)->find($idProject);
        // Vérifier si l'utilisateur existe
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_project_showDetails',['id' => $idProject]); // Redirige vers la liste des utilisateurs
        }

        // Supprimer l'utilisateur
        $project->removeUser($user);
        $entityManager->persist($project);
        $entityManager->flush();

        // Ajouter un message flash
        $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        // Rediriger vers la liste des utilisateurs
        return $this->redirectToRoute('app_project_showDetails',['id' => $idProject]); // Redirige vers la liste des utilisateurs

    }

    // form to add member to the project
    // #[Route('/add-member/{idProject}', name: 'add_member_to_project', methods: ['GET', 'POST'])]
    // public function addMemberToProject($idProject, Request $request, ManagerRegistry $doctrine): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
    //     $project = $doctrine->getRepository(Projects::class)->find($idProject);
    //     $form = $this->createForm(ProjectType::class, $project);
    //     $form->remove('name');
    //     $form->remove('description');
    //     $form->remove('photo');
    //     $form->remove('created_at');
    //     $form->remove('updated_at');

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $entityManager = $doctrine->getManager();
    //         $user = $form->get('user')->getData();
    //         $project->addUser($user);
    //         $entityManager->persist($project);
    //         $entityManager->flush();
    //         $this->addFlash('success', 'Utilisateur ajouté avec succès!');
    //         return $this->redirectToRoute('app_project_showDetails',['idProject' => $idProject]);
    //     }
    //     return $this->render('projects/addNewMember.html.twig',['form' => $form->createView()]);

    // }

    // #[Route('/add-member/{idProject}', name: 'add_member_to_project', methods: ['GET', 'POST'])]
    // public function addMemberToProject($idProject, Request $request, ManagerRegistry $doctrine): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');

    //     $entityManager = $doctrine->getManager();
    //     $project = $entityManager->getRepository(Projects::class)->find($idProject);

    //     if (!$project) {
    //         throw $this->createNotFoundException('Projet non trouvé.');
    //     }

    //     // Récupérer uniquement les tâches du projet
    //     $tasks = $entityManager->getRepository(Task::class)->findBy(['project' => $project]);

    //     // Créer le formulaire
    //     $form = $this->createForm(AddMemberType::class);
        
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user = $form->get('users')->getData();
    //         $selectedTasks = $form->get('tasks')->getData(); // Tâches sélectionnées

    //         if (!$user) {
    //             $this->addFlash('error', 'Veuillez sélectionner un utilisateur.');
    //             return $this->redirectToRoute('add_member_to_project', ['idProject' => $idProject]);
    //         }

    //         // Ajouter l'utilisateur au projet
    //         $project->addUser($user);

    //         // Assigner les tâches sélectionnées à cet utilisateur
    //         foreach ($selectedTasks as $task) {
    //             $task->addUser($user);
    //             $entityManager->persist($task);
    //         }

    //         $entityManager->persist($project);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Utilisateur ajouté avec succès !');
    //         return $this->redirectToRoute('app_project_showDetails', ['idProject' => $idProject]);
    //     }

    //     return $this->render('projects/addNewMember.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }


}
