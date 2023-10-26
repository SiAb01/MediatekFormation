<?php


namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


/**
 * [Description AdminCategoriesController]
 * Gére Lecture,Suppression et Ajout de Catégorie
 */
class AdminCategoriesController extends AbstractController
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

const PAGE_ADMINCATEGORIES = 'admin/admin_categories/admin_categories.html.twig' ;
const ROUTE_ADMINCATEGORIES = 'app_admin_categories';

    public function __construct(PlaylistRepository $playlistRepository,
    CategorieRepository $categorieRepository,
    FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
        }


   
    /** Affiche toutes les categories et leurs formations
     * @return Response
     *  @Route("/admin/categories", name="app_admin_categories")
     */
    public function index(): Response
    { $categories = $this->categorieRepository->findAll();
        return $this->render('admin/admin_categories/admin_categories.html.twig', [
            'categories' => $categories,
            'showForm' => false
        ]);
    }



/**
 * Ajoute une catégorie si le nom saisie n'existe pas déjà
 * @param Request $request
 * @param FlashBagInterface $flashBag
 *  @Route("admin/categorie/add"  , name="admincategorie.add")
 * @return Response
 */
public function addCategorie (Request $request , FlashBagInterface $flashBag) : Response {

    $categorieAdd = new Categorie();
    $formCategorie = $this->createForm(CategorieType::class , $categorieAdd);
    $formTitle = "Ajout d'une nouvelle catégorie" ;
    
    $formCategorie->handleRequest($request);
    
    if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $this->cleanCategorieProperties($categorieAdd);
            $this->categorieRepository->add($categorieAdd, true);
            $flashBag->add('success', 'La nouvelle catégorie a bien été ajoutée ');
            return $this->redirectToRoute('app_admin_categories');
        }

    $categories = $this->categorieRepository->findAll();
    return $this->render(self::PAGE_ADMINCATEGORIES, [
       
        'categorieAdd' => $categorieAdd ,
        'categories' => $categories,
        'formCategorie' => $formCategorie->createView(),
        'formTitle' => $formTitle,
        'showForm' => true,

      
    ]);
    
    }

    /**
     * Supprime une catégorie si elle n'est pas associé à une formation
     * @param Categorie $categorieDeleted
     * @param FlashBagInterface $flashBag
     *  @Route("admin/categorie/delete/{id}"  , name="admincategorie.delete")
     * @return Response
     */
    public function delete (Categorie $categorieDeleted , FlashBagInterface $flashBag) : Response{

        $formations = $categorieDeleted->getcountformations();
        if ($formations > 0){
            $flashBag->add('error', 'La catégorie ne peut pas être supprimée car elle a des formations associées.');

            return $this->redirectToRoute(self::ROUTE_ADMINCATEGORIES);
        }

        $this->categorieRepository->remove($categorieDeleted, true) ;
        $flashBag->add('success', 'La catégorie a bien été supprimée avec succès.');
        return $this->redirectToRoute(self::ROUTE_ADMINCATEGORIES);

    }
    


/**
 * @param Categorie
 *  $inputCategorie
 * Nettoyer la propriéte name
 * @return Categorie
 * 
 */
private function cleanCategorieProperties(Categorie $inputCategorie): Categorie

{
    // Nettoyage des inputs de format texte pour la Categorie
    $cleanedName = filter_var($inputCategorie->getName(), FILTER_SANITIZE_SPECIAL_CHARS);
    // Mettre à jour les propriétés de la Categorie
    $inputCategorie->setName($cleanedName);
    return $inputCategorie;
}

}
