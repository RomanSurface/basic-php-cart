<?php

/**  coding: utf-8 **/

declare(strict_types=1);

namespace AcmeWidget;

class Product
{
    /**
     * @var array
     */
    private $Offers;

    /**
     * @var array
     */
    private $BasePrices;

    /**
     * @var array
     */
    private $Delivery;

    public function __construct(array $productConfig = [])
    {
        $this->BasePrices = $productConfig['prices'];
        $this->Offers = $productConfig['offers'];
        $this->Delivery = $productConfig['delivery'];
    }

    /**
     * @param string $productId
     * @return bool
     */
    public function getProductOffer(string $productId): bool
    {
        return array_key_exists($productId, $this->Offers);
    }

    /**
     * @param string $itemId
     * @return float
     */
    public function getBasePrices(string $itemId): float
    {
        return $this->BasePrices[$itemId];
    }

    /**
     * @param string $itemId
     * @param int $quantity
     * @return array
     */
    public function calculateProductOffer(string $itemId, int $quantity): array
    {
        $offerPrice = [];
        $itemBasePrice = $this->BasePrices[$itemId];
        if ($quantity === 1) {
            $offerPrice['price'] = round($itemBasePrice * $quantity, 2);
            $offerPrice['save'] = 0.00;
        }
        if ($quantity % 2 === 0) {
            $offerPrice['price'] = round($itemBasePrice * $quantity * doubleval($this->Offers[$itemId]), 2);
            $offerPrice['save'] = round($itemBasePrice * $quantity * doubleval(1 - $this->Offers[$itemId]), 2);
        } else {
            $discountedPortion = $itemBasePrice * ($quantity - 1) * doubleval($this->Offers[$itemId]);
            $totalDiscountedPrice = round($discountedPortion + $itemBasePrice, 2);
            $offerPrice['price'] =  $totalDiscountedPrice;
            $offerPrice['save'] = round($itemBasePrice * $quantity - $totalDiscountedPrice, 2);
        }

        return $offerPrice;
    }

    /**
     * @param float $total
     * @return float
     */
    public function calculateOrderDelivery(float $total): float
    {
        $price = 0.00;
        foreach ($this->Delivery as $range => $rangePrice) {
            if ($total <= $range) {
                $price = $rangePrice;
                break;
            }
        }

        return $price;
    }
}

