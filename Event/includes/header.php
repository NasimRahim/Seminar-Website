<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    .navbar {
        background: #007BFF;
        padding: 15px;
        text-align: center;
    }
    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-weight: bold;
    }
    .navbar a:hover {
        text-decoration: underline;
    }
</style>

<div class="navbar">
    <?php if (isset($_SESSION['user_role'])): ?>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="../index.php">Home</a>
            <a href="add_event.php">Add Event</a>
            <a href="view_register.php">View Registrations</a>
            <a href="../logout.php">Logout</a>
        <?php elseif ($_SESSION['user_role'] === 'customer'): ?>
            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <a href="my_register.php">My Events</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</div>
