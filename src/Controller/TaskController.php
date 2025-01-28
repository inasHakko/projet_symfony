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
use App\Form\TaskType;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/project/{id}/task/add/', name: 'app_task_add')]
    public function addPersonne(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $task = new Task();
        $project = $doctrine->getRepository(Projects::class)->find($id);
        // $project=new Projects();
        // $personne = new Personne(); 
        $form = $this->createForm(TaskType::class, $task);
        // $form->remove('created_at');
        // $form->remove('updated_at');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            
            // $task->setCreatedAt(new \DateTimeImmutable());
            // $project->setUpdatedAt(new \DateTimeImmutable());
            // $project->setCreatedBy($this->getUser());
            $task->setProject($project);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'Le project a été ajoutée avec succès!');
            //assigner la tache au project

            return $this->redirectToRoute('app_project_progress', ['id' => $project->getId()]);
            // return $this->redirectToRoute('app_projects');
        }
        return $this->render('task/index.html.twig',['form' => $form->createView()]);
    }
}
