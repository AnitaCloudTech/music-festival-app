<nav class="navbar navbar-expand-lg sticky-top" style="background: linear-gradient(90deg, #6a0dad, #ff6f00); box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="artist_dashboard.php">
      🎤 MyFestival — Izvođač
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link text-white nav-hover" href="artist_dashboard.php">📊 Statistika</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white nav-hover" href="artist_edit.php">🖋 Uredi profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white nav-hover" href="artist_messages.php">💬 Komentari</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-warning fw-semibold nav-hover" href="logout.php">🚪 Odjava</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
.nav-hover {
  transition: color 0.3s ease, transform 0.2s ease;
}
.nav-hover:hover {
  color: #ffe8a1 !important;
  transform: scale(1.05);
}
.navbar-brand:hover {
  text-shadow: 0 0 8px rgba(255,255,255,0.6);
}
</style>
