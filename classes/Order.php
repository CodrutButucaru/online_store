<?php
class Order {
    private $id;
    private $userId;
    private $orderDate;
    private $items = [];

    public function __construct($id, $userId, $orderDate, $items = []) {
        $this->id = $id;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
        $this->items = $items;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getItems() {
        return $this->items;
    }

    public function addItem($productId, $quantity, $price) {
        $this->items[] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ];
    }
}
?>
