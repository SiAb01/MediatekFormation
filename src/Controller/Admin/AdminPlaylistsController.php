<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;



class AdminPlaylistsController extends AbstractController
{
   
  /**
*
* @var PlaylistRepository
*/
private $playlistRepository;

/**
*
* @var FormationRepository
*/
private $formationRepository;

/**
*
* @var CategorieRepository
*/
private $categorieRepository;  

const PAGE_ADMINPLAYLISTS = "admin/admin_playlists/admin_playlists.html.twig" ;
const PAGE_PLAYLIST = "pages/playlist.html.twig";
const ROUTE_ADMINPLAYLISTS = "app_admin_playlists";

public function __construct(PlaylistRepository $playlistRepository,
       CategorieRepository $categorieRepository,
       FormationRepository $formationRespository) {
   $this->playlistRepository = $playlistRepository;
   $this->categorieRepository = $categorieRepository;
   $this->formationRepository = $formationRespository;
}

/**
* @Route("/admin/playlists", name="app_admin_playlists")
* @return Response
*/
public function index(): Response{
    $playlists = $this->playlistRepository->findAllbyOrderAndCount('name', 'ASC');
    $categories = $this->categorieRepository->findAll();
    return $this->render(self::PAGE_ADMINPLAYLISTS, [
        'playlists' => $playlists,
        'categories' => $categories,
       
    ]);
 }
 
 /**
 * @Route("/admin/playlists/sort/{champ}/{ordre}", name="adminplaylists.sort")
 * @param type $champ
 * @param type $ordre
 * @return Response
 */
 public function sort($champ, $ordre): Response{
    $playlists = $this->playlistRepository->findAllbyOrderAndCount($champ, $ordre);
    $categories = $this->categorieRepository->findAll();
    return $this->render(self::PAGE_ADMINPLAYLISTS, [
        'playlists' => $playlists,
        'categories' => $categories
 
    ]);
    
 }
 
 /**
 * @Route("/admin/playlists/recherche/{champ}/{table}", name="adminplaylists.findallcontain")
 * @param type $champ
 * @param Request $request
 * @param type $table
 * @return Response
 */
 public function findAllContain($champ, Request $request, $table=""): Response{
    $valeur = $request->get("recherche");
    $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
    $categories = $this->categorieRepository->findAll();
    return $this->render(self::PAGE_ADMINPLAYLISTS, [
        'playlists' => $playlists,
        'categories' => $categories,
        'valeur' => $valeur,
        'table' => $table
    ]);
 }



/**
 * Affiche un form d'ajout d'une playlist et l'ajoute si soumet et valide
 * @param Request $request
 * @Route("admin/playlist/add"  , name="adminplaylist.add")
 * @return Response
 */
public function addPlaylist (Request $request , FlashBagInterface $flashBag) : Response {

    $playlistAdd = new Playlist();
    $formPlaylist = $this->createForm(PlaylistType::class , $playlistAdd);
    $formTitle = "Ajout d'une nouvelle playlist" ;
    
    $formPlaylist->handleRequest($request);
    
    if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
        $this->playlistRepository->add($playlistAdd, true);
        $flashBag->add('success', 'La nouvelle playlist a bien été ajoutée ');
       
        return $this->redirectToRoute('app_admin_playlists');
    }
  
    return $this->render("admin/admin_playlists/admin_playlist_add.html.twig", [
       
        'playlistAdd' => $playlistAdd ,
        'formPlaylist' => $formPlaylist->createView(),
        'formTitle' => $formTitle
      
    ]);
    
    }



    /** Créer et affiche le form d'edit d'une playlist et enrengistre la modification
     * @param Playlist $playlistEdit
     * @param Request $request
     * @Route("admin/playlist/edit/{id}"  , name="adminplaylist.edit")
     * @return Response
     */
    public function edit(Playlist $playlistEdit, Request $request , FlashBagInterface $flashBag): Response{
          
        $formPlaylist = $this->createForm(PlaylistType::class, $playlistEdit);
        $formationsInPlaylist = $playlistEdit->getFormations(); //lecture seulement
        
        $formTitle = "Modification de la playlist: " . $playlistEdit->getName();
        $formPlaylist->handleRequest($request);
         if ($formPlaylist->isSubmitted()  && $formPlaylist->isValid()){
           $this->playlistRepository->add($playlistEdit, true);
           $flashBag->add('success', 'La playlist a bien été modifiée ');
            return $this->redirectToRoute(self::ROUTE_ADMINPLAYLISTS);
        }
    
        return $this->render('admin/admin_playlists/admin_playlist_edit.html.twig', [
            'playlistEdit' => $playlistEdit,
             'formPlaylist'=> $formPlaylist->createView(),
            'formations' => $formationsInPlaylist,
            'formTitle' => $formTitle,
           
        ]);
    }


    /**
     * @param Playlist $playlistDeleted
     *  @Route("admin/playlist/delete/{id}"  , name="adminplaylist.delete")
     * @return Response
     */
    public function delete (Playlist $playlistDeleted , FlashBagInterface $flashBag) : Response{

        $formations = $playlistDeleted->getNumberOfFormations();
   if ($formations > 0){
    $flashBag->add('error', 'La playlist ne peut pas être supprimée car elle a des formations associées.');

    return $this->redirectToRoute(self::ROUTE_ADMINPLAYLISTS);
   }

   $this->playlistRepository->remove($playlistDeleted, true) ;
   $flashBag->add('success', 'La playlist a bien été supprimée avec succès.');
   return $this->redirectToRoute(self::ROUTE_ADMINPLAYLISTS);

    }
    



    }
    
    



