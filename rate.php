<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Morate biti prijavljeni da biste ocenili izvođača.";
    header("Location: login.php");
    exit;
}

if (isset($_POST['artist_id']) && isset($_POST['rating'])) {
    $user_id = $_SESSION['user_id'];
    $artist_id = intval($_POST['artist_id']);
    $rating = intval($_POST['rating']);

    // Provera da li postoji izvođač
    $stmt = $conn->prepare("SELECT id FROM artists WHERE id = ?");
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $artist_result = $stmt->get_result();

    if ($artist_result->num_rows === 0) {
        $_SESSION['error'] = "Izvođač nije pronađen.";
        header("Location: index.php");
        exit;
    }

    // Provera da li je korisnik već ocenio izvođača
    $stmt = $conn->prepare("SELECT id FROM ratings WHERE user_id = ? AND artist_id = ?");
    $stmt->bind_param("ii", $user_id, $artist_id);
    $stmt->execute();
    $existing = $stmt->get_result();

    if ($existing->num_rows > 0) {
        // Ažuriraj postojeću ocenu
        $stmt = $conn->prepare("UPDATE ratings SET rating = ?, created_at = NOW() WHERE user_id = ? AND artist_id = ?");
        $stmt->bind_param("iii", $rating, $user_id, $artist_id);
        $stmt->execute();
        $_SESSION['message'] = "Vaša ocena je ažurirana.";
    } else {
        // Dodaj novu ocenu
        $stmt = $conn->prepare("INSERT INTO ratings (user_id, artist_id, rating, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iii", $user_id, $artist_id, $rating);
        $stmt->execute();
        $_SESSION['message'] = "Ocena uspešno dodata! ⭐";
    }

    header("Location: artist.php?id=$artist_id");
    exit;
} else {
    $_SESSION['error'] = "Nedostaju podaci za ocenjivanje.";
    header("Location: index.php");
    exit;
}
?>
