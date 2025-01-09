<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TabController extends AbstractController
{
    #[Route('/tab', name: 'app_tab')]
    public function index(): Response
    {
        return $this->render('tab/index.html.twig', [
            'controller_name' => 'TabController',
        ]);
    }

    #[Route('/tab/users', name: 'app_tab_users')]
    public function users(): Response{
        $users = [
            ['name' => 'Alice', 'email' => 'alice@example.com', 'age' => 12],
            ['name' => 'Bob', 'email' => 'bob@example.com', 'age' => 23],
            ['name' => 'Charlie', 'email' => 'charlie@example.com', 'age' => 15],
        ];
        // dd($users);
        return $this->render('tab/index.html.twig', [
            'users' => $users
        ]);
    }
}
