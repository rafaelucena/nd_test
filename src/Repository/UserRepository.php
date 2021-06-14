<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     *
     * @return void
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $data
     *
     * @return User|null
     */
    public function findOneByData(string $data): ?User
    {
        $queryBuilder = $this->createQueryBuilder('u')
                             ->join(Item::class, 'i', 'i.user_id = u.id')
                             ->andWhere('i.data LIKE :data')
                             ->setParameter('data', '%' . $data . '%');

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
