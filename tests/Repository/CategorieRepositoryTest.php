<?php
namespace App\Tests\Repository;

use App\Entity\Categorie;

use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * [Description CategorieRepositoryTest]
 */
class CategorieRepositoryTest extends KernelTestCase {

    /**
     * Récupère le repository de Categorie
     * @return CategorieRepository
     */
    public function recupCategorieRepository() {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    public function findAllForOnePlaylist() {

    }
}