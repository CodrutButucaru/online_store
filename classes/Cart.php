<?php
require_once 'Product.php';

class Cart {
    private $items = [];

    public function addProduct(Product $product, $quantity = 1) {
        $productId = $product->getId();
        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $this->items[$productId] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
    }

    public function removeProduct($productId) {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $price = $item['product']->isTaxable() ? $item['product']->getPriceWithTax() : $item['product']->getPrice();
            $total += $price * $item['quantity'];
        }
        return $total;
    }

    public function clearCart() {
        $this->items = [];
    }
}
?>
