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
    /**
     * @return [type]
     */
    public function testFindAllbyOrderAndCount() {
        $repository= $this->recupPlaylistRepository();
        
        $champ = ' name'; 
        $ordre = 'ASC'; 
        $expectedCount = 32;
        $firstPlaylistIdExpected = 122;
       
        $playlists = $repository->findAllbyOrderAndCount($champ, $ordre);
        $methodId =$playlists[0]['id'] ;
        
     
        $this->assertNotEmpty($playlists , "Liste playliste non vide");
        $this->assertEquals($firstPlaylistIdExpected, $methodId, 'Pas le bon id de la 1ère playlist retourné pas la requete');
        $this->assertCount($expectedCount , $repository->findAllbyOrderAndCount($champ, $ordre), "Pas le bon nbr playlist de la requete");


    }
}