<?php

namespace App\Controller;

use App\Entity\ProfilUser;
use App\Entity\User;
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

        // Récupérer le profil associé à l'utilisateur
        $profil = $doctrine->getRepository(\App\Entity\ProfilUser::class)
                           ->findOneBy(['user' => $user]);

        // Vérifier le contenu des objets récupérés
        // dd($user, $profil);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'profil' => $profil,
        ]);
    }
}
