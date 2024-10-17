<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <ul>
        <li><a href="index.php">Acasă</a></li>
        <li><a href="cart.php">Coș</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php">Logout</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="report.php">Raport</a></li>
            <?php endif; ?>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Înregistrare</a></li>
        <?php endif; ?>
    </ul>
</nav>
