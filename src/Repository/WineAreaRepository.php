<?php

namespace App\Repository;

use App\Entity\WineArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WineArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method WineArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method WineArea[]    findAll()
 * @method WineArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WineArea::class);
    }

    /**
     * @param $country
     * @param $region
     * @param $area
     * @return WineArea|null
     */
    public function getWineArea($country, $region, $area) {
        return $this->findOneBy(['country' => $country, 'region' => $region, 'area_name' => $area]);
    }

    /**
     * @param $country
     * @param $region
     * @param $area
     * @return WineArea
     * @throws ORMException
     */
    public function create($country, $region, $area) {
        $wine_area = new WineArea($country, $region, $area);
        $this->_em->persist($wine_area);
        $this->_em->flush($wine_area);

        return $wine_area;
    }

    /**
     * @return mixed
     */
    public function truncate() {
        $query = $this->createQueryBuilder('t')->delete()->getQuery()->execute();
        return $query;
    }
}
