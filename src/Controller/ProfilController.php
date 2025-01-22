<?php

namespace App\Controller;

use App\Entity\ProfilUser;
use App\Entity\User;
use App\Entity\Projects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;
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
    public function projectDetails(ManagerRegistry $doctrine, $id): Response
    {
        // Récupérer les informations du projet
        $project = $doctrine->getRepository(\App\Entity\Projects::class)
                           ->find($id);
        $tasks = $project->getTasks();
        // Vérifier le contenu des objets récupérés
        // dd($project);
        $user = $this->getUser();
        return $this->render('profil/projectDetails.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'user' => $user
        ]);
    }
}
