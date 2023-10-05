<?php


namespace App\Controller\Admin;

use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminFormationsController extends AbstractController
{
    /**
     * @Route("/admin/formations", name="app_admin_formations")
     */
    public function index(): Response
    {
        return $this->render('admin/admin_formations/index.html.twig', [
            'controller_name' => 'AdminFormationsController',
        ]);
    }
}
