<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

// Check if a category ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "<p>Category not specified.</p>"; // Display an error if no category ID is provided
    include 'includes/footer.php'; // Include the footer
    exit(); // Exit to stop further execution
}

$categoryId = intval($_GET['id']); // Convert the category ID to an integer

// Query to get the category name by ID
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $categoryId); // Bind the category ID to the query
$stmt->execute();
$result = $stmt->get_result();

// If no category is found, display an error
if ($result->num_rows === 0) {
    echo "<p>Category not found.</p>";
    include 'includes/footer.php'; // Include the footer
    exit(); // Stop execution if the category does not exist
}

$categoryData = $result->fetch_assoc();
$category = new Category($categoryId, $categoryData['name']); // Create a Category object using the retrieved data
?>

<h2>Products in Category: <?php echo $category->getName(); ?></h2> <!-- Display category name -->

<div class="products">
    <?php
    // Query to retrieve products in the specified category
    $stmt = $conn->prepare("SELECT products.* FROM products
                            JOIN product_categories ON products.id = product_categories.product_id
                            WHERE product_categories.category_id = ?");
    $stmt->bind_param("i", $categoryId); // Bind the category ID to filter the products
    $stmt->execute();
    $productsResult = $stmt->get_result();

    // If no products are found in this category, display a message
    if ($productsResult->num_rows === 0) {
        echo "<p>No products in this category.</p>";
    } else {
        // Loop through each product and display its details
        while ($row = $productsResult->fetch_assoc()) {
            $product = new Product($row['id'], $row['title'], $row['description'], $row['price'], $row['is_taxable']); // Create a Product object
            // Calculate the price with or without tax
            $priceWithTax = $product->isTaxable() ? number_format($product->getPriceWithTax(), 2) : number_format($product->getPrice(), 2);

            // Display each product with a title, price, and link to details
            echo "<div class='product'>
                    <h3>{$product->getTitle()}</h3>
                    <p>Price: {$priceWithTax} lei</p>
                    <a href='product.php?id={$product->getId()}'>View Details</a>
                  </div>";
        }
    }
    ?>
</div>

<?php
include 'includes/footer.php';
?>
