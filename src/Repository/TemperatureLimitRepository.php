<?php

namespace App\Repository;

use App\Entity\SensorLimit;
use App\Entity\TemperatureLimit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TemperatureLimitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TemperatureLimit::class);
    }
}
