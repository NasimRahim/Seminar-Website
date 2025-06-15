<?php
require_once '../includes/db.php';
session_start();

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = trim($_POST['location']);
    $seats = (int)$_POST['seats_available'];

    if (empty($title) || empty($description) || empty($date) || empty($time) || empty($location)) {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, seats_available) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $date, $time, $location, $seats])) {
            $message = "✅ Event added successfully!";
        } else {
            $message = "❌ Something went wrong. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Event - Admin</title>
  <link rel="stylesheet" href="../assets/css/styleaddevent.css">
</head>
<body>

<!-- HEADER -->
<header>
    <h1 class="animated-title">
    Admin Panel - <span class="loop-text">Add Event</span>
    </h1>
  <p>Fill in the details below to schedule a new seminar.</p>
</header>

<!-- NAVIGATION (optional reuse from main layout) -->
<nav>
  <ul>
    <li><a href="../Index.php">Home</a></li>
    <li><a href="add_event.php">Add Event</a></li>
    <li><a href="view_register.php">View Registrations</a></li>
    <li><a href="../logout.php">Logout</a></li>
  </ul>
</nav>

<!-- MAIN CONTENT AREA -->
<div class="main-grid">
  <section>
    <h2>Add New Event</h2>

    <?php if ($message): ?>
    <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-error' ?>">
        <?= $message ?>
    </div>
    <?php endif; ?>


    <form method="POST">
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" placeholder="Event Title" required>

      <label for="description">Description:</label>
      <textarea name="description" id="description" placeholder="Event Description" rows="4" required></textarea>

      <label for="date">Date:</label>
      <input type="date" name="date" id="date" required>

      <label for="time">Time:</label>
      <input type="time" name="time" id="time" required>

      <label for="location">Location:</label>
      <input type="text" name="location" id="location" placeholder="Event Location" required>

      <label for="seats_available">Available Seats:</label>
      <input type="number" name="seats_available" id="seats_available" placeholder="Seats Available" required>

      <button type="submit">Add Event</button>
    </form>
  </section>
</div>

<!-- FOOTER -->
<footer>
  &copy; <?= date("Y") ?> SeminarZone. Admin access only.
</footer>

</body>
</html>
