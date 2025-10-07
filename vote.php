<?php
session_start();
require 'dbconn.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $option_id = intval($_POST['option_id']);
    $poll_id = intval($_POST['poll_id']);
    $conn->query("UPDATE poll_options SET votes = votes + 1 WHERE id = $option_id");
    $_SESSION['message'] = "Hvala na glasanju!";
}

header("Location: index.php");
exit;
?>
