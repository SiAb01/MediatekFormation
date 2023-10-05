<?php

namespace App\Controller\Admin;

use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminPlaylistsController extends AbstractController
{
    /**
     * @Route("/admin/playlists", name="app_admin_playlists")
     */
    public function index(): Response
    {
        return $this->render('admin/admin_playlists/index.html.twig', [
            'controller_name' => 'AdminPlaylistsController',
        ]);
    }
}
