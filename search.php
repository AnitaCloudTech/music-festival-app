<?php
require 'dbconn.php';
include 'alerts.php';

$query = "";
if(isset($_GET['query'])){
    $query = $conn->real_escape_string($_GET['query']);
}

$sql = "SELECT * FROM artists WHERE name LIKE '%$query%' OR genre LIKE '%$query%'";
$result = $conn->query($sql);
?>

<h2>Pretraga izvođača</h2>

<form method="get" action="search.php">
    <input type="text" name="query" value="<?php echo htmlspecialchars($query); ?>" placeholder="Ime ili žanr">
    <button type="submit">Pretraži</button>
</form>

<table border="1">
    <tr>
        <th>Ime</th>
        <th>Žanr</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><a href="artist.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
        <td><?php echo $row['genre']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
