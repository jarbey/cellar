<?php

namespace App\Repository;

use App\Entity\WineColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WineColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method WineColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method WineColor[]    findAll()
 * @method WineColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineColorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WineColor::class);
    }

    /**
     * @param string $name
     * @return WineColor|null
     */
    public function getWineColorByName($name) {
        return $this->findOneBy(['name' => strtolower($name)]);
    }

    /**
     * @param $name
     * @return WineColor
     * @throws ORMException
     */
    public function create($name) {
        $wine_color = new WineColor(strtolower($name));
        $this->_em->persist($wine_color);
        $this->_em->flush($wine_color);

        return $wine_color;
    }

    /**
     * @return mixed
     */
    public function truncate() {
        $query = $this->createQueryBuilder('t')->delete()->getQuery()->execute();
        return $query;
    }
}
