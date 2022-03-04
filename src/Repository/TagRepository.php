<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Custom request DQL to display Events by Tags
     *
     * @param [object] $tag
     * @return void
     */
    public function findEventsDQL($tag)
    {
        // We get the object ID
        $tagId = $tag->getId();
        
        // We create a connection like PDO to make a SQL Query
        $conn = $this->getEntityManager()->getConnection();

        // This query display all events by "TagId" parameters
        $sql = "
            SELECT *
            FROM event
            INNER JOIN event_tag
            WHERE event.id = event_id and tag_id = '$tagId'
            ORDER BY event.start_date ASC
            ";

        // exécution de la requete
        $results = $conn->executeQuery($sql);

        // returns an array (i.e. a raw data set)
        return $results->fetchAssociative();
    }
    
    // /**
    //  * @return Tag[] Returns an array of Tag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
