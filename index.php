<?php
session_start();
require '../dbconn.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "⛔ Nemate pristup ovoj stranici.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🎛️ Admin Panel | MyFestival</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}
.navbar {
    background: linear-gradient(90deg, #3b0a45, #ff6f00);
}
.navbar-brand {
    font-weight: bold;
    color: #fff !important;
}
.card {
    border: none;
    border-radius: 15px;
    transition: 0.3s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
.card a {
    text-decoration: none;
    color: inherit;
}
.card-title {
    font-weight: 600;
    color: #333;
}
footer {
    margin-top: 40px;
    text-align: center;
    color: #777;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">🎛️ MyFestival Admin</a>
    <div class="d-flex">
      <span class="text-white me-3">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
      <a href="../logout.php" class="btn btn-sm btn-warning">🚪 Odjava</a>
    </div>
  </div>
</nav>

<div class="container">
  <h2 class="mb-4 text-center">Dobrodošli, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card text-center p-4">
        <a href="add_festival.php">
          <h5 class="card-title">🎉 Dodaj festival</h5>
          <p class="text-muted">Kreiraj novi festival i definiši osnovne detalje.</p>
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-4">
        <a href="add_artist.php">
          <h5 class="card-title">🎤 Dodaj izvođača</h5>
          <p class="text-muted">Dodaj novog izvođača sa slikom, žanrom i biografijom.</p>
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-4">
        <a href="manage_program.php">
          <h5 class="card-title">📅 Uredi program</h5>
          <p class="text-muted">Organizuj raspored nastupa i scene festivala.</p>
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-4">
        <a href="manage_comments.php">
          <h5 class="card-title">💬 Pregled komentara</h5>
          <p class="text-muted">Pogledaj i briši neprimerene komentare posetilaca.</p>
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-4">
        <a href="manage_users.php">
          <h5 class="card-title">👥 Pregled korisnika</h5>
          <p class="text-muted">Upravljaj korisnicima i blokiraj naloge ako je potrebno.</p>
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center p-4">
        <a href="add_poll.php">
          <h5 class="card-title">🗳️ Ankete</h5>
          <p class="text-muted">Kreiraj ankete i prikupljaj povratne informacije.</p>
        </a>
      </div>
    </div>
  </div>

  <footer class="mt-5">
    <p>© 2025 MyFestival Admin Panel</p>
  </footer>
</div>

<li><a href="add_poll.php" class="list-group-item list-group-item-action">🗳️ Kreiraj anketu</a></li>
<li><a href="stats.php" class="list-group-item list-group-item-action">📊 Statistika</a></li>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
