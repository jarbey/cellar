<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Cache(usage="READ_ONLY")
 * @ORM\Entity(repositoryClass="App\Repository\BottleRepository")
 */
class Bottle
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $capacity;

    /**
     * BottleSize constructor.
     * @param string $name
     * @param float $capacity
     */
    public function __construct($name, $capacity) {
        $this->name = $name;
        $this->capacity = $capacity;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return BottleSize
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
     * @return BottleSize
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getCapacity() {
        return $this->capacity;
    }

    /**
     * @param float $capacity
     * @return BottleSize
     */
    public function setCapacity($capacity) {
        $this->capacity = $capacity;
        return $this;
    }
}
