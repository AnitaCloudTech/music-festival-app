<?php
session_start();
require '../dbconn.php';
include '../alerts.php';

// Samo admin može pristupiti
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "⛔ Nemate pristup ovoj stranici.";
    exit;
}

// Brisanje komentara
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM comments WHERE id = $id");
    $_SESSION['message'] = "Komentar uspešno obrisan!";
    header("Location: manage_comments.php");
    exit;
}

// Prikaz komentara
$result = $conn->query("
SELECT c.id, c.comment, c.created_at, u.username, a.name AS artist_name
FROM comments c
JOIN users u ON c.user_id = u.id
JOIN artists a ON c.artist_id = a.id
ORDER BY c.created_at DESC

");
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Upravljanje komentarima</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>💬 Upravljanje komentarima</h2>
<?php include '../alerts.php'; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Korisnik</th>
            <th>Izvođač</th>
            <th>Komentar</th>
            <th>Datum</th>
            <th>Akcija</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['artist_name']); ?></td>
            <td><?php echo htmlspecialchars($row['comment']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Da li ste sigurni da želite da obrišete ovaj komentar?');">Obriši</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<p><a href="index.php" class="btn btn-secondary">⬅ Nazad na Dashboard</a></p>

</body>
</html>
