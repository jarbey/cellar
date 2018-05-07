<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Cache(usage="READ_ONLY")
 * @ORM\Entity(repositoryClass="App\Repository\DbRepository")
 */
class Db
{
    /**
	 * @var integer
	 *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $name;

	/**
	 * @var Sensor[]
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\Sensor", mappedBy="db", cascade={"persist"})
	 */
	private $sensors;

	/**
	 * Db constructor.
	 * @param string $name
	 * @param Sensor[] $sensors
	 */
	public function __construct($name, array $sensors) {
		$this->name = $name;
		$this->sensors = new ArrayCollection();
		foreach ($sensors as $sensor) {
			$this->addSensor($sensor);
		}
	}


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Db
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Db
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Sensor[]
	 */
	public function getSensors() {
		return $this->sensors;
	}

	/**
	 * @param Sensor $sensor
	 * @return Db
	 */
	public function addSensor($sensor) {
		$sensor->setDb($this);
		$this->sensors->add($sensor);

		return $this;
	}

}
