<?php
session_start();
require 'dbconn.php';
include 'alerts.php';

$poll_id = intval($_GET['id']);
$poll = $conn->query("SELECT * FROM polls WHERE id=$poll_id")->fetch_assoc();

if(!$poll){
    echo "Anketa ne postoji.";
    exit;
}

// Glasanje
if(isset($_POST['vote'])){
    $user_id = $_SESSION['user_id'];
    $vote = $conn->real_escape_string($_POST['vote_option']);

    $check = $conn->query("SELECT * FROM poll_votes WHERE poll_id=$poll_id AND user_id=$user_id");
    if($check->num_rows == 0){
        $conn->query("INSERT INTO poll_votes (poll_id, user_id, vote_option) VALUES ($poll_id, $user_id, '$vote')");
        $_SESSION['message'] = "Uspešno ste glasali!";
    } else {
        $_SESSION['error'] = "Već ste glasali u ovoj anketi.";
    }
    header("Location: poll.php?id=$poll_id");
    exit;
}

// Prikaz opcija (jednostavno tekstualno, po svakoj voti)
$options = ["Opcija 1","Opcija 2","Opcija 3"];
?>

<h2>Anketa: <?php echo $poll['question']; ?></h2>
<?php include 'alerts.php'; ?>
<form method="post">
    <?php foreach($options as $opt): ?>
        <input type="radio" name="vote_option" value="<?php echo $opt; ?>" required> <?php echo $opt; ?><br>
    <?php endforeach; ?>
    <br>
    <button type="submit" name="vote">Glasaj</button>
</form>
