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

    public function testFindAllOrderBy() {
      
    }
    
    public function testFindAllbyOrderAndCount() {
        
    }
    
    public function testFindOnePlaylist() {
        
    }
    
    public function testFindByContainValue() {
        
    }
    








}


