<?php
session_start();
require 'dbconn.php';
include 'alerts.php';

if (isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Dozvoli login i sa emailom
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Kreiraj sesiju
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] === 'artist') {
                $_SESSION['artist_id'] = $user['id']; // samo ako je izvođač
            }

            $_SESSION['message'] = "Uspešno ste prijavljeni!";

            // ✅ Preusmeri u zavisnosti od uloge
            switch ($user['role']) {
                case 'artist':
                    header("Location: artist_dashboard.php");
                    break;
                case 'organizer':
                    header("Location: organizer_dashboard.php");
                    break;
                default:
                    header("Location: index.php"); // posetilac
            }
            exit;
        } else {
            $_SESSION['error'] = "Pogrešna lozinka!";
        }
    } else {
        $_SESSION['error'] = "Korisnik ne postoji!";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prijava</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body class="container mt-4">

<h2>Prijava</h2>
<?php include 'alerts.php'; ?>

<form method="post">
    <div class="mb-3">
        <label>Korisničko ime ili email:</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Lozinka:</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary">Prijavi se</button>
</form>

<p>Nemate nalog? <a href="register.php">Registrujte se</a></p>

</body>
</html>
