<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Personne;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\PersonneRepository;
// use Symfony\Component\Form\FormTypeInterface;
use App\Form\PersonneFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\Helpers;
use App\Service\UploaderService;
use Psr\Log\LoggerInterface;
use App\Service\MailerService;

class PersonneController extends AbstractController
{
    // private $logger;
    public function __construct(private LoggerInterface $logger, private Helpers $helpers){

    }


    #[Route('/add', name: 'app_personne_add')]
    public function addPersonne(ManagerRegistry $doctrine, Request $request, UploaderService $uploader): Response
    {
        $personne = new Personne(); 
        $form = $this->createForm(PersonneFormType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $brochureFile */
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $directory = $this->getParameter('personne_directory');
                $personne->setImage($uploader->uploadFile($photo,$directory));
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($personne);
            $entityManager->flush();
            $this->addFlash('success', 'La personne a été ajoutée avec succès!');
            return $this->redirectToRoute('app_personne_alls');
        }
        return $this->render('personne/FormPersonne.html.twig',['form' => $form->createView()]);
    }

    #[Route('/findByAge/{ageMin}/{ageMax}', name: 'app_personne_findByAge')]
    public function findAge(PersonneRepository $repository, $ageMin, $ageMax): Response
    {
        // $repository = $doctrine->getRepository(Personne::class);
        // dd($repository);
        
        $personnes = $repository->findByAge($ageMin, $ageMax);

        return $this->render('personne/listePersonne.html.twig', ['personnes' => $personnes]); 
    }

    #[Route('/edit/{id?0}', name: 'app_personne_edit')]
    public function editPersonne(ManagerRegistry $doctrine, Request $request, Personne $personne = null, $id , MailerService $mailer): Response
    {
        $entityManager = $doctrine->getManager();
        $newPersonne = false;

        if (!$personne) {
            $newPersonne = true;
            $personne = new Personne();
        }
        // $personne = new Personne();
        $form = $this->createForm(PersonneFormType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($personne);
            $entityManager->flush();

            if($newPersonne){
                $personne->setCreatedAt(new \DateTimeImmutable());
                $personne->setUpdatedAt(new \DateTime());
                $message = "personne has been added successfully";
                $this->addFlash('success', $message);
            }else{
                $personne->setUpdatedAt(new \DateTime());
                $message ="personne has been updated successfully";
                $this->addFlash('success', $message);
            }
            $mailMessage = $personne->getFirstname(). ' ' . $personne->getName(). ' ' .$message;
            $mailer->sendEmail(content: $mailMessage);
            // $this->addFlash('success', 'La personne a été ajoutée avec succès!');
            return $this->redirectToRoute('app_personne_alls');
        }
        return $this->render('personne/FormPersonne.html.twig',['form' => $form->createView()]);
    }


    #[Route('/list', name: 'app_personne_List')]
    public function ListePersonne(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository('App\Entity\Personne');
        $personnes = $repository->findAll(); //select * from personne

        return $this->render('personne/listePersonne.html.twig',
    [
        'personnes' => $personnes,
    ]);
    }

    #[Route('/personne/{name}/{id}', name: 'app_personne_details')]
    public function detailsPersonne(ManagerRegistry $doctrine, $id, $name): Response
    {
        $repository= $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);
        if(!$personne){
            $this->addFlash('error', "la personne avec cet id $id et name $name  n'existe pas");
            return $this->redirectToRoute('app_personne_List');
        }

        return $this->render('personne/detailsPersonne.html.twig', ['personne' => $personne]);
    }

    #[Route('/all/{page?1}/{nbr?12}', name: 'app_personne_alls')]
    public function pagination(ManagerRegistry $doctrine, int $page, int $nbr): Response
    {
        $repository = $doctrine->getRepository(Personne::class);

        // Pagination : Récupérer les personnes

        // Nombre total de personnes
        // $nbrPersonnes = $repository->count([]);
        // $helpers = new Helpers();
        // dd($this->helpers->sayCC());
        $personnes = $repository->findAll();
        $nbrPersonnes = count($personnes);
        $personnes = $repository->findBy([], [], $nbr, ($page - 1) * $nbr);

        // Calcul du nombre de pages
        $pages = ceil($nbrPersonnes / $nbr);

        // Vérification si la page demandée existe
        if ($page > $pages && $pages > 0) {
            $this->addFlash('error', "La page $page n'existe pas");
            return $this->redirectToRoute('app_personne_List', ['page' => $pages, 'nbr' => $nbr]);
        }

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbrPage' => $pages,
            'page' => $page,
            'nbre' => $nbr
        ]);
    }

    #[Route('/delete/{id}', name: 'app_personne_delete')]
    public function deletePersonne(ManagerRegistry $doctrine, $id): RedirectResponse
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);
        if (!$personne) {
            $this->addFlash('error', "la personne avec cet id $id n'existe pas");
            return $this->redirectToRoute('app_personne_alls');
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($personne);
        $entityManager->flush();

        $this->addFlash('success', "La personne avec l'id $id a bien été supprimée");
        return $this->redirectToRoute('app_personne_alls');
    }

    #[Route('/update/{id}/{firstname}/{name}/{age}/{job}', name: 'app_personne_update')]
    public function updatePersonne(ManagerRegistry $doctrine, $id, $firstname, $name, $age,$job): RedirectResponse
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);
        if (!$personne) {
            $this->addFlash('error', "la personne avec cet id $id n'existe pas");
            return $this->redirectToRoute('app_personne_alls');
        }else{
            $personne->setFirstname($firstname);
            $personne->setName($name);
            $personne->setAge($age);
            $personne->setJob($job);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($personne);
            $entityManager->flush();

            $this->addFlash('success', "La personne avec l'id $id a bien été modifiée");
            return $this->redirectToRoute('app_personne_alls');
        }
    }

}
