<?php
namespace App\Tests\Repository;

use App\Entity\Playlist;

use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * [Description PlaylistRepositoryTest]
 */
class PlaylistRepositoryTest extends KernelTestCase {

    /**
     * Récupère le repository de Playlist
     * @return PlaylistRepository
     */
    public function recupPlaylistRepository() {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    public function testFindAllbyOrderAndCount() {

    }
}