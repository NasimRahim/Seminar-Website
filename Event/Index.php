<?php
session_start();
$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SeminarZone - Home</title>
  <link rel="stylesheet" href="assets/css/stylemain.css">
</head>
<body>

<!-- HEADER -->
<header>
    <h1 class="animated-title">
    Welcome to <span class="loop-text">SeminarZone</span>
  </h1>
  <p> Your one-stop platform for seminar & event registration</p>
</header>

<!-- NAVIGATION BAR -->
<nav>
  <ul>
    <li><a href="#">Home</a></li>
    <li><a href="#about-us">About Us</a></li>
    <li><a href="#what-we-provide">Services</a></li>
    <li><a href="#contact-us">Contact Us</a></li>

    <?php if ($role === 'admin'): ?>
      <li><a href="admin/add_event.php">Add Event</a></li>
      <li><a href="admin/view_register.php">View Registrations</a></li>
      <li><a href="#" onclick="openModal()">Add Admin</a></li>
      <li><a href="logout.php">Logout</a></li>
    <?php elseif ($role === 'customer'): ?>
      <li><a href="Register_customer_events.php">Book Events</a></li>
      <li><a href="customer_events.php">My Events</a></li>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php">Register</a></li>
    <?php endif; ?>
  </ul>
</nav>


<!-- MAIN GRID -->
<div class="main-grid">
<section id="about-us">
  <h2>About Us</h2>        
    <p>
    SeminarZone is a user-friendly platform built to manage seminar and event registration with ease. It serves both organizers and participants efficiently.
    </P>
  <div class="team-container">
    <!-- Person 1 -->
    <div class="team-member">
      <img src="assets/images/mother.jpg" alt="Dr. Norazura">
      <h3>Dr. Norazura Binti Jamaludin</h3>
      <p><strong>Role:</strong> Principal</p>
      <p><strong>Age:</strong> 45</p>
      <p>Passionate about innovation in education and event systems.</p>
    </div>
    <!-- Person 2 -->
    <div class="team-member">
      <img src="assets/images/female.png" alt="Dr. Norazura">
      <h3>Dr. Norazura Binti Jamaludin</h3>
      <p><strong>Role:</strong> Principal</p>
      <p><strong>Age:</strong> 45</p>
      <p>Passionate about innovation in education and event systems.</p>
    </div>
        <!-- Person 3 -->
    <div class="team-member">
      <img src="assets/images/man.png" alt="Dr. Norazura">
      <h3>Dr. Norazura Binti Jamaludin</h3>
      <p><strong>Role:</strong> Principal</p>
      <p><strong>Age:</strong> 45</p>
      <p>Passionate about innovation in education and event systems.</p>
    </div>
    <!-- Person 4 -->
    <div class="team-member">
      <img src="assets/images/goblin.png" alt="Mr. Hafiz">
      <h3>Mr. Hafiz Bin Ismail</h3>
      <p><strong>Role:</strong> Event Coordinator</p>
      <p><strong>Age:</strong> 38</p>
      <p>Focused on seamless event delivery and customer satisfaction.</p>
    </div>
  </div>
</section>


<aside id="what-we-provide">
  <h2>What We Provide</h2>

  <div class="features">
    <!-- Always visible features -->
    <a class="feature-box">
      <h3>📬 Notifications</h3>
      <p>Receive timely email updates for every booking and change.</p>
    </a>

    <a class="feature-box">
      <h3>📝 Registration System</h3>
      <p>Simplified online registration for seminars and events.</p>
    </a>

    <!-- Admin-only features -->
    <?php if ($role === 'admin'): ?>
      <a class="feature-box" href="admin/add_event.php">
        <h3>➕ Add Event</h3>
        <p>Create and manage new seminars or workshops.</p>
      </a>

      <a class="feature-box" href="admin/view_register.php">
        <h3>📋 View Registrations</h3>
        <p>Monitor and manage participant registrations.</p>
      </a>

    <!-- Customer-only features -->
    <?php elseif ($role === 'customer'): ?>
      <a class="feature-box" href="Register_customer_events.php">
        <h3>🎯 Book New Events</h3>
        <p>Discover and register for upcoming seminars.</p>
      </a>

      <a class="feature-box" href="customer_events.php">
        <h3>🧾 My Events</h3>
        <p>Track and manage your registered events.</p>
      </a>

    <!-- Guest/Not Logged In -->
    <?php else: ?>
      <a class="feature-box" href="login.php">
        <h3>🔐 Login</h3>
        <p>Login to unlock event registration features.</p>
      </a>
    <?php endif; ?>
  </div>
</aside>



  <article id="contact-us">
    <h2>Contact Us</h2>
    <p>
      📧 support@seminarzone.com <br>
      ☎️ +6012-3456789 <br>
      📍 UTeM, Durian Tunggal, Melaka
    </p>
  </article>
</div>

<!-- FOOTER -->
<footer>
  &copy; <?= date("Y") ?> SeminarZone. All rights reserved.
</footer>


<!-- Add Admin Modal -->
<div id="adminModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Create an Admin Account</h2>
    <form method="POST" action="admin/register_admin.php">
      <label for="name">Full Name:</label>
      <input type="text" name="name" required>

      <label for="email">Email Address:</label>
      <input type="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required minlength="6">

    <label for="confirm">Confirm Password:</label>
    <input type="password" name="confirm" required minlength="6">


      <button type="submit">Add Admin</button>
    </form>
  </div>
</div>


<!-- Script for pop up -->
<script>
  function openModal() {
    document.getElementById('adminModal').style.display = 'flex';
  }

  function closeModal() {
    document.getElementById('adminModal').style.display = 'none';

    // ✅ Reset form inputs inside the modal
    const form = document.querySelector('#adminModal form');
    form.reset();
  }

  // Close modal when clicking outside of it
  window.onclick = function(event) {
    const modal = document.getElementById('adminModal');
    if (event.target === modal) {
      closeModal();
    }
  }
</script>

</body>
</html>
