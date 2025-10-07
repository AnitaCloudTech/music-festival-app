<?php
session_start();
require 'dbconn.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['artist_id'])) {
    $user_id = $_SESSION['user_id'];
    $artist_id = intval($_POST['artist_id']);
    $comment = trim($_POST['comment']);

    if($comment !== '') {
        $stmt = $conn->prepare("INSERT INTO comments (artist_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $artist_id, $user_id, $comment);
        $stmt->execute();
    }
}

header("Location: artist.php?id=" . intval($_POST['artist_id']));
exit;
?>
