<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Entity\Categorie;
use App\Repository\PlaylistRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    
    /**
    * @var PlaylistRepository
    */
     private $playlistRepository ;


    public function __construct(ManagerRegistry $registry , PlaylistRepository $playlistRepository)
    {

        parent::__construct($registry, Formation::class);
        $this->playlistRepository = $playlistRepository;
    }

    public function add(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

/**
 * Ajoute une formation avec sa playlist et sa collection de  catégorie(s).
 *
 * @param Formation $userFormation L'objet Formation à ajouter.
 * @param Playlist $userPlaylist L'objet Playlist associé à la formation.
 * @param array $userCategories Un tableau d'objets de Catégorie associés à la formation.
 *
 * @return void
 */
    public function addFormation(Formation $userFormation, Playlist $userPlaylist, array  $userCategories)
    {
        // Créer une nouvelle instance de Formation
        $userFormationEntity = new Formation();
    
        // Ajouter chaque catégorie à la collection de la formation
        $this->addFormationCategories($userFormationEntity, $userCategories);
    
        // Établir la relation avec la playlist
        $this->addFormationPlaylist($userFormationEntity, $userPlaylist);
    
        // Persist et flush
        $this->getEntityManager();
        $this->persist($userFormationEntity);
        $this->flush();
    
        return $userFormationEntity; // Vous pouvez retourner l'entité créée si nécessaire
    }

    
    /**
     * Ajoute un ou plusieurs catégories à une formation , pas utilisé
     * @param Formation $userFormation
     * @param array $userCategories
     * 
     * @return [type]
     */
    private function addFormationCategories(Formation $userFormation, array $userCategories )
    {
        // Supposons que $userFormations soit l'objet de type Formation
    // et $userCategories soit un tableau d'objets de type Categorie
    
    // Obtenez la collection de catégories liées à l'utilisateur
    $userCategoriesCollection = $userFormation->getCategories();
    
    // Parcourez les catégories que vous souhaitez ajouter
    foreach ($userCategories as $userCategorie) {
        // Vérifiez si la catégorie est déjà présente dans la collection
        if (!$userCategoriesCollection->contains($userCategorie)) {
            // Si elle n'est pas présente, ajoutez-la à la collection
            $userCategoriesCollection->add($userCategorie);
        }
    }
    
    
        $this->persist($userFormation);
        $this->flush();
    }
    
    /**
     * @param Formation $userFormation
     * @param Playlist $userPlaylist
     * 
     * @return [type]
     */
    private function addFormationPlaylist(Formation $userFormation,Playlist $userPlaylist)
    {
        // D'abord vérifier que userPlaylist en le chercher parmi les playlists existent
     
        $this->playlistRepository->find($userPlaylist->getId());
        $userFormation->setPlaylist($userPlaylist);
    
        // Persist et flush pour la playlist ajoutée
        $entityManager = $this->getEntityManager();
        $entityManager->persist($userFormation);
        $entityManager->flush();
    }
    










    public function remove(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retourne toutes les formations triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @param type $table si $champ dans une autre table
     * @return Formation[]
     */
    public function findAllOrderBy($champ, $ordre, $table=""): array{
        if($table==""){
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->orderBy('t.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();            
        }
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAll();
        }
        if($table==""){
            return $this->createQueryBuilder('f')
                    ->where('f.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();            
        }else{
            return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->where('t.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();                   
        }       
    }    
    
    /**
     * Retourne les n formations les plus récentes
     * @param type $nb
     * @return Formation[]
     */
    public function findAllLasted($nb) : array {
        return $this->createQueryBuilder('f')
                ->orderBy('f.publishedAt', 'DESC')
                ->setMaxResults($nb)     
                ->getQuery()
                ->getResult();
    }    
    
    /**
     * Retourne la liste des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('f.publishedAt', 'ASC')   
                ->getQuery()
                ->getResult();        
    }
    
     
    /**
     * Retourne la liste des formations d'une playlist et le nombre de formations
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylistCount($idPlaylist): array{
        // Pour faire le select imbriqué
        $subQuery = $this->createQueryBuilder('f_sub')
        ->select('COUNT(f_sub.id)')
        ->where('f_sub.playlist = f.playlist')
        ->getDQL();


        $mainQuery =$this->createQueryBuilder('f')
     
        ->select('f', '(' . $subQuery . ') as numberOfFormations')
        ->join('f.playlist', 'p')
        ->where('p.id = :id')
        ->setParameter('id', $idPlaylist)
        ->orderBy('f.publishedAt', 'ASC');
        
        return  $mainQuery->getQuery()->getResult();
        
        
    }




}
