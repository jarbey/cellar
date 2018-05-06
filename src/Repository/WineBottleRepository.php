<?php

namespace App\Repository;

use App\Entity\WineBottle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WineBottle|null find($id, $lockMode = null, $lockVersion = null)
 * @method WineBottle|null findOneBy(array $criteria, array $orderBy = null)
 * @method WineBottle[]    findAll()
 * @method WineBottle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineBottleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WineBottle::class);
    }
}
