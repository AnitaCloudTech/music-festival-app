<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Morate biti prijavljeni da biste dodali omiljene izvođače.";
    header("Location: login.php");
    exit;
}

if (isset($_POST['artist_id'])) {
    $user_id = $_SESSION['user_id'];
    $artist_id = intval($_POST['artist_id']);

    // Proveravamo da li postoji izvođač
    $stmt = $conn->prepare("SELECT id FROM artists WHERE id = ?");
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Izvođač nije pronađen.";
        header("Location: index.php");
        exit;
    }

    // Proveravamo da li je već u omiljenima
    $stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND artist_id = ?");
    $stmt->bind_param("ii", $user_id, $artist_id);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {
        $_SESSION['message'] = "Ovaj izvođač je već u omiljenima.";
    } else {
        $stmt = $conn->prepare("INSERT INTO favorites (user_id, artist_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $artist_id);
        $stmt->execute();
        $_SESSION['message'] = "Izvođač uspešno dodat u omiljene! ❤️";
    }
} else {
    $_SESSION['error'] = "Nedostaje ID izvođača.";
}

header("Location: index.php");
exit;
?>
