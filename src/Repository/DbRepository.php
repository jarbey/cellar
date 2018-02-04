<?php

namespace App\Repository;

use App\Entity\Db;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DbRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Db::class);
    }

}
