<?php
session_start();
require '../dbconn.php';
include '../alerts.php';

// Samo admin ima pristup
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "⛔ Nemate pristup ovoj stranici.";
    exit;
}

// Dodaj kolonu 'is_blocked' u tabelu users ako ne postoji
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_blocked TINYINT(1) DEFAULT 0");

// Brisanje korisnika
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id");
    $_SESSION['message'] = "Korisnik uspešno obrisan!";
    header("Location: manage_users.php");
    exit;
}

// Blokiranje / deblokiranje korisnika
if(isset($_GET['block'])){
    $id = intval($_GET['block']);
    $conn->query("UPDATE users SET is_blocked = 1 WHERE id = $id");
    $_SESSION['message'] = "Korisnik je blokiran.";
    header("Location: manage_users.php");
    exit;
}

if(isset($_GET['unblock'])){
    $id = intval($_GET['unblock']);
    $conn->query("UPDATE users SET is_blocked = 0 WHERE id = $id");
    $_SESSION['message'] = "Korisnik je deblokiran.";
    header("Location: manage_users.php");
    exit;
}

// Uzimanje svih korisnika
$result = $conn->query("SELECT id, username, email, role, is_blocked FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Upravljanje korisnicima</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>👥 Upravljanje korisnicima</h2>
<?php include '../alerts.php'; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Korisničko ime</th>
            <th>Email</th>
            <th>Uloga</th>
            <th>Status</th>
            <th>Akcije</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <?php if($row['is_blocked']): ?>
                    <span class="badge bg-danger">Blokiran</span>
                <?php else: ?>
                    <span class="badge bg-success">Aktivan</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if($row['is_blocked']): ?>
                    <a href="?unblock=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Deblokiraj</a>
                <?php else: ?>
                    <a href="?block=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Blokiraj</a>
                <?php endif; ?>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Da li ste sigurni da želite da obrišete korisnika?');">Obriši</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<p><a href="index.php" class="btn btn-secondary">⬅ Nazad na Dashboard</a></p>

</body>
</html>
