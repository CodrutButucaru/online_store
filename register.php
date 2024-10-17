<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
require_once 'classes/User.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['name', 'email', 'password', 'phone', 'address']; // List of required fields
    $missing = []; // Array to track any missing fields

    // Loop through required fields and check if any are empty
    foreach ($requiredFields as $field) {
        if (empty(trim($_POST[$field]))) {
            $missing[] = $field; // Add missing fields to the array
        }
    }

    // If there are missing fields, show an error message
    if (!empty($missing)) {
        echo "<p style='color:red;'>Please fill in all fields: " . implode(', ', $missing) . ".</p>";
    } else {
        // Sanitize and assign form data to variables
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $role = 'client'; // Default role for new users

        // Check if the email is already in use
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $emailResult = $stmt->get_result();

        // If the email is already in use, show an error
        if ($emailResult->num_rows > 0) {
            echo "<p style='color:red;'>This email is already in use. Please use a different email.</p>";
        } else {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $phone, $address, $role);

            // If the user is successfully registered, show success message
            if ($stmt->execute()) {
                echo "<p>Registration successful! <a href='login.php'>Log in now</a>.</p>";
            } else {
                echo "<p style='color:red;'>There was an error with your registration. Please try again.</p>";
            }
        }
    }
}
?>

<h2>Register</h2>
<!-- Registration form -->
<form action="register.php" method="post">
    <label for="name">Name:</label><br>
    <input type="text" name="name" id="name" required><br><br> <!-- Name input field -->

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br> <!-- Email input field -->

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br> <!-- Password input field -->

    <label for="phone">Phone:</label><br>
    <input type="text" name="phone" id="phone" required><br><br> <!-- Phone input field -->

    <label for="address">Address:</label><br>
    <input type="text" name="address" id="address" required><br><br> <!-- Address input field -->

    <button type="submit">Register</button> <!-- Submit button -->
</form>

<?php
include 'includes/footer.php';
?>
