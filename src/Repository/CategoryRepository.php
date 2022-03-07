<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findPremiumEventsInCurrentCategory($curentCat)
    {
        $conn = $this->getEntityManager()->getConnection();

        $curentCat = $curentCat->getSlug();

        $sql = "
            SELECT *
            FROM event
            INNER JOIN category            
            ON event.category_id = category.id
            WHERE category.slug = 'concert'
            AND event.is_premium = '1'
            ";
            $results = $conn->executeQuery($sql);
            
            return $results->fetchAllAssociative();
    }



    public function findCurrentCategoryPremiumEventsDQL($curentCat)
    {
        //get current category Id
        $cat = $curentCat->getId();
        //call entity manager
        $entityManager = $this->getEntityManager();
        //call query
        $query = $entityManager->createQuery(
        //create query on App\Entity\Event (for automatically build an Object)
        "SELECT e
            FROM App\Entity\Event e        
            WHERE e.isPremium = 1
            AND e.category = $cat
            ");
        
        // returns an array of objects with relations
        $resultats = $query->getResult();
        // dd($resultats);
        return $resultats;
    }


}
