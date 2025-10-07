<?php
session_start();
require 'dbconn.php';
include 'artist_nav.php';

$artist_id = $_SESSION['artist_id'];
$noti = $conn->query("SELECT * FROM notifications WHERE artist_id = $artist_id ORDER BY created_at DESC");
$conn->query("UPDATE notifications SET is_read = 1 WHERE artist_id = $artist_id");
?>
<div class="container mt-4">
<h2>📩 Obaveštenja</h2>
<ul class="list-group">
<?php while($n = $noti->fetch_assoc()): ?>
  <li class="list-group-item"><?= htmlspecialchars($n['message']) ?> 
  <small class="text-muted">(<?= $n['created_at'] ?>)</small></li>
<?php endwhile; ?>
</ul>
</div>
