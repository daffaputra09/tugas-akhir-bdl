document.addEventListener('DOMContentLoaded', function() {
    
    // Auto hide alert setelah 5 detik
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.display = 'none';
        }, 5000);
    });

    // Highlight menu aktif
    var currentUrl = window.location.href;
    var menuLinks = document.querySelectorAll('.menu-link');
    menuLinks.forEach(function(link) {
        if (currentUrl.indexOf(link.getAttribute('href')) > -1) {
            link.classList.add('active');
        }
    });

    // Format input NIM (hanya angka)
    var nimInput = document.getElementById('nim');
    if (nimInput) {
        nimInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Format input no HP (hanya angka)
    var phoneInput = document.getElementById('no_hp');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Submit search dengan Enter
    var searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }
});

// Konfirmasi hapus
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus data ini?');
}
