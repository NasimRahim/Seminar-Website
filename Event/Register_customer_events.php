<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = (int)$_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    // Check if already registered
    $check = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check->execute([$user_id, $event_id]);

    if ($check->rowCount() > 0) {
        $status = 'error';
        $message = '⚠️ You are already registered for this event.';
    } else {
        // Check event availability
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$event_id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event && $event['seats_available'] > 0) {
            $register = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
            $update = $conn->prepare("UPDATE events SET seats_available = seats_available - 1 WHERE id = ?");

            if ($register->execute([$user_id, $event_id]) && $update->execute([$event_id])) {
                $status = 'success';
                $message = '🎉 Successfully registered for <strong>' . htmlspecialchars($event['title']) . '</strong>!';
                echo "<script>setTimeout(() => window.location.href = 'customer_events.php', 2500);</script>";
            } else {
                $status = 'error';
                $message = 'Something went wrong. Please try again.';
            }
        } else {
            $status = 'error';
            $message = 'Sorry, no seats available.';
        }
    }
}

// Fetch available events
$stmt = $conn->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register for Events - SeminarZone</title>
  <link rel="stylesheet" href="assets\css\styleregistercustomerevent.css">
</head>
<body>
<div class="wrapper">

<header>
  <h1 class="animated-title">Available <span class="loop-text">Seminar Events</span></h1>
  <p>Browse and register for upcoming events.</p>
</header>

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
    <?php if (!empty($message)): ?>
      <div class="alert <?= $status === 'success' ? 'alert-success' : 'alert-error' ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($events)): ?>
    <div class="event-wrapper">
      <?php foreach ($events as $event): ?>
        <div class="event-card">
          <div class="event-icon">📢</div>
          <div class="event-details">
            <h3><?= htmlspecialchars($event['title']) ?></h3>
            <p><strong>Date:</strong> <?= date("d M Y", strtotime($event['date'])) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($event['time']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
            <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
            <p><strong>Seats Available:</strong> <?= $event['seats_available'] ?></p>

            <form method="POST" onsubmit="return confirm('Register for this event?')">
              <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
              <button type="submit" class="register-btn">Register Seminar</button>
            </form>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <div class="no-events">
        <p>No events available at the moment.</p>
      </div>
    <?php endif; ?>
  </section>
</div>

<footer>
  &copy; <?= date("Y") ?> SeminarZone. All rights reserved.
</footer>
</div>
</body>
</html>
