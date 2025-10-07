<?php
session_start();
require '../dbconn.php';
include '../alerts.php';

// Dozvola samo adminima
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "⛔ Nemate pristup ovoj stranici.";
    exit;
}

// Najpopularniji izvođači po broju omiljenih
$popularArtists = $conn->query("
    SELECT a.name, COUNT(f.id) AS total_favorites
    FROM artists a
    LEFT JOIN favorites f ON a.id = f.artist_id
    GROUP BY a.id
    ORDER BY total_favorites DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Najposećeniji festivali po broju kupljenih karata
$topFestivals = $conn->query("
    SELECT f.name, SUM(t.quantity) AS total_tickets
    FROM festivals f
    LEFT JOIN tickets t ON f.id = t.festival_id
    GROUP BY f.id
    ORDER BY total_tickets DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Najkomentarisaniji izvođači
$topCommented = $conn->query("
    SELECT a.name, COUNT(c.id) AS total_comments
    FROM artists a
    LEFT JOIN comments c ON a.id = c.artist_id
    GROUP BY a.id
    ORDER BY total_comments DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Statistika - Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="mb-4">📊 Statistika sistema</h2>

<h4>🔥 Najpopularniji izvođači</h4>
<table class="table table-striped">
<thead><tr><th>Izvođač</th><th>Broj omiljenih</th></tr></thead>
<tbody>
<?php foreach($popularArtists as $a): ?>
<tr><td><?= htmlspecialchars($a['name']) ?></td><td><?= $a['total_favorites'] ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>

<h4>🎟️ Najposećeniji festivali</h4>
<table class="table table-striped">
<thead><tr><th>Festival</th><th>Broj prodatih ulaznica</th></tr></thead>
<tbody>
<?php foreach($topFestivals as $f): ?>
<tr><td><?= htmlspecialchars($f['name']) ?></td><td><?= $f['total_tickets'] ?: 0 ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>

<h4>💬 Najkomentarisaniji izvođači</h4>
<table class="table table-striped">
<thead><tr><th>Izvođač</th><th>Broj komentara</th></tr></thead>
<tbody>
<?php foreach($topCommented as $c): ?>
<tr><td><?= htmlspecialchars($c['name']) ?></td><td><?= $c['total_comments'] ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>

<p><a href="index.php" class="btn btn-secondary mt-3">⬅ Nazad na Admin panel</a></p>

</body>
</html>
