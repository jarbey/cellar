<?php

namespace App\Repository;

use App\Entity\BottleSize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BottleSize|null find($id, $lockMode = null, $lockVersion = null)
 * @method BottleSize|null findOneBy(array $criteria, array $orderBy = null)
 * @method BottleSize[]    findAll()
 * @method BottleSize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BottleSizeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BottleSize::class);
    }

    /**
     * @param string $name
     * @return BottleSize|null
     */
    public function getBottleSizeByName($name) {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param float $capacity
     * @return BottleSize|null
     */
    public function getBottleSizeByCapacity($capacity) {
        return $this->findOneBy(['capacity' => $capacity]);
    }
}
