<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
/**
 * Retourne les playlists leur catégories et le nombre de formations
 *  @param type $champ
 * @param type $ordre
 * @return Playlist[]
 */
public function findAllbyOrderAndCount($champ, $ordre): array
{
    $queryBuilder = $this->createQueryBuilder('p')
        ->select('p.id as id, p.name as name, COUNT(f) as numberOfFormations, c.name as categoriename')
        ->leftJoin('p.formations', 'f')
        ->leftJoin('f.categories', 'c')
        ->groupBy('p.id, p.name, c.name');

    if ($champ === 'numberOfFormations') {
        $queryBuilder->orderBy('numberOfFormations', $ordre);
    } else {
        $queryBuilder->orderBy('p.' . $champ, $ordre);
    }

    return $queryBuilder->getQuery()->getResult();
}

}

