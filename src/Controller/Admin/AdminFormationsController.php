<?php


namespace App\Controller\Admin;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminFormationsController extends AbstractController
{

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

    const PAGE_ADMINFORMATION = "\admin\admin_formations\admin_formation.html.twig";
  
    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/admin/formations", name="app_admin_formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMINFORMATION, [
            'formations' => $formations,
            'categories' => $categories,
            'showForm' => false

        ]);
    }

    /**
     * @Route("/admin/formations/tri/{champ}/{ordre}/{table}", name="adminformations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMINFORMATION, [
            'formations' => $formations,
            'categories' => $categories,
            'showForm' => false
        ]);
    }
    
    /**
     * @Route("/admin/formations/recherche/{champ}/{table}", name="adminformations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMINFORMATION, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table,
            'showForm' => false
        ]);
    }
    

   
/**
 * Affiche un form d'ajout d'une formation et l'ajoute si soumet et valide
 * @param Request $request
 * @Route("admin/formation/add"  , name="adminformation.add")
 * @return Response
 */
public function addFormation (Request $request) : Response {

$formationadd = new Formation();
$formFormation = $this->createForm(FormationType::class , $formationadd);
$formTitle = "Ajout d'une nouvelle formation";

$formations = $this->formationRepository->findAll();
$categories = $this->categorieRepository->findAll();



$formFormation->handleRequest($request);

if ($formFormation->isSubmitted() && $formFormation->isValid()) {
    $this->formationRepository->add($formationadd, true);
   
    return $this->redirectToRoute('app_admin_formations');
}

return $this->render(self::PAGE_ADMINFORMATION, [
    'formations' => $formations,
    'categories' => $categories,
    'formationadd' => $formationadd ,
    'formFormation' => $formFormation->createView(),
    'showForm' => true,
    'formTitle' => $formTitle,
]);

}


/**
 * Affiche le formulaire d'édition de formulaire et modifie le formulaire
 * @param Formation $formationEdit
 * @param Request $request
 *  @Route("admin/formation/edit/{id}"  , name="adminformation.edit")
 * @return Response
 */
public function edit(Formation $formationEdit, Request $request): Response{
      
    $formFormation = $this->createForm(FormationType::class, $formationEdit);
    $formations = $this->formationRepository->findAll();
    $categories = $this->categorieRepository->findAll();
    
    $formTitle = "Modification de la formation: " . $formationEdit->getTitle();

    $formFormation->handleRequest($request);

    if($formFormation->isSubmitted() && $formFormation->isValid()){
       $this->formationRepository->add($formationEdit, true);
        return $this->redirectToRoute('app_admin_formations');
    }

    return $this->render(self::PAGE_ADMINFORMATION, [
        'formationEdit' => $formationEdit,
        'formFormation' => $formFormation->createView(),
        'formations' => $formations,
        'categories' => $categories,
        'formTitle' => $formTitle,
        'showForm' => true
    ]);
}


/**
 * Supprime une formation
 * @Route("admin/formation/delete/{id}"  , name="adminformation.delete")
 * @param Formation $formationDelete
 * @param Request $request
 *
 * @return Response
 */
public function delete ( Formation $formationDelete ) :Response{

$this->formationRepository->remove($formationDelete, true);
return $this->redirectToRoute('app_admin_formations');

}



   
}
