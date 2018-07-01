<?php

namespace App\Repository;

use App\Entity\BottleSize;
use App\Entity\WineArea;
use App\Entity\WineBottle;
use App\Entity\WineColor;
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


    /**
     * @param WineColor $wine_color
     * @return int
     */
    public function getNbWineBottleColor(WineColor $wine_color) {
        $qb = $this->createQueryBuilder('t');
        $qb->select('SUM(stocks.quantity_current) AS nb');
        $qb->join('t.stocks', 'stocks');
        $qb->where('t.color = :color');
        $qb->setParameter('color', $wine_color);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $name
     * @param WineArea $wine_area
     * @param WineColor $wine_color
     * @param BottleSize $bottle_size
     * @param $vintage
     * @return WineBottle|null
     */
    public function getWineBottle($name, WineArea $wine_area, WineColor $wine_color, BottleSize $bottle_size, $vintage) {
        return $this->findOneBy(['name' => $name, 'area' => $wine_area, 'color' => $wine_color, 'bottle_size' => $bottle_size, 'vintage' => $vintage]);
    }

    /**
     * @param $name
     * @param WineArea $area
     * @param WineColor $color
     * @param $vintage
     * @param $drinkability_start_year
     * @param $drinkability_end_year
     * @param $drinkability_optimum
     * @return WineBottle
     */
    public function create($name, WineArea $area, WineColor $color, BottleSize $bottle_size, $vintage, $drinkability_start_year, $drinkability_end_year, $drinkability_optimum) {
        $wine_bottle = new WineBottle($name, $area, $color, $bottle_size, $vintage, $drinkability_start_year, $drinkability_end_year, $drinkability_optimum);
        $this->_em->persist($wine_bottle);
        $this->_em->flush($wine_bottle);

        return $wine_bottle;
    }
}
