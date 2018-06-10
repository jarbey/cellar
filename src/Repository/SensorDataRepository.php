<?php

namespace App\Repository;

use App\Entity\SensorData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SensorData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SensorData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SensorData[]    findAll()
 * @method SensorData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensorDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SensorData::class);
    }

	/**
	 * @param array $data
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
    public function save($data = []) {
    	/** @var SensorData $sensor_data */
		foreach ($data as $sensor_data) {
    		$this->getEntityManager()->persist($sensor_data);
		}

		$this->getEntityManager()->flush();
	}

	/**
	 * @param array $data
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function remove($data = []) {
		/** @var SensorData $sensor_data */
		foreach ($data as $sensor_data) {
			$this->getEntityManager()->remove($sensor_data);
		}

		$this->getEntityManager()->flush();
	}

}
