<?php

namespace App\Controller\Admin;

namespace App\Controller\Admin;

use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin\index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
