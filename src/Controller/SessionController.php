<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        $session=$request->getSession();
        if($session->has('nbrVisite')){
            $nbrVisite=$session->get('nbrVisite')+1;
        }else{
            $nbrVisite=1;
        }
        $session->set('nbrVisite', $nbrVisite);
        return $this->render('session/index.html.twig');
    }
}
