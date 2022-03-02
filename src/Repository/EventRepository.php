<?php

namespace App\Repository;

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
        // on crée la requête depuis le repository Event que l'on nomme ici 'e'
        return $this->createQueryBuilder('e')
            // on fait une jointure à partir de la propriété event.category que l'on nomme 'e_c'
            ->join('e.category', 'e_c')
            // on sélectionne les events dont la categorie correspond à un paramètre lié
            ->andWhere('e_c.name = :val')
            // on lie le paramètre à la valeur $category (fournit en paramètre de la requête)
            ->setParameter('val', $category)
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
            ->getQuery()
            ->getResult()
        ;
    }
}
