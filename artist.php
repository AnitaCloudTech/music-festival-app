<?php
session_start();
require 'dbconn.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$artist_id = intval($_GET['id']);
$sql = "SELECT * FROM artists WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    echo "Izvođač nije pronađen.";
    exit;
}

$artist = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($artist['name']); ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<h2><?php echo htmlspecialchars($artist['name']); ?></h2>
<p>Žanr: <?php echo htmlspecialchars($artist['genre']); ?></p>
<img src="images/<?php echo htmlspecialchars($artist['image']); ?>" alt="<?php echo htmlspecialchars($artist['name']); ?>" width="200"><br><br>
<p><?php echo htmlspecialchars($artist['bio']); ?></p>

<?php
$avg = $conn->query("SELECT ROUND(AVG(rating),1) AS avg_rating FROM ratings WHERE artist_id=$artist_id")->fetch_assoc();
$avg_rating = $avg['avg_rating'] ?: 'Nema ocena';
?>
<h4>Prosečna ocena: <span id="avg-rating"><?php echo $avg_rating; ?></span></h4>

<?php if(isset($_SESSION['user_id'])): ?>
<form id="rateForm" method="post">
    <input type="hidden" name="artist_id" value="<?php echo $artist['id']; ?>">
    <label>Ocenite nastup:</label>
    <select name="rating" id="rating" class="form-select" style="width:150px;display:inline-block;">
        <option value="">-- Izaberite --</option>
        <?php for($i=1;$i<=5;$i++): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?> ⭐</option>
        <?php endfor; ?>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Pošalji</button>
</form>
<p id="rateMsg"></p>
<?php else: ?>
<p class="text-muted">Prijavite se da biste ocenili izvođača.</p>
<?php endif; ?>

<?php if(isset($_SESSION['role']) && $_SESSION['role']=='visitor'): ?>
<form method="post" action="favorite.php">
    <input type="hidden" name="artist_id" value="<?php echo $artist['id']; ?>">
    <button type="submit" name="favorite" class="btn btn-outline-danger">❤️ Dodaj u omiljene</button>
</form>
<?php else: ?>
<p class="text-muted">Prijavite se da biste dodali u omiljene.</p>
<?php endif; ?>

<hr>
<h4>Komentari</h4>

<?php
// Prikaži postojeće komentare
$comments = $conn->prepare("SELECT c.comment, c.created_at, u.username 
                            FROM comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.artist_id = ? 
                            ORDER BY c.created_at DESC");
$comments->bind_param("i", $artist_id);
$comments->execute();
$res = $comments->get_result();

if($res->num_rows > 0):
    while($row = $res->fetch_assoc()):
?>
    <div class="border p-2 mb-2 rounded">
        <strong><?php echo htmlspecialchars($row['username']); ?>:</strong><br>
        <?php echo htmlspecialchars($row['comment']); ?><br>
        <small class="text-muted"><?php echo $row['created_at']; ?></small>
    </div>
<?php endwhile; else: ?>
    <p class="text-muted">Još uvek nema komentara.</p>
<?php endif; ?>

<?php if(isset($_SESSION['user_id'])): ?>
<form method="post" action="comments.php" class="mt-3">
    <input type="hidden" name="artist_id" value="<?php echo $artist['id']; ?>">
    <textarea name="comment" class="form-control mb-2" placeholder="Ostavite komentar..." required></textarea>
    <button type="submit" class="btn btn-primary btn-sm">Pošalji</button>
</form>
<?php else: ?>
<p class="text-muted">Prijavite se da biste ostavili komentar.</p>
<?php endif; ?>

<p class="mt-3"><a href="index.php" class="btn btn-secondary">Nazad na program</a></p>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
