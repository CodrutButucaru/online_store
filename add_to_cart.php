<?php
session_start();
include 'db_connect.php';
require_once 'classes/Product.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ensure product_id and quantity are present
    if (!isset($_POST['product_id'], $_POST['quantity'])) {
        header("Location: index.php"); // Redirect if required data is missing
        exit();
    }

    // Sanitize and prepare product_id and quantity
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $sessionId = session_id(); // Get the session ID to track the cart

    // Check if the product exists in the database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the product does not exist, redirect to home page
    if ($result->num_rows === 0) {
        header("Location: index.php");
        exit();
    }

    // Create a Product object with the fetched data
    $row = $result->fetch_assoc();
    $product = new Product($row['id'], $row['title'], $row['description'], $row['price'], $row['is_taxable']);

    // Check if the product is already in the user's cart
    $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param("si", $sessionId, $productId);
    $stmt->execute();
    $cartResult = $stmt->get_result();

    // Update the cart if the product already exists, otherwise insert a new entry
    if ($cartResult->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param("isi", $quantity, $sessionId, $productId);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO cart_items (session_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $sessionId, $productId, $quantity);
        $stmt->execute();
    }

    // Redirect to the cart page after adding/updating the item
    header("Location: cart.php");
    exit();
} else {
    // Redirect if the request is not a POST request
    header("Location: index.php");
    exit();
}
?>
