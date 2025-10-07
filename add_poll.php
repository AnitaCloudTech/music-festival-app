<?php
session_start();
require '../dbconn.php';
include '../alerts.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "⛔ Nemate pristup ovoj stranici.";
    exit;
}

if(isset($_POST['create_poll'])){
    $question = $conn->real_escape_string($_POST['question']);
    $options = array_filter($_POST['options']);

    if(!empty($question) && count($options) >= 2){
        $conn->query("INSERT INTO polls (question) VALUES ('$question')");
        $poll_id = $conn->insert_id;

        foreach($options as $opt){
            $opt = $conn->real_escape_string($opt);
            $conn->query("INSERT INTO poll_options (poll_id, option_text) VALUES ($poll_id, '$opt')");
        }

        $_SESSION['message'] = "✅ Anketa uspešno kreirana!";
    } else {
        $_SESSION['error'] = "Unesite pitanje i najmanje dve opcije.";
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Kreiraj anketu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>🗳️ Kreiraj novu anketu</h2>
<?php include '../alerts.php'; ?>

<form method="post">
    <div class="mb-3">
        <label>Pitanje:</label>
        <input type="text" name="question" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Opcije (najmanje 2):</label>
        <input type="text" name="options[]" class="form-control mb-2" placeholder="Opcija 1" required>
        <input type="text" name="options[]" class="form-control mb-2" placeholder="Opcija 2" required>
        <input type="text" name="options[]" class="form-control mb-2" placeholder="Opcija 3">
        <input type="text" name="options[]" class="form-control mb-2" placeholder="Opcija 4">
    </div>

    <button type="submit" name="create_poll" class="btn btn-primary">Kreiraj anketu</button>
</form>

<a href="index.php" class="btn btn-secondary mt-3">← Nazad</a>

</body>
</html>
