<?php

namespace App\Repository;

use App\Entity\WineStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WineStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method WineStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method WineStock[]    findAll()
 * @method WineStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineStockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WineStock::class);
    }
}
