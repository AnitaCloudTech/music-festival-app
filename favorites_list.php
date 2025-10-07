<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Dohvati omiljene izvođače
$sql = "SELECT a.id, a.name, a.genre, a.image 
        FROM artists a
        JOIN favorites f ON a.id = f.artist_id
        WHERE f.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Moji omiljeni izvođači</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2 class="mb-4">🎧 Moji omiljeni izvođači</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← Nazad na program</a>

    <?php if($result->num_rows > 0): ?>
        <div class="row">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <img src="images/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text text-muted">Žanr: <?php echo htmlspecialchars($row['genre']); ?></p>
                            <a href="artist.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Detalji</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Još uvek nemaš omiljenih izvođača.</div>
    <?php endif; ?>
</div>
</body>
</html>
