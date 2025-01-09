<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo_list')]
    public function index(Request $request): Response
    {
        $session=$request->getSession();
        if (!$session->has('todos')){
            $todos = [
                'Task 1' => 'kiss me',
                'Task 2' => 'lick me',
                'Task 3' => 'fuck me',
            ];
            $session->set('todos',$todos);
        }
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/add/{cle}/{value}', name: 'app_todo_add')]
    public function add(Request $request, $cle, $value): Response{
        $session=$request->getSession();
        if($session->has('todos')){
            $todos1 = $session->get('todos');
            if(isset($todos1[$cle])){
                $this->addFlash(
                    'error',
                    "la cle existe déja"
                 );
            }else{
                $todos1[$cle] = $value;
                $session->set('todos',$todos1);
                $this->addFlash(
                   'success',
                    "la cle a été ajoutée avec succès"
                );
            }
        }else{
            $this->addFlash(
               'info',
               "le tableau n'existe pas"
            );
        }
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/update/{cle}/{value}')]
    public function update(Request $request, $cle, $value): Response{
        $session=$request->getSession();
        if($session->has('todos')){
            $todos1 = $session->get('todos');
            if(isset($todos1[$cle])){
                //$session->remove('todos', $cle);
                $todos1[$cle] = $value;
                $session->set('todos',$todos1);
                $this->addFlash(
                   'success',
                    "la cle a été modifiée avec succès"
                );
            } else{
                $this->addFlash(
                    'error',
                    "la cle n'existe pas"
                );
            }
        }
        return $this->render('/todo/index.html.twig');
    }

    #[Route('/todo/delete', name: 'app_todo_delete')]
    public function delete(Request $request): Response{
        $session=$request->getSession();
        if($session->has('todos')){
            $todos= $session->get('todos');
            $session->remove('todos');
        }
        return $this->render('/todo/index.html.twig');
    }
}
