<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ProfilUser;


class MembersController extends AbstractController
{
    #[Route('/members', name: 'app_members')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(ProfilUser::class)->findAll();
        return $this->render('members/index.html.twig', [
            'users' => $users
        ]);
    }
}
