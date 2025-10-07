<?php
require 'dbconn.php';
include 'header.php';

if(!isset($_SESSION['user_id'])){
    echo "Prijavite se da vidite obaveštenja.";
    exit;
}

$user_id = intval($_SESSION['user_id']);

$sql = "SELECT p.*, a.name AS artist_name, a.genre
        FROM favorites f
        JOIN program p ON f.artist_id = p.artist_id
        JOIN artists a ON p.artist_id = a.id
        WHERE f.user_id=$user_id
        AND p.performance_time >= NOW()
        ORDER BY p.performance_time";

$result = $conn->query($sql);
?>

<h2>Nadolazeći nastupi omiljenih izvođača</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Vreme</th>
            <th>Izvođač</th>
            <th>Žanr</th>
            <th>Scena</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['performance_time']; ?></td>
            <td><?php echo $row['artist_name']; ?></td>
            <td><?php echo $row['genre']; ?></td>
            <td><?php echo $row['stage']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
