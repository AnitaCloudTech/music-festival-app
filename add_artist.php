<?php
session_start();
require '../dbconn.php';
include '../alerts.php';

// ✅ Dozvoljeno i adminu i organizatoru
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'organizer'])){
    echo "Nemate pristup ovoj stranici.";
    exit;
}

if(isset($_POST['add_artist'])){
    $name = $conn->real_escape_string($_POST['name']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $bio = $conn->real_escape_string($_POST['bio']);
    
    // Upload slike
    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image = 'images/'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../'.$image);
    }

    // Dodavanje izvođača
    $stmt = $conn->prepare("INSERT INTO artists (name, genre, bio, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $genre, $bio, $image);
    if($stmt->execute()){
        $_SESSION['message'] = "✅ Izvođač uspešno dodat!";
    } else {
        $_SESSION['error'] = "❌ Došlo je do greške: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Dodaj izvođača</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Dodaj izvođača</h2>
<?php include '../alerts.php'; ?>

<form method="post" enctype="multipart/form-data">
    <label>Ime:</label>
    <input type="text" name="name" class="form-control" required><br>
    
    <label>Žanr:</label>
    <input type="text" name="genre" class="form-control"><br>
    
    <label>Biografija:</label>
    <textarea name="bio" class="form-control"></textarea><br>
    
    <label>Slika:</label>
    <input type="file" name="image" class="form-control"><br><br>
    
    <button type="submit" name="add_artist" class="btn btn-primary">💾 Dodaj izvođača</button>
</form>

<p class="mt-3"><a href="dashboard.php" class="btn btn-secondary">⬅ Nazad</a></p>

</body>
</html>
