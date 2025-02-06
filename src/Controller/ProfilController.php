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
use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UploaderService;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        // Récupérer le profil associé à l'utilisateur
        $profil = $doctrine->getRepository(\App\Entity\ProfilUser::class)
                           ->findOneBy(['user' => $user]);
        $projects = $profil->getProjects();
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
        // Récupérer les tâches de l'utilisateur pour le projet sélectionné
        $tasks = $repository->findUserTasksForProject($profil->getId(), $project->getId());
        return $this->render('profil/projectDetails.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'user' => $user
        ]);
    }

    //edit profil
    #[Route('/profil/edit/{id}', name: 'app_edit_profil')]
    public function editProfilId(ManagerRegistry $doctrine, $id, Request $request, UploaderService $uploader): Response
    {
        $user = $this->getUser();
        $profil = $doctrine->getRepository(\App\Entity\ProfilUser::class)
                           ->findOneBy(['user' => $user]);
        $form = $this->createForm(ProfilType::class, $profil);
        //supprimer les champs qu'on n'a pas besoin
        $form->remove('user');
        $form->remove('projects');
        $form->remove('tasks');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $message ="personne has been updated successfully";
            $this->addFlash('success', $message);
            // $profil->setUpdated_at(new \DateTime());
            /** @var UploadedFile $brochureFile */
            $photo = $form->get('image')->getData();
            if ($photo) {
                $directory = $this->getParameter('personne_directory');
                $profil->setImage($uploader->uploadFile($photo,$directory));
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($profil);
            $entityManager->flush();
            $this->addFlash('success', 'Profil modifié avec succès');
            return $this->redirectToRoute('app_profil');
        }
        return $this->render('profil/editProfil.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'profil' => $profil
        ]);
    }

   
}
