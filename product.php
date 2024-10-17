<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

// Check if the product ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "<p>Product not specified.</p>"; // Display error if product ID is missing
    include 'includes/footer.php';
    exit(); // Exit script if no product ID is provided
}

$productId = intval($_GET['id']); // Convert the product ID to an integer

// Query to fetch product details by product ID
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $productId); // Bind product ID to query
$stmt->execute();
$result = $stmt->get_result();

// If no product is found, display an error
if ($result->num_rows === 0) {
    echo "<p>Product not found.</p>";
    include 'includes/footer.php';
    exit(); // Exit script if no product is found
}

$row = $result->fetch_assoc();
$product = new Product($row['id'], $row['title'], $row['description'], $row['price'], $row['is_taxable']); // Create a Product object

// Query to fetch categories for the product
$categoryStmt = $conn->prepare("SELECT categories.name FROM categories 
                                JOIN product_categories ON categories.id = product_categories.category_id 
                                WHERE product_categories.product_id = ?");
$categoryStmt->bind_param("i", $productId); // Bind product ID to query
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();

$categories = []; // Initialize an array to store category names
while ($catRow = $categoryResult->fetch_assoc()) {
    $categories[] = htmlspecialchars($catRow['name']); // Escape and store each category name
}

// Calculate the price with tax if applicable
$priceWithTax = $product->isTaxable() ? number_format($product->getPriceWithTax(), 2) : number_format($product->getPrice(), 2);
?>

<!-- Display product details -->
<h1><?php echo $product->getTitle(); ?></h1>
<p><?php echo $product->getDescription(); ?></p>
<p>Categories: <?php echo implode(', ', $categories); ?></p> <!-- Display categories as a comma-separated list -->
<p>Price: <?php echo $priceWithTax; ?> lei</p>

<!-- Form to add the product to the cart -->
<form action="add_to_cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>"> <!-- Pass the product ID as hidden input -->
    Quantity: <input type="number" name="quantity" value="1" min="1"> <!-- Quantity input -->
    <button type="submit">Add to Cart</button> <!-- Submit button to add to cart -->
</form>

<?php
include 'includes/footer.php';
?>
