<?php

namespace App\Repository;
use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * custom request gathering events by category name
     *
     * @param string $category
     * @return void
     */
    public function findByCategory($category)
    {
        return $this->createQueryBuilder('e')
            // we join category table
            ->join('e.category', 'e_c')
            // on sélectionne les events dont la categorie correspond à un paramètre lié
            ->andWhere('e_c.name = :val')
            // on lie le paramètre à la valeur $category (fournit en paramètre de la requête)
            ->setParameter('val', $category)
            // sort by date
            ->orderBy('e.startDate', 'ASC')
            // on lance la requête
            ->getQuery()
            // on retourne le résultat
            ->getResult()
        ;
    }

    /**
     * custom request gathering events by tag name
     *
     * @param string $tag
     * @return void
     */
    public function findByTag($tagId)
    {
        return $this->createQueryBuilder('f')
            // we join tag table
            ->join('f.event_tag', 'e_c')
            // on sélectionne les events dont le tag correspond à un paramètre lié
            ->andWhere('e_c = :tag')
            // on lie le paramètre à la valeur $tag (fournit en paramètre de la requête)
            ->setParameter('tag', $tagId)
            // sort by date
            //->orderBy('e.startDate', 'ASC')
            // on lance la requête
            ->getQuery()
            // on retourne le résultat
            ->getResult()
        ;
    }

    /**
     * custom request gathering events by filters
     * return events which category.name is in filters array
     *
     * @param array $filters
     * @return void
     */
    public function findEvents($filters)
    {
        return $this->createQueryBuilder('e')
            ->join('e.category', 'e_c')
            ->andWhere('e_c.name IN (:vals)')
            ->setParameter(':vals', array_values($filters))
            ->orderBy('e.startDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Custom FindALL to have OrderBy startDate
     *
     * @return void
     */
    public function findAll()
    {
        return $this->findBy([],['startDate' => 'ASC'] );
    }

    // TODO custom request to fetch events by locality
    /**
     * custom request to fetch events by locality
     *
     * @param string $locality
     * @return void
     */
    public function findByLocality($locality)
    {
        return $this->createQueryBuilder('e')
            ->join('e.user', 'e_u')
            ->andWhere('e_u.zip = :val')
            ->setParameter(':val', $locality)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findEventsByLocality($filters, $locality)
    {
        return $this->createQueryBuilder('e')
            ->join('e.category', 'e_c')
            ->join('e.user', 'e_u')
            ->where('e_u.zip = :val')
            ->setParameter(':val', $locality)
            ->andWhere('e_c.name IN (:vals)')
            ->setParameter(':vals', array_values($filters))
            ->orderBy('e.startDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    // TODO end of custom request



    /**
     * Sql custom request for user favorite.
     * We check if the current event is already 
     * in the favorites of the current user.
     *
     * @param integer $eventId
     * @param integer $userId
     * @return [] 
     */
    public function findIfCurrentEventIsAlreadyInUserFavorite(int $eventId , int $userId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
                SELECT* FROM user_event 
                WHERE user_id = $userId 
                AND event_id = $eventId
            ";
            $results = $conn->executeQuery($sql);
            
            return $results->fetchAllAssociative();
    }


}
