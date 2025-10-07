<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Music Fest</title>

<!-- Bootstrap i tvoji stilovi -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">

<!-- Bootstrap JS i tvoj JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</head>
<body>

<!-- Navigacija -->
<nav class="navbar navbar-expand-lg" style="background: linear-gradient(90deg, #6a0dad, #ff6f00);">
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="index.php">🎵 Music Fest</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item"><a class="nav-link text-white" href="program.php">Program</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="favorites.php">Omiljeni izvođači</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="notifications.php">Obaveštenja</a></li>

        <?php if(isset($_SESSION['username'])): ?>
          <li class="nav-item">
            <span class="nav-link text-warning fw-bold">Prijavljeni kao: <?= htmlspecialchars($_SESSION['username']); ?></span>
          </li>

          <?php if($_SESSION['role'] == 'admin'): ?>
            <li class="nav-item"><a class="nav-link text-white" href="admin/index.php">Admin Panel</a></li>
          <?php endif; ?>

          <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="login.php">Prijava</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
