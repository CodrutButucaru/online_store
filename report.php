<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';

// Check if the user is an admin; if not, display an error and exit
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Access denied. This page is only accessible to administrators.</p>";
    include 'includes/footer.php';
    exit();
}

// Query to select categories that have more than 3 products
$categoryQuery = "SELECT categories.id FROM categories
                  JOIN product_categories ON categories.id = product_categories.category_id
                  GROUP BY categories.id
                  HAVING COUNT(product_categories.product_id) > 3";
$categoryResult = $conn->query($categoryQuery);

$categoryIds = [];

// Loop through categories and store their IDs
while ($row = $categoryResult->fetch_assoc()) {
    $categoryIds[] = $row['id'];
}

// If no categories have more than 3 products, show a message and exit
if (empty($categoryIds)) {
    echo "<p>There are no categories with more than 3 products.</p>";
    include 'includes/footer.php';
    exit();
}

$categoryIdsString = implode(',', $categoryIds); // Convert array of category IDs to a comma-separated string

// Updated query to fetch orders where products are < 100 lei (before or after discount) and belong to valid categories
$query = "SELECT orders.id AS order_id, 
                 orders.order_date, 
                 users.name, 
                 users.email, 
                 (SELECT SUM(order_items.quantity) 
                  FROM order_items 
                  WHERE order_items.order_id = orders.id) AS total_products,
                 SUM(CASE
                     -- Discount logic for categories 1 and 3
                     WHEN product_categories.category_id IN (1, 3) THEN 
                         CASE 
                             WHEN (products.price - 100) < 100 THEN products.price - 100
                             ELSE products.price 
                         END
                     -- Discount logic for quantity > 5
                     WHEN order_items.quantity > 5 THEN 
                         CASE 
                             WHEN (products.price * 0.80) < 100 THEN products.price * 0.80
                             ELSE products.price
                         END
                     -- Discount logic for quantity > 3
                     WHEN order_items.quantity > 3 THEN 
                         CASE 
                             WHEN (products.price * 0.90) < 100 THEN products.price * 0.90
                             ELSE products.price
                         END
                     ELSE products.price
                 END * order_items.quantity) AS total_discounted_price
          FROM orders
          JOIN users ON orders.user_id = users.id
          JOIN order_items ON orders.id = order_items.order_id
          JOIN products ON order_items.product_id = products.id
          JOIN product_categories ON products.id = product_categories.product_id
          WHERE product_categories.category_id IN ($categoryIdsString)
            AND (
                -- Products that either cost < 100 before or after discounts
                products.price < 100
                OR 
                -- Products that after applying discounts meet the condition of costing less than 100
                (product_categories.category_id IN (1, 3) AND (products.price - 100) < 100)
                OR (order_items.quantity > 5 AND (products.price * 0.80) < 100)
                OR (order_items.quantity > 3 AND (products.price * 0.90) < 100)
            )
          GROUP BY orders.id
          ORDER BY total_products DESC";

$result = $conn->query($query); // Execute the query

echo "<h2>Order Report</h2>";

// If no orders match the criteria, show a message
if ($result->num_rows === 0) {
    echo "<p>No orders meet the specified criteria.</p>";
} else {
    // Display the orders in a table format
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Total Products</th>
                <th>Total Discounted Price</th>
            </tr>";

    // Loop through each order and display it in the table
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['order_date']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['total_products']}</td>
                <td>" . number_format($row['total_discounted_price'], 2) . " lei</td>
              </tr>";
    }

    echo "</table>";
}

include 'includes/footer.php';
?>
