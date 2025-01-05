<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Personne;

class PersonneController extends AbstractController
{
    #[Route('/personne/add', name: 'app_personne')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne(); // Assuming Personne is an entity in the App\Entity namespace
        $personne->setFirstname('inas');
        $personne->setName('Hakkou');
        $personne->setAge('23');
        $personne->setJob('développeur');

        //ajouter l'opération d'insertion dans ma transaction
        $entityManager->persist($personne);
        // exécuter la transaction
        $entityManager->flush();
        return $this->render('personne/detailsPersonne.html.twig', [
            'personne' => $personne,
        ]);
    }
}
