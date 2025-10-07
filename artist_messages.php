<?php
session_start();
require 'dbconn.php';
include 'artist_nav.php';


if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'artist') {
    header("Location: login.php");
    exit;
}

$artist_id = $_SESSION['artist_id'];

// Приказ коментара
$comments = $conn->prepare("
    SELECT c.id, c.comment, c.created_at, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id
    WHERE c.artist_id = ?
    ORDER BY c.created_at DESC
");
$comments->bind_param("i", $artist_id);
$comments->execute();
$res = $comments->get_result();

// Слање одговора
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = trim($_POST['reply']);
    $comment_id = intval($_POST['comment_id']);
    if($reply != "") {
        $stmt = $conn->prepare("INSERT INTO artist_replies (comment_id, artist_id, reply_text, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $comment_id, $artist_id, $reply);
        $stmt->execute();
    }
    header("Location: artist_messages.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Komentari fanova</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>💬 Komentari fanova</h2>
<?php while($row = $res->fetch_assoc()): ?>
<div class="border rounded p-2 mb-2">
    <strong><?php echo htmlspecialchars($row['username']); ?>:</strong>
    <p><?php echo htmlspecialchars($row['comment']); ?></p>
    <small class="text-muted"><?php echo $row['created_at']; ?></small>

    <form method="post" class="mt-2">
        <input type="hidden" name="comment_id" value="<?php echo $row['id']; ?>">
        <textarea name="reply" class="form-control mb-2" placeholder="Odgovorite..." required></textarea>
        <button type="submit" class="btn btn-sm btn-outline-primary">Pošalji</button>
    </form>

    <?php
    $replies = $conn->query("SELECT reply_text, created_at FROM artist_replies WHERE comment_id = {$row['id']} ORDER BY created_at ASC");
    if($replies->num_rows > 0):
        while($r = $replies->fetch_assoc()):
    ?>
        <div class="bg-light p-2 ms-4 mt-1 rounded">
            <strong>Odgovor:</strong> <?php echo htmlspecialchars($r['reply_text']); ?><br>
            <small class="text-muted"><?php echo $r['created_at']; ?></small>
        </div>
    <?php endwhile; endif; ?>
</div>
<?php endwhile; ?>

<p><a href="artist_dashboard.php" class="btn btn-secondary mt-3">← Nazad na statistiku</a></p>

</body>
</html>
