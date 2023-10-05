<?php


namespace App\Controller\Admin;

use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminCategoriesController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="app_admin_categories")
     */
    public function index(): Response
    {
        return $this->render('admin/admin_categories/index.html.twig', [
            'controller_name' => 'AdminCategoriesController',
        ]);
    }
}
