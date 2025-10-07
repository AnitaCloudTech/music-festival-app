<?php
session_start();
require 'dbconn.php';
include 'alerts.php';
include 'artist_nav.php';


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'artist'){
    $_SESSION['error'] = "Nemate pristup ovoj stranici.";
    header("Location: index.php");
    exit;
}

$artist_id = $_SESSION['artist_id'];
$artist = $conn->query("SELECT * FROM artists WHERE id=$artist_id")->fetch_assoc();

if(isset($_POST['update'])){
    $name = $conn->real_escape_string($_POST['name']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $bio = $conn->real_escape_string($_POST['bio']);
    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $image = 'images/'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    } else {
        $image = $artist['image'];
    }
    $conn->query("UPDATE artists SET name='$name', genre='$genre', bio='$bio', image='$image' WHERE id=$artist_id");
    $_SESSION['message'] = "Profil uspešno ažuriran!";
    header("Location: artist_edit.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Uredi profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="container mt-4">

<h2>Uredi profil</h2>
<?php include 'alerts.php'; ?>
<form method="post" enctype="multipart/form-data">
    Ime: <input type="text" name="name" class="form-control" value="<?php echo $artist['name']; ?>" required><br>
    Žanr: <input type="text" name="genre" class="form-control" value="<?php echo $artist['genre']; ?>"><br>
    Bio: <textarea name="bio" class="form-control"><?php echo $artist['bio']; ?></textarea><br>
    Slika: <input type="file" name="image" class="form-control"><br>
    <img src="images/<?php echo $artist['image']; ?>" width="150"><br><br>
    <button type="submit" name="update" class="btn btn-primary">Sačuvaj</button>
</form>

<p><a href="index.php">Nazad na program festivala</a></p>

</body>
</html>
