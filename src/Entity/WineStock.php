<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WineStockRepository")
 */
class WineStock
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var WineBottle
     * @ORM\ManyToOne(targetEntity="App\Entity\WineBottle", inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bottle;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $price_buy;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $date_buy;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $quantity_current;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $quantity_buy;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location_buy;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location_type_buy;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment_buy;

    /**
     * WineStock constructor.
     * @param WineBottle $bottle
     * @param \DateTimeInterface $date_buy
     * @param float $price_buy
     * @param int $quantity_current
     * @param int $quantity_buy
     * @param string $location_buy
     * @param string $location_type_buy
     * @param string $comment_buy
     */
    public function __construct(WineBottle $bottle, \DateTimeInterface $date_buy, $price_buy, $quantity_current, $quantity_buy, $location_buy, $location_type_buy, $comment_buy) {
        $this->bottle = $bottle;
        $this->date_buy = $date_buy;
        $this->price_buy = $price_buy;
        $this->quantity_current = $quantity_current;
        $this->quantity_buy = $quantity_buy;
        $this->location_buy = $location_buy;
        $this->location_type_buy = $location_type_buy;
        $this->comment_buy = $comment_buy;
    }


    /**
     * @return string
     */
    public function getQuantity() {
        return $this->getQuantityCurrent() . ' / ' . $this->getQuantityBuy();
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return WineBottle
     */
    public function getBottle() {
        return $this->bottle;
    }

    /**
     * @param WineBottle $bottle
     * @return WineStock
     */
    public function setBottle($bottle) {
        $this->bottle = $bottle;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceBuy() {
        return $this->price_buy;
    }

    /**
     * @param float $price_buy
     * @return WineStock
     */
    public function setPriceBuy($price_buy) {
        $this->price_buy = $price_buy;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateBuy() {
        return $this->date_buy;
    }

    /**
     * @param \DateTimeInterface $date_buy
     * @return WineStock
     */
    public function setDateBuy($date_buy) {
        $this->date_buy = $date_buy;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityCurrent() {
        return $this->quantity_current;
    }

    /**
     * @param int $quantity_current
     * @return WineStock
     */
    public function setQuantityCurrent($quantity_current) {
        $this->quantity_current = $quantity_current;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityBuy() {
        return $this->quantity_buy;
    }

    /**
     * @param int $quantity_buy
     * @return WineStock
     */
    public function setQuantityBuy($quantity_buy) {
        $this->quantity_buy = $quantity_buy;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocationBuy() {
        return $this->location_buy;
    }

    /**
     * @param string $location_buy
     * @return WineStock
     */
    public function setLocationBuy($location_buy) {
        $this->location_buy = $location_buy;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocationTypeBuy() {
        return $this->location_type_buy;
    }

    /**
     * @param string $location_type_buy
     * @return WineStock
     */
    public function setLocationTypeBuy($location_type_buy) {
        $this->location_type_buy = $location_type_buy;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentBuy() {
        return $this->comment_buy;
    }

    /**
     * @param string $comment_buy
     * @return WineStock
     */
    public function setCommentBuy($comment_buy) {
        $this->comment_buy = $comment_buy;
        return $this;
    }

}
