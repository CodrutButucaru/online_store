<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
require_once 'classes/Product.php';
?>

<h2>Shopping Cart</h2>

<?php
$sessionId = session_id(); // Get the current session ID

// Initialize variables for totals
$old_total = 0; // Total before discounts
$new_total = 0; // Total after discounts
$total_discount = 0; // Total discount applied

// Query to get products from the cart
$stmt = $conn->prepare("SELECT cart_items.product_id, cart_items.quantity, products.title, products.price, products.is_taxable, product_categories.category_id
                        FROM cart_items
                        JOIN products ON cart_items.product_id = products.id
                        JOIN product_categories ON products.id = product_categories.product_id
                        WHERE cart_items.session_id = ?");
$stmt->bind_param("s", $sessionId);
$stmt->execute();
$result = $stmt->get_result();

// If the cart is empty, display a message
if ($result->num_rows === 0) {
    echo "<p>Your cart is empty.</p>";

    // Check if the user is trying to apply a coupon while the cart is empty
    if (isset($_POST['apply_coupon']) && !empty($_POST['coupon_code'])) {
        echo "<p style='color:red;'>You cannot apply a coupon to an empty cart. Please add products first.</p>";
    }

} else {
    // Check if a coupon has been applied
    $coupon_applied = false;
    $coupon_code = '';

    // Only apply the coupon if the user has entered it
    if (isset($_POST['apply_coupon']) && !empty($_POST['coupon_code'])) {
        if ($_POST['coupon_code'] === 'PROMO7352') {
            $coupon_applied = true;
            $coupon_code = 'PROMO7352';
            echo "<p>Coupon 'PROMO7352' applied successfully!</p>";
            // Save coupon in session for later use
            $_SESSION['coupon_applied'] = true;
            $_SESSION['coupon_code'] = $coupon_code;
        } else {
            echo "<p style='color:red;'>Invalid coupon code.</p>";
            // Reset the session variables for coupon
            unset($_SESSION['coupon_applied']);
            unset($_SESSION['coupon_code']);
        }
    }

    // Display table headers for the cart items
    echo "<table border='1'>
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Original Price</th>
                <th>Discounted Price</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>";

    $i = 1; // Counter for item numbers

    // Loop through each product in the cart
    while ($row = $result->fetch_assoc()) {
        $product = new Product($row['product_id'], $row['title'], '', $row['price'], $row['is_taxable']);
        $quantity = $row['quantity'];
        $category_id = $row['category_id'];

        // Calculate the price with or without tax
        $original_price = $product->isTaxable() ? $product->getPriceWithTax() : $product->getPrice();
        $discounted_price = $original_price; // Default, no discount

        // Apply discount if the coupon is applied
        if ($coupon_applied) {
            // Apply category-based discount (100 lei off for categories 1 or 3)
            if (in_array($category_id, [1, 3])) {
                $discounted_price = max(0, $original_price - 100);
            } elseif ($quantity > 5) {
                // 20% discount for quantities > 5
                $discounted_price = $original_price * 0.8;
            } elseif ($quantity > 3) {
                // 10% discount for quantities > 3
                $discounted_price = $original_price * 0.9;
            }
        }

        // Calculate totals for this product
        $total_before_discount = $original_price * $quantity;
        $total_after_discount = $discounted_price * $quantity;

        $old_total += $total_before_discount;
        $new_total += $total_after_discount;
        $total_discount += $total_before_discount - $total_after_discount;

        // Display each product in the table
        echo "<tr>
                <td>{$i}</td>
                <td>{$product->getTitle()}</td>
                <td>{$quantity}</td>
                <td>{$original_price} lei</td>
                <td>{$discounted_price} lei</td>
                <td>" . number_format($total_after_discount, 2) . " lei</td>
                <td><a href='remove_from_cart.php?id={$product->getId()}'>Remove</a></td>
              </tr>";
        $i++; // Increment item number
    }

    // Save the totals in session for later use on checkout page
    $_SESSION['old_total'] = $old_total;
    $_SESSION['new_total'] = $new_total;
    $_SESSION['total_discount'] = $total_discount;

    // Display the totals
    echo "<tr>
            <td colspan='4' style='text-align: right;'><strong>Total Before Discount:</strong></td>
            <td colspan='2'><strong>" . number_format($old_total, 2) . " lei</strong></td>
          </tr>";
    echo "<tr>
            <td colspan='4' style='text-align: right;'><strong>Total After Discount:</strong></td>
            <td colspan='2'><strong>" . number_format($new_total, 2) . " lei</strong></td>
          </tr>";
    echo "<tr>
            <td colspan='4' style='text-align: right;'><strong>Total Discount:</strong></td>
            <td colspan='2'><strong>" . number_format($total_discount, 2) . " lei</strong></td>
          </tr>";
    echo "</table>"; // End of table

    // Link to the checkout page
    echo "<br><a href='checkout.php'>Proceed to Checkout</a>";
}
?>

<!-- Form for applying the coupon -->
<form action="cart.php" method="post">
    <label for="coupon">Apply Coupon:</label>
    <input type="text" name="coupon_code" id="coupon">
    <button type="submit" name="apply_coupon">Apply</button>
</form>

<?php
include 'includes/footer.php';
?>
