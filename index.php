<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
?>

<h1>Welcome to Our Online Store!</h1>
<p>Here you will find a variety of quality products at competitive prices.</p>

<h2>Products</h2>
<div class="products">
    <?php
    // Fetch all products from the database
    $productQuery = "SELECT * FROM products";
    $productResult = $conn->query($productQuery);

    // Loop through the products and display them
    while ($row = $productResult->fetch_assoc()) {
        $product = new Product($row['id'], $row['title'], $row['description'], $row['price'], $row['is_taxable']); // Create Product object
        // Calculate price with or without tax
        $priceWithTax = $product->isTaxable() ? number_format($product->getPriceWithTax(), 2) : number_format($product->getPrice(), 2);

        // Fetch the categories for the current product
        $categoryStmt = $conn->prepare("SELECT categories.name FROM categories 
                                        JOIN product_categories ON categories.id = product_categories.category_id 
                                        WHERE product_categories.product_id = ?");
        $categoryStmt->bind_param("i", $row['id']);
        $categoryStmt->execute();
        $categoryResult = $categoryStmt->get_result();

        $categories = [];
        while ($catRow = $categoryResult->fetch_assoc()) {
            $categories[] = htmlspecialchars($catRow['name']);
        }
        $categoriesString = implode(', ', $categories); // Concatenate categories

        // Display product details: title, price (with or without tax), categories, and "View Details" button
        echo "<div class='product'>
                <h3>{$product->getTitle()}</h3>
                <p>Price: {$priceWithTax} lei</p>
                <!--<p>Categories: {$categoriesString}</p>-->
                <a href='product.php?id={$product->getId()}'>View Details</a>
              </div>";
    }
    ?>
</div>

<?php
include 'includes/footer.php';
?>
