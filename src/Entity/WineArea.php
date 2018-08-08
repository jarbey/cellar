<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Cache(usage="READ_ONLY")
 * @ORM\Entity(repositoryClass="App\Repository\WineAreaRepository")
 */
class WineArea
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
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $area_name;

    /**
     * @var Collection|WineBottle[]
     * @ORM\OneToMany(targetEntity="App\Entity\WineBottle", mappedBy="area", orphanRemoval=true)
     */
    private $bottles;

    public function __construct($country, $region, $area_name)
    {
        $this->bottles = new ArrayCollection();
        $this->country = $country;
        $this->region = $region;
        $this->area_name = $area_name;
    }

    /**
     * @return string
     */
    public function getArea() {
        return $this->getAreaName() . ' (' . $this->region . ' - ' . $this->country . ')';
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return WineArea
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $country
     * @return WineArea
     */
    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * @param string $region
     * @return WineArea
     */
    public function setRegion($region) {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getAreaName() {
        return $this->area_name;
    }

    /**
     * @param string $area_name
     * @return WineArea
     */
    public function setAreaName($area_name) {
        $this->area_name = $area_name;
        return $this;
    }

    /**
     * @return Collection|WineBottle[]
     */
    public function getBottles()
    {
        return $this->bottles;
    }

    /**
     * @param WineBottle $bottle
     * @return $this
     */
    public function addBottle(WineBottle $bottle)
    {
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
    public function removeBottle(WineBottle $bottle)
    {
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
