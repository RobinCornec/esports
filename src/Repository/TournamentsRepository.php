<?php

namespace App\Repository;

use App\Entity\Tournaments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tournaments>
 *
 * @method Tournaments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournaments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournaments[]    findAll()
 * @method Tournaments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournaments::class);
    }

    public function add(Tournaments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tournaments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
