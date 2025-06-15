<?php
require_once 'includes/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long!";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match!";
    }
    else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $message = "Email is already registered.";
        } else {
            // Encrypt password using md5 (for this project it's okay)
            $hashedPassword = md5($password);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashedPassword])) {
                $message = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $message = "Something went wrong. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Account - SeminarZone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<div class="login-container">
    <h2>CREATE AN ACCOUNT</h2>

    <form method="POST">
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <label for="name">Full Name :</label>
        <input type="text" name="name" id="name" placeholder="Full Name" required>

        <label for="email">Email Address :</label>
        <input type="email" name="email" id="email" placeholder="Email Address" required>

        <label for="password">Password :</label>
        <div class="password-wrapper">
        <input type="password" name="password" id="password" placeholder="Password" minlength="6" required>
        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
        </div>

        <label for="confirm">Confirm Password :</label>
        <div class="password-wrapper">
        <input type="password" name="confirm" id="confirm" placeholder="Confirm Password" minlength="6" required>
        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm', this)"></i>
        </div>

        <button type="submit">REGISTER</button>
            
        <div class="links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </form>
</div>

<!-- ✅ Link your external JS file -->
<script src="assets/js/script.js"></script>

</body>
</html>
