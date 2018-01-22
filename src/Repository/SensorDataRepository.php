<?php

namespace App\Repository;

use App\Entity\SensorData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SensorDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SensorData::class);
    }

	/**
	 * @param SensorData[] $data
	 */
    public function save($data = []) {
    	/** @var SensorData $sensor_data */
		foreach ($data as $sensor_data) {
    		$this->getEntityManager()->persist($sensor_data);
		}

		$this->getEntityManager()->flush();
	}

	/**
	 * @param SensorData[] $data
	 */
	public function remove($data = []) {
		/** @var SensorData $sensor_data */
		foreach ($data as $sensor_data) {
			$this->getEntityManager()->remove($sensor_data);
		}

		$this->getEntityManager()->flush();
	}

}
