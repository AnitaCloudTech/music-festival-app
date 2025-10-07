<?php
session_start();
require 'dbconn.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if(isset($_POST['artist_id'])) {
    $user_id = $_SESSION['user_id'];
    $artist_id = intval($_POST['artist_id']);

    $check = $conn->prepare("SELECT * FROM favorites WHERE user_id=? AND artist_id=?");
    $check->bind_param("ii", $user_id, $artist_id);
    $check->execute();
    $exists = $check->get_result();

    if($exists->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO favorites (user_id, artist_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $artist_id);
        $stmt->execute();
    }
}

header("Location: artist.php?id=".$_POST['artist_id']);
exit;
?>
