<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
require_once 'classes/User.php';

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if both email and password fields are filled
    if (!isset($_POST['email'], $_POST['password'])) {
        echo "<p style='color:red;'>Please fill in all fields.</p>"; // Display error if fields are missing
    } else {
        $email = $_POST['email']; // Get the email from POST
        $password = $_POST['password']; // Get the password from POST

        // Query to check if the user exists with the given email
        $stmt = $conn->prepare("SELECT id, name, email, password, phone, address, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email); // Bind the email to the query
        $stmt->execute();
        $userResult = $stmt->get_result();

        // If a user with the email exists
        if ($userResult->num_rows === 1) {
            $userRow = $userResult->fetch_assoc(); // Fetch user data

            // Verify the password entered matches the hashed password in the database
            if (password_verify($password, $userRow['password'])) {
                // Set session variables for user ID and role
                $_SESSION['user_id'] = $userRow['id'];
                $_SESSION['role'] = $userRow['role'];

                // Redirect to homepage upon successful login
                header("Location: index.php");
                exit(); // Stop script execution
            } else {
                echo "<p style='color:red;'>Incorrect email or password.</p>"; // Display error if password is wrong
            }
        } else {
            echo "<p style='color:red;'>Incorrect email or password.</p>"; // Display error if no matching user found
        }
    }
}
?>

<h2>Login</h2>
<!-- Login form -->
<form action="login.php" method="post">
    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br> <!-- Email input field -->

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br> <!-- Password input field -->

    <button type="submit">Login</button> <!-- Submit button for login -->
</form>

<?php
include 'includes/footer.php';
?>
