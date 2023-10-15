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
     * Retourne toutes les playlists triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderBy($champ, $ordre): array{
        return $this->createQueryBuilder('p')
                ->select('p.id id')
                ->addSelect('p.name name')
                ->addSelect('c.name categoriename')
                ->join('p.formations', 'f')
                ->leftjoin('f.categories', 'c')
                ->groupBy('p.id')
                ->addGroupBy('c.name')
                ->orderBy('p.'.$champ, $ordre)
                ->addOrderBy('c.name')
                ->getQuery()
                ->getResult();       
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


/**
 * Récupérer une playlist avec son nbr fomrmation
 */
public function findOnePlaylist($playlistId)
{
    $queryBuilder = $this->createQueryBuilder('p')
        ->select('p.id as playlistId,  COUNT(f) as numberOfFormations')
        ->leftJoin('p.formations', 'f')
        ->where('p.id = :playlistId')
        ->setParameter('playlistId', $playlistId)
        ->groupBy('p.id ');

    return $queryBuilder->getQuery()->getArrayResult();
}




         
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        // Sous-requete select qui récupère le nombre de formations
        $subQueryBuilder = $this->createQueryBuilder('p_sub')
        ->select('COUNT(f_sub)')
        ->join('p_sub.formations', 'f_sub')
        ->where('p_sub.id = p.id');
        
        
        if($valeur==""){
            return $this->findAllOrderBy('name', 'ASC');
        }    
        if($table==""){      
            return $this->createQueryBuilder('p')
                    ->select('p.id id')
                    ->addSelect('p.name name')
                    ->addSelect('c.name categoriename')
                    ->addSelect(['(' . $subQueryBuilder->getDQL() . ') AS numberOfFormations'])
                    ->join('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->addGroupBy('c.name')
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy('c.name')
                    ->getQuery()
                    ->getResult();              
        }else{   
            return $this->createQueryBuilder('p')
                    ->select('p.id id')
                    ->addSelect('p.name name')
                    ->addSelect('c.name categoriename')
                    ->addSelect(['(' . $subQueryBuilder->getDQL() . ') AS numberOfFormations'])
                    ->join('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->addGroupBy('c.name')
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy('c.name')
                    ->getQuery()
                    ->getResult();
            
        }
    }



}

