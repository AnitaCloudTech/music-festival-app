<?php
session_start();
require '../dbconn.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "⛔ Nemate pristup ovoj stranici.";
    exit;
}

if(isset($_POST['add_festival'])){
    $name = $conn->real_escape_string($_POST['name']);
    $location = $conn->real_escape_string($_POST['location']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);

    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image = 'images/'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../'.$image);
    }

    if($conn->query("INSERT INTO festivals (name, location, date, description, image) 
                     VALUES ('$name', '$location', '$date', '$description', '$image')")){
        $msg = "✅ Festival uspešno dodat!";
    } else {
        $msg = "❌ Greška pri dodavanju festivala.";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>🎉 Dodaj festival | MyFestival Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
.navbar { background: linear-gradient(90deg, #3b0a45, #ff6f00); }
.navbar-brand { color: white !important; font-weight: bold; }
.card { border-radius: 15px; padding: 2rem; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.btn-primary { background-color: #6a0dad; border: none; }
.btn-primary:hover { background-color: #580b9e; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">🎛️ Admin Panel</a>
    <a href="../logout.php" class="btn btn-warning btn-sm">🚪 Odjava</a>
  </div>
</nav>

<div class="container">
  <div class="card mx-auto" style="max-width:700px;">
    <h3 class="text-center mb-4">🎉 Dodaj novi festival</h3>

    <?php if(!empty($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Naziv festivala</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Lokacija</label>
        <input type="text" name="location" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Datum</label>
        <input type="date" name="date" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Opis</label>
        <textarea name="description" class="form-control" rows="4"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Slika</label>
        <input type="file" name="image" class="form-control">
      </div>

      <div class="text-center">
        <button type="submit" name="add_festival" class="btn btn-primary px-5">💾 Sačuvaj festival</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
