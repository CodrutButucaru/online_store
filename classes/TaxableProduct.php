<?php
require_once 'Product.php';

class TaxableProduct extends Product {
    private $taxRate;

    public function __construct($id, $title, $description, $price, $isTaxable = true, $taxRate = 0.19) {
        parent::__construct($id, $title, $description, $price, $isTaxable);
        $this->taxRate = $taxRate;
    }

    public function getPriceWithTax() {
        return $this->price * (1 + $this->taxRate);
    }
}
?>
