console.log('script.js loaded');

// Popup notifikacije za omiljene izvođače
document.addEventListener("DOMContentLoaded", () => {
    const favButtons = document.querySelectorAll("form button[name='favorite']");
    favButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            alert("Izvođač dodat u omiljene!");
            button.closest("form").submit();
        });
    });
});

// Pretraga uživo na index.php
const searchInput = document.getElementById("searchInput");
if(searchInput){
    searchInput.addEventListener("input", () => {
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
}
// test loading
console.log('script.js loaded');

$(document).ready(function() {
    $('#rateForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'rate.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('#rateMsg').text(response.message);
                if (response.status === 'success') {
                    $('#avg-rating').text(response.avg);
                }
            },
            error: function() {
                $('#rateMsg').text('Došlo je do greške.');
            }
        });
    });
});


// ❤️ Dodavanje/uklanjanje omiljenih izvođača (nova verzija)
$(document).ready(function() {
    $('#favBtn').click(function() {
        const artistId = $(this).data('artist');
        $.ajax({
            url: 'favorites.php',
            type: 'POST',
            data: { artist_id: artistId },
            dataType: 'json',
            success: function(response) {
                $('#favMsg').text(response.message);
                if (response.status === 'added') {
                    $('#favBtn').text('💔 Ukloni iz omiljenih');
                } else if (response.status === 'removed') {
                    $('#favBtn').text('❤️ Dodaj u omiljene');
                }
            },
            error: function() {
                $('#favMsg').text('Greška pri slanju zahteva.');
            }
        });
    });
});
.navbar {
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.navbar .nav-link:hover {
  text-shadow: 0 0 5px #fff, 0 0 10px #ff9900;
  transition: 0.3s;
}

body {
  background-color: #f7f5ff;
}
