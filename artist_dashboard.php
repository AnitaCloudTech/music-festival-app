<?php
session_start();
require 'dbconn.php';
include 'artist_nav.php';


if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'artist') {
    header("Location: login.php");
    exit;
}

if(isset($_SESSION['artist_id'])) {
    $artist_id = $_SESSION['artist_id'];
} else {
    $user_id = $_SESSION['user_id'];
    $artist = $conn->query("SELECT id FROM artists WHERE user_id = $user_id")->fetch_assoc();
    $artist_id = $artist['id'] ?? 0;
}


// Узимамо статистику
$rating = $conn->query("SELECT ROUND(AVG(rating),1) AS avg_rating FROM ratings WHERE artist_id=$artist_id")->fetch_assoc()['avg_rating'] ?? 0;
$comments = $conn->query("SELECT COUNT(*) AS total FROM comments WHERE artist_id=$artist_id")->fetch_assoc()['total'] ?? 0;
$favorites = $conn->query("SELECT COUNT(*) AS total FROM favorites WHERE artist_id=$artist_id")->fetch_assoc()['total'] ?? 0;
$performances = $conn->query("SELECT stage, performance_time FROM program WHERE artist_id=$artist_id ORDER BY performance_time")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Moj profil - Statistika</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>📊 Statistika za vaš profil</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h4>⭐ Prosečna ocena</h4>
            <p class="display-6"><?php echo $rating ?: 'Nema ocena'; ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h4>💬 Broj komentara</h4>
            <p class="display-6"><?php echo $comments; ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h4>❤️ U omiljenima</h4>
            <p class="display-6"><?php echo $favorites; ?></p>
        </div>
    </div>
</div>

<hr>
<h4>🎵 Vaši nastupi</h4>
<?php if(!empty($performances)): ?>
<table class="table table-striped">
    <thead><tr><th>Scena</th><th>Vreme</th></tr></thead>
    <tbody>
    <?php foreach($performances as $p): ?>
        <tr>
            <td><?php echo htmlspecialchars($p['stage']); ?></td>
            <td><?php echo htmlspecialchars($p['performance_time']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p class="text-muted">Nema zakazanih nastupa.</p>
<?php endif; ?>

<p class="mt-3"><a href="artist_edit.php" class="btn btn-secondary">✏️ Uredi profil</a></p>
<p><a href="logout.php">Odjavi se</a></p>

</body>
</html>
<style>
.card {
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
</style>
<style>
.card {
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
</style>
