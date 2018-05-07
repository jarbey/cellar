<?php

namespace App\Repository;

use App\Entity\WineBottle;
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

    /**
     * @param WineBottle $bottle
     * @param \DateTime $date_buy
     * @param $price_buy
     * @param $quantity_current
     * @param $quantity_buy
     * @param $comment_buy
     * @param $location_buy
     * @param $location_type_buy
     * @return WineStock
     */
    public function create(WineBottle $bottle, \DateTime $date_buy, $price_buy, $quantity_current, $quantity_buy,  $comment_buy, $location_buy, $location_type_buy) {
        $wine_stock = new WineStock($bottle, $date_buy, $price_buy, $quantity_current, $quantity_buy, $comment_buy, $location_buy, $location_type_buy);
        $this->_em->persist($wine_stock);
        $this->_em->flush($wine_stock);

        return $wine_stock;
    }

}
