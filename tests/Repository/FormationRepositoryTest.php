<?php
namespace App\Tests\Repository;

use App\Entity\Formation;

use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase {


    /**
     * Récupère le repository de Formation
     * @return FormationRepository
     */
    public function recupFormationRepository() {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    /**  Pas utilisé
     * @return [type]
     */
    public function getFormations()
    {
        $repository = $this->recupFormationRepository();

        // Création et ajout de la première formation
        $formation1 = new Formation();
        $formation1->setPublishedAt(new \DateTime('now'));
        $formation1->setTitle('Titre de la formation 1');
        $formation1->setDescription('Description de la formation 1');
        $repository->add($formation1,true);

        // Création et ajout de la deuxième formation
        $formation2 = new Formation();
        $formation2->setPublishedAt(new \DateTime('now'));
        $formation2->setTitle('Titre de la formation 2');
        $formation2->setDescription('Description de la formation 2');
        $repository->add($formation2,true);

        // Création et ajout de la troisième formation
        $formation3 = new Formation();
        $formation3->setPublishedAt(new \DateTime('now'));
        $formation3->setTitle('Titre de la formation 3');
        $formation3->setDescription('Description de la formation 3');
        $repository->add($formation3,true);

        // Vous pouvez retourner les formations si nécessaire
        return [$formation1, $formation2, $formation3];
    }
    
    public function testFindAllOrderBy() {

        $champ = ' name';
        $ordre = 'ASC';
        $table = 'playlist';
        $repository= $this->recupFormationRepository();
        $formations = $repository->findAllOrderBy($champ, $ordre, $table);
        // Assurez-vous qu'il y a au moins une formation retournée
        $this->assertNotEmpty($formations);
        // Récupérez la première formation retournée par la méthode find
        $firstFormationIdExpected = 122;
         $repository->find($formations[0]->getId());
        // Comparez les deux formations
        $this->assertEquals($firstFormationIdExpected, $formations[0]->getId(), 'Pas la bonne formation');
    }

  
    public function testFindByContainValue() {
        $repository= $this->recupFormationRepository();

        $champ = 'name';
        $valeur = 'Java';
        $table = 'categories';

        $expectedCount = 32;
 
        $this->assertCount( $expectedCount, $repository->findByContainValue( $champ , $valeur , $table ) );



    
        // Écrivez vos assertions PHPUnit pour vérifier le résultat
        // Par exemple, $this->assertEquals($expected, $result);
    }
    
    public function testFindAllLasted() {
        // Créez un objet de votre classe (par exemple, $obj = new VotreClasse();)
        // Appelez la méthode que vous voulez tester
        $repository= $this->recupFormationRepository();
         //On va tester le nombre de lignes retourner par la fonction findAllLasted
         $nbExpected = 4 ;
       
        $this->assertCount( $nbExpected, $repository->findAllLasted($nbExpected));


       
    }
    
    public function testFindAllForOnePlaylist() {
       
        $idPlaylist = 3 ;
        //Chercher un tableau ou donnée attendue d'une playlist
        $expectedPlaylistFormationsCount = 4 ;
        $repository= $this->recupFormationRepository();
        $repository->findAllForOnePlaylist($idPlaylist);


        $this->assertCount($expectedPlaylistFormationsCount, $repository->findAllForOnePlaylist($idPlaylist));



    }
    







}


