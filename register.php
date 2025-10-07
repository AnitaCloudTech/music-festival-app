<?php
session_start();
require 'dbconn.php';
include 'alerts.php';

if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role']; // ✅ dodato polje za ulogu
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Provera da li username ili email već postoji
    $check = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Username ili email već postoji!";
    } else {
        // ✅ ubacujemo ulogu u bazu
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password_hash, $role);
        $stmt->execute();

        $_SESSION['message'] = "Registracija uspešna! Sada se možete prijaviti.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registracija</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body class="container mt-4">

<h2>Registracija</h2>
<?php include 'alerts.php'; ?>

<form method="post">
    <div class="mb-3">
        <label>Username:</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Lozinka:</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <!-- ✅ Novo polje za izbor uloge -->
    <div class="mb-3">
        <label>Uloga:</label>
        <select name="role" class="form-select" required>
            <option value="visitor">Posetilac</option>
            <option value="artist">Izvođač</option>
            <option value="organizer">Organizator</option>
        </select>
    </div>

    <button type="submit" name="register" class="btn btn-primary">Registruj se</button>
</form>

<p>Već imate nalog? <a href="login.php">Prijavite se</a></p>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
