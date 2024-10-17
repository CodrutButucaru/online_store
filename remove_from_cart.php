<?php
session_start();
include 'db_connect.php';

// Check if the product ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: cart.php"); // Redirect to the cart page if no product ID is provided
    exit(); // Stop further execution
}

$productId = intval($_GET['id']); // Convert the product ID to an integer for security
$sessionId = session_id(); // Get the current session ID to identify the user's cart

// Prepare the SQL query to delete the product from the cart for the current session
$stmt = $conn->prepare("DELETE FROM cart_items WHERE session_id = ? AND product_id = ?");
$stmt->bind_param("si", $sessionId, $productId); // Bind the session ID and product ID to the query
$stmt->execute(); // Execute the query

// Redirect back to the cart page after removing the item
header("Location: cart.php");
exit();
?>
