<?php
session_start();
require '../dbconn.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "⛔ Nemate pristup ovoj stranici.";
    exit;
}

// Dodavanje nastupa
if(isset($_POST['add_program'])){
    $festival_id = intval($_POST['festival_id']);
    $artist_id = intval($_POST['artist_id']);
    $time = $conn->real_escape_string($_POST['performance_time']);
    $stage = $conn->real_escape_string($_POST['stage']);

    if($conn->query("INSERT INTO program (festival_id, artist_id, performance_time, stage) 
                     VALUES ($festival_id, $artist_id, '$time', '$stage')")){
        $msg = "✅ Nastup uspešno dodat!";
    } else {
        $msg = "❌ Greška pri dodavanju nastupa: " . $conn->error;
    }
}

// Učitavanje festivala i izvođača
$festivals = $conn->query("SELECT id, name FROM festivals");
$artists = $conn->query("SELECT id, name FROM artists");
$result = $conn->query("SELECT p.id, f.name AS festival, a.name AS artist, p.stage, p.performance_time 
                        FROM program p
                        JOIN festivals f ON p.festival_id = f.id
                        JOIN artists a ON p.artist_id = a.id");
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>📅 Uredi program | MyFestival Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
.navbar { background: linear-gradient(90deg, #3b0a45, #ff6f00); }
.navbar-brand { color: white !important; font-weight: bold; }
.card { border-radius: 15px; padding: 2rem; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.table th { background: #6a0dad; color: white; }
.btn-primary { background-color: #6a0dad; border: none; }
.btn-primary:hover { background-color: #580b9e; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">🎛️ Admin Panel</a>
    <a href="../logout.php" class="btn btn-warning btn-sm">🚪 Odjava</a>
  </div>
</nav>

<div class="container">
  <div class="card mb-5">
    <h3 class="text-center mb-4">📅 Dodaj nastup u program</h3>

    <?php if(!empty($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>

    <form method="POST">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Festival</label>
          <select name="festival_id" class="form-select" required>
            <option value="">-- Odaberi --</option>
            <?php while($f = $festivals->fetch_assoc()): ?>
              <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Izvođač</label>
          <select name="artist_id" class="form-select" required>
            <option value="">-- Odaberi --</option>
            <?php while($a = $artists->fetch_assoc()): ?>
              <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Vreme nastupa</label>
          <input type="datetime-local" name="performance_time" class="form-control" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">Scena</label>
          <input type="text" name="stage" class="form-control">
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" name="add_program" class="btn btn-primary px-5">💾 Sačuvaj</button>
      </div>
    </form>
  </div>

  <div class="card">
    <h4 class="mb-3">🎶 Trenutni program</h4>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Festival</th>
          <th>Izvođač</th>
          <th>Vreme</th>
          <th>Scena</th>
        </tr>
      </thead>
      <tbody>
        <?php if($result->num_rows > 0): $i=1; while($p = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($p['festival']) ?></td>
          <td><?= htmlspecialchars($p['artist']) ?></td>
          <td><?= htmlspecialchars($p['performance_time']) ?></td>
          <td><?= htmlspecialchars($p['stage']) ?></td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="5" class="text-center">📭 Nema unetih nastupa</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
