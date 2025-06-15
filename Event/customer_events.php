<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Cancel registration
if (isset($_GET['cancel_id'])) {
    $cancel_id = (int) $_GET['cancel_id'];
    $stmt = $conn->prepare("SELECT event_id FROM registrations WHERE id = ? AND user_id = ?");
    $stmt->execute([$cancel_id, $user_id]);
    $reg = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reg) {
        $conn->prepare("DELETE FROM registrations WHERE id = ?")->execute([$cancel_id]);
        $conn->prepare("UPDATE events SET seats_available = seats_available + 1 WHERE id = ?")->execute([$reg['event_id']]);
        $_SESSION['msg'] = "✅ Event registration canceled.";
        header("Location: customer_events.php");
        exit();
    }
}

// Fetch registered events
$stmt = $conn->prepare("
    SELECT r.id AS reg_id, e.title, e.date, e.time, e.location
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    WHERE r.user_id = ?
    ORDER BY e.date ASC
");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Registered Events - SeminarZone</title>
  <link rel="stylesheet" href="assets/css/stylecustomerevent.css">
</head>
<body>
<div class="wrapper"> <!-- ✅ Start wrapper -->

<header>
  <h1 class="animated-title">My <span class="loop-text">Registered Events</span></h1>
  <p>View and manage your seminar registrations here.</p>
</header>

<!-- NAVIGATION BAR -->
<nav>
  <ul>
    <li><a href="Index.php">Home</a></li>
    <li><a href="Register_customer_events.php">Register New Events</a></li>
    <li><a href="customer_events.php">My Events</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>


<div class="main-grid">
  <section>
    <?php if (isset($_SESSION['msg'])): ?>
      <div class="alert <?= strpos($_SESSION['msg'], '✅') !== false ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($_SESSION['msg']) ?>
      </div>
      <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <?php if (!empty($events)): ?>
    <div class="event-wrapper">
    <?php foreach ($events as $event): ?>
        <div class="event-card">
        <div class="event-icon">📅</div>
        <div class="event-details">
            <h3><?= htmlspecialchars($event['title']) ?></h3>
            <p><strong>Date:</strong> <?= date("d M Y", strtotime($event['date'])) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($event['time']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
            <a class="cancel-btn" href="customer_events.php?cancel_id=<?= $event['reg_id'] ?>" onclick="return confirm('Cancel this registration?');">Cancel Registration</a>
        </div>
        </div>
    <?php endforeach; ?>
    </div>
    <div class="register-cta">
        <a href="Register_customer_events.php" class="register-btn">Register New Event</a>
    </div>

    <?php else: ?>
      <div class="no-events">
  <p>You have not registered for any events yet.</p>
  <a class="register-btn" href="Register_customer_events.php">Register for an Event</a>
</div>

    <?php endif; ?>
  </section>
</div>

<footer>
  &copy; <?= date("Y") ?> SeminarZone. All rights reserved.
</footer>
</div> <!-- ✅ End wrapper -->
</body>
</html>
