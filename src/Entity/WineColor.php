<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Cache(usage="READ_ONLY")
 * @ORM\Entity(repositoryClass="App\Repository\WineColorRepository")
 */
class WineColor
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
    private $name;

    /**
     * @var Collection|WineBottle[]
     * @ORM\OneToMany(targetEntity="App\Entity\WineBottle", mappedBy="color")
     */
    private $bottles;

    public function __construct($name)
    {
        $this->bottles = new ArrayCollection();
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return WineColor
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
     * @return WineColor
     */
    public function setName($name) {
        $this->name = $name;
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
            $bottle->setColor($this);
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
            if ($bottle->getColor() === $this) {
                $bottle->setColor(null);
            }
        }

        return $this;
    }
}
