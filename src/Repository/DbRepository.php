<?php

namespace App\Repository;

use App\Entity\Db;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Db|null find($id, $lockMode = null, $lockVersion = null)
 * @method Db|null findOneBy(array $criteria, array $orderBy = null)
 * @method Db[]    findAll()
 * @method Db[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DbRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Db::class);
    }

}
