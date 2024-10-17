<?php
class Product {
    protected $id;
    protected $title;
    protected $description;
    protected $price;
    protected $isTaxable;

    public function __construct($id, $title, $description, $price, $isTaxable) {
        $this->id = $id;
        $this->title = htmlspecialchars($title);
        $this->description = htmlspecialchars($description);
        $this->price = $price;
        $this->isTaxable = $isTaxable;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function isTaxable() {
        return $this->isTaxable;
    }

    public function getPriceWithTax() {
        if ($this->isTaxable()) {
            return $this->price * 1.19; // TVA de 19%
        }
        return $this->price;
    }
}
?>
