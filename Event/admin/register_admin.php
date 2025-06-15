<?php
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    // 1. Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // ✅ Minimum password length check
    if (strlen($password) < 6) {
        die("Password must be at least 6 characters.");
    }

    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    // 2. Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        die("Email is already registered.");
    }

    // 3. Encrypt password
    $hashedPassword = md5($password); // okay for school project

    // 4. Insert user as admin
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    if ($stmt->execute([$name, $email, $hashedPassword])) {
        echo "<script>
            alert('Admin added successfully.');
            window.location.href = '../Index.php';
        </script>";
    } else {
        echo "Failed to add admin. Try again.";
    }
}
?>
