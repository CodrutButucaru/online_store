<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
require_once 'classes/Order.php';

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to place an order.</p>";
    echo "<a href='login.php'>Login</a> or <a href='register.php'>Register</a>";
    include 'includes/footer.php';
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user data from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc(); // Store user data for later display

$sessionId = session_id(); // Get the current session ID for the cart

// Retrieve cart items for the current session
$stmt = $conn->prepare("SELECT cart_items.product_id, cart_items.quantity, products.price, products.is_taxable, product_categories.category_id
                        FROM cart_items
                        JOIN products ON cart_items.product_id = products.id
                        JOIN product_categories ON products.id = product_categories.product_id
                        WHERE cart_items.session_id = ?");
$stmt->bind_param("s", $sessionId);
$stmt->execute();
$cartResult = $stmt->get_result();

$cartItems = []; // Initialize an empty array for cart items
$oldTotal = 0; // Original total before discounts
$newTotal = 0; // Final total after applying discounts

// Reset the coupon_applied session if it's not applicable anymore
if (!isset($_SESSION['coupon_applied']) || $_SESSION['coupon_applied'] !== true) {
    $_SESSION['coupon_applied'] = false;
}

// Check if a coupon is applied (use the same logic from cart.php)
$coupon_applied = $_SESSION['coupon_applied'] === true;

// Calculate the total for each cart item and add to the grand total
while ($row = $cartResult->fetch_assoc()) {
    $originalPrice = $row['is_taxable'] ? $row['price'] * 1.19 : $row['price']; // Price with or without tax
    $discountedPrice = $originalPrice; // Default discounted price is the same as original

    // Apply coupon-based discounts only if the coupon was applied
    if ($coupon_applied) {
        if (in_array($row['category_id'], [1, 3])) {
            // Apply 100 lei discount for categories 1 or 3
            $discountedPrice = max(0, $originalPrice - 100);
        } elseif ($row['quantity'] > 5) {
            // Apply 20% discount for quantities > 5
            $discountedPrice = $originalPrice * 0.8;
        } elseif ($row['quantity'] > 3) {
            // Apply 10% discount for quantities > 3
            $discountedPrice = $originalPrice * 0.9;
        }
    }

    $totalOriginal = $originalPrice * $row['quantity']; // Total before discount
    $totalDiscounted = $discountedPrice * $row['quantity']; // Total after discount

    $oldTotal += $totalOriginal;
    $newTotal += $totalDiscounted;

    // Store cart item details in an array for insertion into order_items table
    $cartItems[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity'],
        'original_price' => $originalPrice,
        'discounted_price' => $discountedPrice
    ];
}

$oldTotalFormatted = number_format($oldTotal, 2);
$newTotalFormatted = number_format($newTotal, 2);

// If the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save the order
    $orderDate = date('Y-m-d H:i:s'); // Get the current date and time

    // Insert a new order into the orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $orderDate);
    $stmt->execute();
    $orderId = $stmt->insert_id; // Get the last inserted order ID

    // Save each item in the order, including both original and discounted prices
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, original_price, discounted_price) VALUES (?, ?, ?, ?, ?)");
    foreach ($cartItems as $item) {
        $stmt->bind_param("iiidd", $orderId, $item['product_id'], $item['quantity'], $item['original_price'], $item['discounted_price']);
        $stmt->execute();
    }

    // Clear the user's cart after the order is placed
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE session_id = ?");
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();

    // Reset the coupon_applied session after the order is placed
    unset($_SESSION['coupon_applied']);
    unset($_SESSION['coupon_code']);

    // Display success message and link back to home
    echo "<p>Your order has been placed successfully!</p>";
    echo "<a href='index.php'>Back to Home</a>";

    // Redirect to index.php automatically after 1 second
    echo "
<script>
    setTimeout(function() {
        window.location.href = 'index.php'; 
    }, 1000); 
</script>
";

    include 'includes/footer.php';
    exit(); // Stop further execution after order placement
}
?>

<h2>Order Summary</h2>

<h3>Your Details</h3>
<!-- Display the logged-in user's details -->
<p><strong>Name:</strong> <?php echo htmlspecialchars($userData['name']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($userData['phone']); ?></p>
<p><strong>Address:</strong> <?php echo htmlspecialchars($userData['address']); ?></p>

<!-- Display the total order cost -->
<h3>Order Total Before Discount: <?php echo $oldTotalFormatted; ?> lei</h3>
<h3>Order Total After Discount: <?php echo $newTotalFormatted; ?> lei</h3>

<!-- Form to submit the order -->
<form action="checkout.php" method="post">
    <button type="submit">Submit Order</button>
</form>

<?php
include 'includes/footer.php';
?>
