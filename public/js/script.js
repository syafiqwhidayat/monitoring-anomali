document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById('toggle-sidebar');
    const body = document.body;

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            // Menambah/menghapus class sidebar-collapsed pada tag body
            body.classList.toggle('sidebar-collapsed');
            
            // Opsional: Simpan status sidebar di localStorage agar saat refresh tidak berubah
            const isCollapsed = body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarStatus', isCollapsed ? 'hidden' : 'visible');
        });
    }

    // Cek status terakhir saat halaman dimuat
    if (localStorage.getItem('sidebarStatus') === 'hidden') {
        body.classList.add('sidebar-collapsed');
    }
});
