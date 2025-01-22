<?php

namespace App\Controller;
use App\Entity\ProfilUser;
use App\Entity\User;
use App\Entity\Projects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;


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
}
