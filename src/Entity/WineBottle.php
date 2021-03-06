<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Cache(usage="READ_ONLY")
 * @ORM\Entity(repositoryClass="App\Repository\WineBottleRepository")
 */
class WineBottle
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
     * @var WineArea
     * @ORM\ManyToOne(targetEntity="App\Entity\WineArea", inversedBy="bottles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $area;

    /**
     * @var WineColor
     * @ORM\ManyToOne(targetEntity="App\Entity\WineColor", inversedBy="bottles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $color;

    /**
     * @var BottleSize
     * @ORM\ManyToOne(targetEntity="App\Entity\BottleSize", inversedBy="bottles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bottle_size;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vintage;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $drinkability_start_year;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $drinkability_end_year;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $drinkability_optimum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WineStock", mappedBy="bottle")
     */
    private $stocks;

    /**
     * WineBottle constructor.
     * @param string $name
     * @param WineArea $area
     * @param WineColor $color
     * @param BottleSize $bottle_size
     * @param int $vintage
     * @param int $drinkability_start_year
     * @param int $drinkability_end_year
     * @param int $drinkability_optimum
     */
    public function __construct($name, WineArea $area, WineColor $color, BottleSize $bottle_size, $vintage, $drinkability_start_year, $drinkability_end_year, $drinkability_optimum) {
        $this->stocks = new ArrayCollection();
        $this->name = $name;
        $this->area = $area;
        $this->color = $color;
        $this->bottle_size = $bottle_size;
        $this->vintage = $vintage;
        $this->drinkability_start_year = $drinkability_start_year;
        $this->drinkability_end_year = $drinkability_end_year;
        $this->drinkability_optimum = $drinkability_optimum;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return WineBottle
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
     * @return WineBottle
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return WineArea
     */
    public function getArea() {
        return $this->area;
    }

    /**
     * @param WineArea $area
     * @return WineBottle
     */
    public function setArea($area) {
        $this->area = $area;
        return $this;
    }

    /**
     * @return WineColor
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param WineColor $color
     * @return $this
     */
    public function setColor(WineColor $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return BottleSize
     */
    public function getBottleSize() {
        return $this->bottle_size;
    }

    /**
     * @param BottleSize $bottle_size
     * @return WineBottle
     */
    public function setBottleSize($bottle_size) {
        $this->bottle_size = $bottle_size;
        return $this;
    }

    /**
     * @return int
     */
    public function getVintage() {
        return $this->vintage;
    }

    /**
     * @param int $vintage
     * @return WineBottle
     */
    public function setVintage($vintage) {
        $this->vintage = $vintage;
        return $this;
    }

    /**
     * @return int
     */
    public function getDrinkabilityStartYear() {
        return $this->drinkability_start_year;
    }

    /**
     * @param int $drinkability_start_year
     * @return WineBottle
     */
    public function setDrinkabilityStartYear($drinkability_start_year) {
        $this->drinkability_start_year = $drinkability_start_year;
        return $this;
    }

    /**
     * @return int
     */
    public function getDrinkabilityEndYear() {
        return $this->drinkability_end_year;
    }

    /**
     * @param int $drinkability_end_year
     * @return WineBottle
     */
    public function setDrinkabilityEndYear($drinkability_end_year) {
        $this->drinkability_end_year = $drinkability_end_year;
        return $this;
    }

    /**
     * @return int
     */
    public function getDrinkabilityOptimum() {
        return $this->drinkability_optimum;
    }

    /**
     * @param int $drinkability_optimum
     * @return WineBottle
     */
    public function setDrinkabilityOptimum($drinkability_optimum) {
        $this->drinkability_optimum = $drinkability_optimum;
        return $this;
    }

    /**
     * @return Collection|WineStock[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(WineStock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setBottle($this);
        }

        return $this;
    }

    public function removeStock(WineStock $stock): self
    {
        if ($this->stocks->contains($stock)) {
            $this->stocks->removeElement($stock);
            // set the owning side to null (unless already changed)
            if ($stock->getBottle() === $this) {
                $stock->setBottle(null);
            }
        }

        return $this;
    }

}
