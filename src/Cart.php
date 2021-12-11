<?php

/**  coding: utf-8 **/

declare(strict_types=1);

namespace AcmeWidget;

class Cart
{
    /**
     * @var array
     */
    private $Cart;

    /**
     * @param object;
     */
    private $Session;

    /**
     * @param object;
     */
    private $Product;

    public function __construct(Session $session, Product $product)
    {
        $this->Session = $session;
        $this->Product = $product;
        $this->readSessionCart();
    }

    private function readSessionCart()
    {
        ($this->Session->read('cart') !== null) ? ($this->Cart = $this->Session->read('cart')) : ($this->Cart = []) ;
    }

    private function writeSessionCart()
    {
        $this->Session->write('cart', $this->Cart);
    }

    private function getCart(): array
    {
        return $this->Cart;
    }

    /**
     * @param string $productId
     * @return string
     */
    public function addItem(string $productId): string
    {
        if (array_key_exists($productId, $this->Cart)) {
            $this->Cart[$productId]['quantity'] = $this->Cart[$productId]['quantity'] + 1;
        } else {
            $this->Cart[$productId]['quantity'] = 1;
        }

        $this->writeSessionCart();
        return $this->getCartTotals();
    }

    /**
     * @param string $productId
     * @return string
     */
    public function subtractItem(string $productId): string
    {
        if (array_key_exists($productId, $this->Cart)) {
            if ($this->Cart[$productId]['quantity'] > 1) {
                $this->Cart[$productId]['quantity'] = $this->Cart[$productId]['quantity'] - 1;
            } elseif ($this->Cart[$productId]['quantity'] == 1) {
                unset($this->Cart[$productId]);
            }
        }

        $this->writeSessionCart();
        return $this->getCartTotals();
    }

    /**
     * @return string
     */
    public function getCartTotals(): string
    {
        $cartTotals = [];
        $cartTotals['total'] = 0;
        $items = $this->getCart();

        foreach ($items as $itemId => $itemData) {
            $itemBasePrice = $this->Product->getBasePrices($itemId);
            if ($this->getProductOffer($itemId) !== true) {
                $itemTotal = round($itemBasePrice * intval($itemData['quantity']), 2);
                $hasOffer = 'no';
                $save = 0.00;
            } else {
                $itemOffer = $this->calculateProductOffer($itemId, intval($itemData['quantity']));
                $itemTotal = $itemOffer['price'];
                $save = $itemOffer['save'];
                ($itemData['quantity'] > 1) ? ($hasOffer = 'yes') : ($hasOffer = 'no');
            }
            $cartTotals['items'][$itemId]['quantity'] = $itemData['quantity'];
            $cartTotals['items'][$itemId]['total'] = $itemTotal;
            $cartTotals['items'][$itemId]['offer'] = $hasOffer;
            $cartTotals['items'][$itemId]['save'] = $save;
            $cartTotals['total'] = round($cartTotals['total'] + $itemTotal, 2);
        }

        $cartTotals['delivery'] = $this->calculateOrderDelivery($cartTotals['total']);
        $cartTotals['totalCart'] = round($cartTotals['delivery'] + $cartTotals['total'], 2) ;

        return json_encode($cartTotals);
    }

    /**
     * @param string $productId
     * @return bool
     */
    private function getProductOffer(string $productId): bool
    {
        return $this->Product->getProductOffer($productId);
    }

    /**
     * @param string $itemId
     * @param int $quantity
     * @return array
     */
    private function calculateProductOffer(string $itemId, int $quantity): array
    {
        return $this->Product->calculateProductOffer($itemId, $quantity);
    }

    /**
     * @param float $total
     * @return float
     */
    private function calculateOrderDelivery(float $total): float
    {
        return $this->Product->calculateOrderDelivery($total);
    }

    /**
     * @return string
     */
    public function emptyCart(): string
    {
        $this->Cart = [];
        $this->writeSessionCart();
        return $this->getCartTotals();
    }
}
