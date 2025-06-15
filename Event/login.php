<?php
require_once 'includes/db.php';
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Fetch user from DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($password) === $user['password']) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        header("Location: Index.php");
        exit();
    } else {
        $message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SeminarZone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="login-container">
    <h2>LOGIN TO YOUR ACCOUNT</h2>

    <form method="POST">
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <label for="email">Email Address :</label>
        <input type="email" id="email" name="email" placeholder="Email Address" required>

        <label for="password">Password :</label>
        <div class="password-wrapper">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
        </div>

        <button type="submit">LOGIN</button>

        <div class="links">
            <p>Dont have an account? <a href="register.php">Sign Up now</a></p>
        </div>
    </form>
</div>


<!-- ✅ Link your external JS file -->
<script src="assets/js/script.js"></script>

</body>
</html>
