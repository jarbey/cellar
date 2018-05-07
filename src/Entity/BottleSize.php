<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Cache(usage="READ_ONLY")
 * @ORM\Entity(repositoryClass="App\Repository\BottleSizeRepository")
 */
class BottleSize
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
     * @var Collection|WineBottle[]
     * @ORM\OneToMany(targetEntity="App\Entity\WineBottle", mappedBy="area", orphanRemoval=true)
     */
    private $bottles;

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

    /**
     * @return Collection|WineBottle[]
     */
    public function getBottles() {
        return $this->bottles;
    }

    /**
     * @param WineBottle $bottle
     * @return $this
     */
    public function addBottle(WineBottle $bottle) {
        if (!$this->bottles->contains($bottle)) {
            $this->bottles[] = $bottle;
            $bottle->setArea($this);
        }

        return $this;
    }

    /**
     * @param WineBottle $bottle
     * @return $this
     */
    public function removeBottle(WineBottle $bottle) {
        if ($this->bottles->contains($bottle)) {
            $this->bottles->removeElement($bottle);
            // set the owning side to null (unless already changed)
            if ($bottle->getArea() === $this) {
                $bottle->setArea(null);
            }
        }

        return $this;
    }
}
