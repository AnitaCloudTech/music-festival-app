$conn->query("INSERT INTO comments (user_id, artist_id, content, created_at)
              VALUES ($user_id, $artist_id, '$content', NOW())");

// обавештење извођачу
$msg = "Нови коментар од корисника " . $_SESSION['username'];
$conn->query("INSERT INTO notifications (artist_id, message) VALUES ($artist_id, '$msg')");
