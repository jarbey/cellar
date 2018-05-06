<?php

namespace App\Repository;

use App\Entity\Bottle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bottle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bottle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bottle[]    findAll()
 * @method Bottle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BottleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bottle::class);
    }

    /**
     * @param string $name
     * @return Bottle|null
     */
    public function getBottleByName($name) {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param float $capacity
     * @return Bottle|null
     */
    public function getBottleByCapacity($capacity) {
        return $this->findOneBy(['capacity' => $capacity]);
    }
}
