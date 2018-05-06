<?php

namespace App\Repository;

use App\Entity\HumidityLimit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HumidityLimit|null find($id, $lockMode = null, $lockVersion = null)
 * @method HumidityLimit|null findOneBy(array $criteria, array $orderBy = null)
 * @method HumidityLimit[]    findAll()
 * @method HumidityLimit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HumidityLimitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HumidityLimit::class);
    }
}
