<?php

namespace App\Repository;

use App\Entity\SensorLimit;
use App\Entity\TemperatureLimit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TemperatureLimit|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemperatureLimit|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemperatureLimit[]    findAll()
 * @method TemperatureLimit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemperatureLimitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TemperatureLimit::class);
    }
}
