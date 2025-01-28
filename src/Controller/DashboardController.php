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
        $entityManager = $doctrine->getManager();
        $projects = $entityManager->getRepository(Projects::class)->findAll();
        return $this->render('dashboard/index.html.twig', [
            'projects' => $projects
        ]);
    }

}
