<?php
session_start();
require 'dbconn.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $festival_id = intval($_POST['festival_id']);
    $user_id = $_SESSION['user_id'];
    $quantity = intval($_POST['quantity']);

    $stmt = $conn->prepare("INSERT INTO tickets (user_id, festival_id, quantity, created_at) VALUES (?, ?, ?, NOW())");

    $stmt->bind_param("iii", $user_id, $festival_id, $quantity);
    $stmt->execute();

    $message = "✅ Uspešno ste rezervisali $quantity ulaznica!";
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Kupovina ulaznica</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Kupovina ulaznica</h2>
<?php if($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="festival_id" value="1">
    <label for="quantity">Broj ulaznica:</label>
    <input type="number" name="quantity" id="quantity" class="form-control mb-3" min="1" max="10" required>
    <button type="submit" class="btn btn-primary">Kupi</button>
</form>

<p class="mt-3"><a href="index.php">← Nazad na program</a></p>

</body>
</html>
