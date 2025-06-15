<?php
require_once '../includes/db.php';
session_start();

// Only admin allowed
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$stmt = $conn->query("
    SELECT r.id, u.name AS user_name, u.email, e.title AS event_title, e.date, e.time
    FROM registrations r
    JOIN users u ON r.user_id = u.id
    JOIN events e ON r.event_id = e.id
    ORDER BY e.date ASC, e.title ASC
");

$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - View Registrations</title>
  <link rel="stylesheet" href="../assets/css/styleviewregister.css">
</head>
<body>
<div class="wrapper">

  <header>
    <h1 class="animated-title">View <span class="loop-text">All Registrations</span></h1>
    <p>Admin dashboard for monitoring seminar participation.</p>
  </header>

  <nav>
    <ul>
      <li><a href="../Index.php">Home</a></li>
      <li><a href="add_event.php">Add Event</a></li>
      <li><a href="view_register.php">View Registrations</a></li>
      <li><a href="../logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="main-grid">
    <section>
      <?php if (!empty($registrations)): ?>
      <table class="styled-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Participant</th>
            <th>Email</th>
            <th>Event</th>
            <th>Date</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($registrations as $index => $reg): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($reg['user_name']) ?></td>
            <td><?= htmlspecialchars($reg['email']) ?></td>
            <td><?= htmlspecialchars($reg['event_title']) ?></td>
            <td><?= date("d M Y", strtotime($reg['date'])) ?></td>
            <td><?= htmlspecialchars($reg['time']) ?></td>
          </tr>
          <?php endforeach; ?>  
        </tbody>
      </table>
      <?php else: ?>
        <div class="no-data">No registrations found.</div>
      <?php endif; ?>
    </section>
  </div>

  <footer>
    &copy; <?= date("Y") ?> SeminarZone. All rights reserved.
  </footer>

</div>
</body>
</html>
